<?php

namespace App\Notifications;

use App\Models\ObservationData;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ObservationResultNotification extends Notification
{
    use Queueable;

    protected $observation;

    /**
     * Membuat instance notifikasi baru.
     */
    public function __construct(ObservationData $observation)
    {
        $this->observation = $observation;
    }

    /**
     * Menentukan saluran pengiriman notifikasi (hanya database internal).
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
        $status = $this->observation->observation_status; // 'passed' atau 'failed'

        // Jika hasil pemeriksaan fisik & kesehatan dinyatakan lolos
        if ($status === 'passed') {
            return [
                'title' => 'Hasil Pemeriksaan Fisik & Kesehatan',
                'message' => 'Selamat, Anda dinyatakan MEMENUHI SYARAT (Lolos) pada tahap pemeriksaan fisik dan kesehatan.',
                'icon' => 'fa-heart-pulse',
                'color' => 'text-emerald-500',
                'action_url' => route('dashboard'),
            ];
        }

        // Jika hasil pemeriksaan fisik & kesehatan dinyatakan tidak lolos constraints
        return [
            'title' => 'Hasil Pemeriksaan Fisik & Kesehatan',
            'message' => 'Anda dinyatakan TIDAK MEMENUHI SYARAT pada tahap pemeriksaan fisik dan kesehatan. Catatan: ' . ($this->observation->observation_notes ?? '-'),
            'icon' => 'fa-heart-crack',
            'color' => 'text-red-500',
            'action_url' => route('dashboard'),
        ];
    }
}
