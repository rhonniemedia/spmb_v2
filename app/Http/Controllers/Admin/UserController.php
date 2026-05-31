<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filterRole = $request->input('filter_role');

        $stats = $this->getGlobalStats();

        $users = User::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })
            ->when($filterRole, function ($query) use ($filterRole) {
                $query->where('role', $filterRole);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.pengguna.data-pengguna', array_merge(
            compact('users', 'search', 'filterRole'),
            $stats
        ));
    }

    private function getGlobalStats()
    {
        $totalUsers = User::count();
        $adminStats = User::whereIn('role', ['superadmin', 'admin'])->count();
        $verifikatorStats = User::where('role', 'verifikator')->count();
        $observatorStats = User::where('role', 'observator')->count();
        $userStats = User::where('role', 'user')->count();

        return compact('totalUsers', 'adminStats', 'verifikatorStats', 'observatorStats', 'userStats');
    }

    /**
     * Update data pengguna (PUT /admin/user-data/{id})
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role'  => 'required|in:superadmin,admin,verifikator,observator,user',
            'reset_password' => 'nullable|boolean', // Terima input toggle
        ];

        $validated = $request->validate($rules, [
            'name.required'  => 'Nama pengguna wajib diisi.',
            'email.required' => 'Email pengguna wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email ini sudah digunakan oleh pengguna lain.',
            'role.required'  => 'Role / Hak Akses wajib dipilih.',
            'role.in'        => 'Pilihan role tidak valid.',
        ]);

        $user = User::findOrFail($id);

        // 1. Isi data dasar
        $user->fill([
            'name'  => $validated['name'],
            'email' => $validated['email'],
            'role'  => $validated['role'],
        ]);

        // 2. Eksekusi Reset Password JIKA toggle dihidupkan
        if ($request->boolean('reset_password')) {
            $user->password = bcrypt('Password123*');
        }

        // 3. Cek apakah ada yang berubah (nama, email, role, ATAU password)
        if ($user->isClean()) {
            return response()->json([
                'status'  => 'info',
                'message' => 'Tidak ada data yang diubah, sistem mengabaikan pembaruan.'
            ]);
        }

        // 4. Simpan ke database
        $user->save();

        // 5. Hitung statistik & render HTMX
        $stats = $this->getGlobalStats();
        $stats['isOob'] = true;

        $u = $user;
        $rowHtml = view('pages.admin.pengguna.partials._row-user', compact('u'))->render();
        $statsHtml = view('pages.admin.pengguna.partials._stats-cards', $stats)->render();

        return $rowHtml . $statsHtml;
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if (Auth::id() === $user->id) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tindakan ditolak! Anda tidak dapat menonaktifkan akun yang sedang digunakan.'
            ], 403);
        }

        $user->delete();

        $stats = $this->getGlobalStats();
        $stats['isOob'] = true;

        $statsHtml = view('pages.admin.pengguna.partials._stats-cards', $stats)->render();

        return response($statsHtml);
    }
}
