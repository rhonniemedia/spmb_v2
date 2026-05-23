<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Mengembalikan fragment HTML untuk dropdown list HTMX beserta Swap OOB.
     */
    public function dropdown(): Response
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return response('', 200);
        }

        $unreadCount = $user->unreadNotifications()->count();
        $allNotifications = $user->notifications()->take(5)->get();

        // 1. Render baris-baris HTML list notifikasi
        $html = '';
        foreach ($allNotifications as $notification) {
            $data = $notification->data;

            $readClass = $notification->read_at ? 'opacity-60' : 'bg-primary/5';
            $boldClass = $notification->read_at ? 'font-normal' : 'font-bold';
            $icon = $data['icon'] ?? 'fa-info';
            $color = $data['color'] ?? 'text-gray-500';
            $title = $data['title'] ?? 'Pemberitahuan';
            $message = $data['message'] ?? '';
            $actionUrl = route('notifications.read', $notification->id);
            $timeAgo = $notification->created_at ? $notification->created_at->diffForHumans() : '';

            $html .= "
                <a href='{$actionUrl}' class='flex gap-3 px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 {$readClass}'>
                    <div class='w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center shrink-0'>
                        <i class='fa-solid {$icon} {$color} text-sm'></i>
                    </div>
                    <div class='flex-1 min-w-0'>
                        <p class='text-xs font-bold text-gray-800 {$boldClass}'>{$title}</p>
                        <p class='text-[11px] text-[#6A7686] mt-0.5 line-clamp-2 leading-relaxed'>{$message}</p>
                        <span class='text-[9px] text-gray-400 block mt-1'>
                            <i class='fa-regular fa-clock mr-0.5'></i> {$timeAgo}
                        </span>
                    </div>
                </a>";
        }

        if ($allNotifications->isEmpty()) {
            $html .= '
                <div class="text-center py-8 text-gray-400">
                    <i class="fa-solid fa-bell-slash text-xl mb-1 block text-gray-300"></i>
                    <span class="text-xs">Belum ada pemberitahuan</span>
                </div>';
        }

        // 2. Trik Swap Out-of-Band (OOB) HTMX untuk indikator titik merah desktop
        $badgeHtml = '<div id="notification-badge" hx-swap-oob="true">';
        if ($unreadCount > 0) {
            $badgeHtml .= '<div class="absolute top-[5px] right-[5px] w-[8px] h-[8px] rounded-full bg-primary border-2 border-white animate-pulse"></div>';
        }
        $badgeHtml .= '</div>';

        // 3. Swap OOB untuk memperbarui text counter di header dropdown desktop
        $countHtml = '<span id="notification-count-header" hx-swap-oob="true">';
        if ($unreadCount > 0) {
            $countHtml .= '<span class="text-[10px] bg-red-50 text-red-500 px-2 py-0.5 rounded-full font-bold">' . $unreadCount . ' Baru</span>';
        }
        $countHtml .= '</span>';

        // 4. Swap OOB untuk memperbarui text counter versi mobile menu
        $countMobileHtml = '<span id="notification-count-mobile" hx-swap-oob="true">';
        if ($unreadCount > 0) {
            $countMobileHtml .= '<span class="text-[10px] text-primary font-bold bg-primary-light px-2 py-0.5 rounded-full">' . $unreadCount . ' Baru</span>';
        }
        $countMobileHtml .= '</span>';

        return response($html . $badgeHtml . $countHtml . $countMobileHtml, 200)
            ->header('Content-Type', 'text/html');
    }

    /**
     * Menandai notifikasi telah dibaca dan mengalihkan pendaftar ke target URL.
     */
    public function read(string $id): RedirectResponse
    {
        /** @var DatabaseNotification $notification */
        $notification = DatabaseNotification::findOrFail($id);

        if ($notification->notifiable_id === Auth::id()) {
            $notification->markAsRead();
        }

        $data = $notification->data;
        $destinationUrl = $data['action_url'] ?? route('dashboard');

        return redirect()->to($destinationUrl);
    }
}
