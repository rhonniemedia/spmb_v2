<?php

namespace App\Traits;

use App\Models\ActivityLog;
use App\Models\RegistrationData;

trait LogsActivity
{
    /**
     * Catat aktivitas ke tabel activity_logs.
     *
     * @param  string               $action       Enum: verified, rejected, document_verified, dst.
     * @param  RegistrationData|null $registration  Model pendaftar yang terkait.
     * @param  string               $description  Teks utama yang tampil di feed.
     * @param  string|null          $context      Teks detail (baris kedua di feed).
     */
    protected function logActivity(
        string $action,
        ?RegistrationData $registration = null,
        string $description = '',
        ?string $context = null,
    ): void {
        ActivityLog::record($action, $registration, $description, $context);
    }
}
