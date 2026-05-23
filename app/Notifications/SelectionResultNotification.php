<?php

namespace App\Notifications;

use App\Models\SelectionResult;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SelectionResultNotification extends Notification
{
    use Queueable;

    protected $result;

    /**
     * Membuat instance notifikasi baru.
     */
    public function __construct(SelectionResult $result)
    {
        $this->result = $result;
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
        $status = $this->result->status; // 'accepted' atau 'rejected'
        $concentrationName = $this->result->acceptedConcentration->name ?? 'Jurusan Pilihan';

        // Jika siswa dinyatakan lulus seleksi utama
        if ($status === 'accepted') {
            return [
                'title' => 'Selamat, Anda Dinyatakan Lulus!',
                'message' => "Selamat! Anda dinyatakan LULUS seleksi SPMB pada kompetensi keahlian: {$concentrationName}. Silakan lakukan daftar ulang.",
                'icon' => 'fa-trophy',
                'color' => 'text-yellow-500',
                'action_url' => route('dashboard'), // Dapat diarahkan ke halaman daftar ulang jika sudah siap
            ];
        }

        // Jika siswa dinyatakan tidak lulus seleksi utama
        return [
            'title' => 'Pengumuman Hasil Seleksi Kelulusan',
            'message' => 'Mohon maaf, Anda dinyatakan belum memenuhi kriteria kelulusan seleksi SPMB periode ini. Tetap semangat dan terima kasih!',
            'icon' => 'fa-circle-xmark',
            'color' => 'text-gray-400',
            'action_url' => route('dashboard'),
        ];
    }
}
