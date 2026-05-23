<?php

namespace App\Notifications;

use App\Models\RegistrationData;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RegistrationFinalizedNotification extends Notification
{
    use Queueable;

    protected RegistrationData $registration;

    /**
     * Membuat instance notifikasi baru.
     */
    public function __construct(RegistrationData $registration)
    {
        $this->registration = $registration;
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
            'title' => 'Formulir Pendaftaran Terkirim!',
            'message' => "Selamat! Pendaftaran Anda berhasil dikunci dengan Nomor: {$this->registration->registration_number}. Langkah berikutnya: Silakan cetak bukti pendaftaran dan lakukan verifikasi berkas asli ke sekolah.",
            'icon' => 'fa-file-circle-check',
            'color' => 'text-emerald-500',
            'action_url' => route('registration'), // Mengarahkan pendaftar kembali ke dashboard untuk memantau berkas
        ];
    }
}
