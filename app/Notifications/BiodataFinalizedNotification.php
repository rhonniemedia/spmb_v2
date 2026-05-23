<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BiodataFinalizedNotification extends Notification
{
    use Queueable;

    /**
     * Membuat instance notifikasi baru.
     */
    public function __construct()
    {
        //
    }

    /**
     * Menentukan saluran pengiriman (hanya disimpan di database internal).
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Menyimpan data notifikasi ke dalam format JSON di database.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Biodata Berhasil Dikunci!',
            'message' => 'Biodata Anda telah final. Langkah berikutnya: silakan klik di sini untuk memilih jalur masuk dan kompetensi keahlian (jurusan) pilihan Anda.',
            'icon' => 'fa-file-signature',
            'color' => 'text-indigo-500',
            'action_url' => route('registration'), // Langsung mengarah ke form pemilihan jurusan
        ];
    }
}
