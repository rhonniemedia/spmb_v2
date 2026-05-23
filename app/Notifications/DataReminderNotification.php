<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DataReminderNotification extends Notification
{
    use Queueable;

    protected $type; // Untuk membedakan tipe pesan ('welcome' atau 'reminder')

    // Terima parameter tipe saat class dipanggil
    public function __construct($type = 'welcome')
    {
        $this->type = $type;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        // Jika baru pertama kali aktivasi/login
        if ($this->type === 'welcome') {
            return [
                'title' => 'Selamat Datang di SPMB!',
                'message' => 'Akun Anda berhasil diaktivasi. Yuk, langsung lengkapi Biodata Diri dan Data Orang Tua Anda sekarang.',
                'icon' => 'fa-user-plus',
                'color' => 'text-blue-500',
                'action_url' => route('biodata'), // Atau arahkan langsung ke halaman edit profil
            ];
        }

        // Jalur fallback jika dipakai untuk pengingat H-3 penutupan
        return [
            'title' => 'Penting: Finalisasi Biodata Anda',
            'message' => 'Waktu pendaftaran segera habis. Mohon periksa kembali dan lakukan finalisasi data Anda.',
            'icon' => 'fa-triangle-exclamation',
            'color' => 'text-amber-500',
            'action_url' => route('biodata'),
        ];
    }
}
