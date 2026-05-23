<?php

namespace App\Notifications;

use App\Models\RegistrationDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DocumentVerificationNotification extends Notification
{
    use Queueable;

    protected $document;

    /**
     * Membuat instance notifikasi baru.
     */
    public function __construct(RegistrationDocument $document)
    {
        $this->document = $document;
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
        $status = $this->document->verification_status; // 'verified' atau 'rejected'
        $docName = $this->document->requirement->name ?? 'Dokumen';

        // Jika dokumen berhasil diverifikasi oleh panitia
        if ($status === 'verified') {
            return [
                'title' => 'Berkas Terverifikasi',
                'message' => "Berkas fisik '{$docName}' Anda telah dinyatakan sah dan berhasil diverifikasi oleh panitia di sekolah.",
                'icon' => 'fa-circle-check',
                'color' => 'text-emerald-500',
                'action_url' => route('dashboard'),
            ];
        }

        // Jika dokumen ditolak karena tidak sesuai
        return [
            'title' => 'Berkas Perlu Revisi / Ditolak',
            'message' => "Berkas fisik '{$docName}' ditolak oleh verifikator. Catatan: " . ($this->document->verification_notes ?? 'Silakan periksa kembali berkas Anda.'),
            'icon' => 'fa-circle-xmark',
            'color' => 'text-red-500',
            'action_url' => route('dashboard'),
        ];
    }
}
