<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        /**
         * SINKRONISASI KATEGORI
         * Kunci array di bawah ini HARUS sama persis dengan kolom 'category' di database/seeder.
         */
        $categorySettings = [
            'Pendaftaran' => [
                'color' => 'red',
                'icon'  => 'fa-user-plus',
                'label' => 'Pendaftaran',
            ],
            'Hasil Seleksi' => [
                'color' => 'purple',
                'icon'  => 'fa-file-circle-check',
                'label' => 'Hasil Seleksi',
            ],
            'Ujian' => [
                'color' => 'yellow',
                'icon'  => 'fa-pen-to-square',
                'label' => 'Ujian',
            ],
            'Informasi' => [
                'color' => 'amber',
                'icon'  => 'fa-circle-info',
                'label' => 'Umum',
            ],
        ];

        /**
         * Map warna ke class Tailwind secara eksplisit.
         * Menggunakan warna standar: red, green, blue, amber.
         */
        $colorClassMap = [
            'red'   => ['bar' => 'bg-red-500',   'text' => 'text-red-600'],
            'purple' => ['bar' => 'bg-purple-500', 'text' => 'text-purple-600'],
            'blue'  => ['bar' => 'bg-blue-500',  'text' => 'text-blue-600'],
            'amber' => ['bar' => 'bg-amber-500', 'text' => 'text-amber-600'],
            'gray'  => ['bar' => 'bg-gray-400',  'text' => 'text-gray-500'],
        ];

        $totalActive = $announcements->count();

        // Generate summary untuk sidebar
        $summary = $announcements->groupBy('category')->map(function ($items, $key) use ($categorySettings, $colorClassMap, $totalActive) {
            $setting = $categorySettings[$key] ?? ['color' => 'gray', 'label' => $key];
            $colorKey = $setting['color'];
            $cls = $colorClassMap[$colorKey] ?? $colorClassMap['gray'];

            return [
                'label' => $setting['label'],
                'count' => $items->count(),
                'total' => $totalActive,
                'color' => $cls['bar'],
                'text'  => $cls['text'],
            ];
        });

        $urgentAnnouncement = Announcement::where('is_active', true)
            ->where('is_urgent', true)
            ->latest()
            ->first();

        return view('pages.user.pengumuman', compact('announcements', 'summary', 'urgentAnnouncement', 'categorySettings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Announcement $announcement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        //
    }
}
