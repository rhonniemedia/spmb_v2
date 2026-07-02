@php
$fullName = $r->registrationData->personalData->full_name ?? 'Tanpa Nama';
$init = strtoupper(substr($fullName, 0, 2));
$colors = ['linear-gradient(135deg,#FF1443,#FF6B6B)', 'linear-gradient(135deg,#3B82F6,#93C5FD)', 'linear-gradient(135deg,#F59E0B,#FCD34D)', 'linear-gradient(135deg,#8B5CF6,#A78BFA)'];
$color = $colors[isset($loop) ? ($loop->index % 4) : 0];

$phone = $r->registrationData->personalData->phone_number ?? '-';
$sekolah = $r->registrationData->personalData->previous_school ?? '-';
$regNumber = $r->registrationData->registration_number ?? '-';
$jalur = $r->registrationData->admissionPath->name ?? 'Jalur Reguler';
$konsentrasi = $r->registrationData->latestSelectionResult->acceptedConcentration->name ?? '-';

$dataStatusLabel = $r->data_status === 'complete' ? 'Data Lengkap' : 'Data Belum Lengkap';

$verifLabel = match ($r->verification_status) {
'verified' => 'Terverifikasi',
'rejected' => 'Ditolak',
'processing' => 'Diproses',
default => 'Menunggu',
};

// ── DATA GABUNGAN UNTUK MODAL STATUS ──
$alpineData = [
'id' => $r->id,
'reg_number' => $regNumber,
'name' => $fullName,
'init' => $init,
'color' => $color,
'sekolah' => $sekolah,
'phone' => $phone,
'jalur' => $jalur,
'konsentrasi' => $konsentrasi,

'data_status' => $r->data_status,
'verification_status' => $r->verification_status,
'verification_notes' => $r->verification_notes ?? '',
'verified_by' => $r->verifiedBy->name ?? '-',

'announced_at' => optional($r->announced_at)->translatedFormat('d M Y, H:i') ?? '-',
'confirmed_at' => optional($r->confirmed_at)->translatedFormat('d M Y, H:i') ?? '-',
're_registered_at' => optional($r->re_registered_at)->translatedFormat('d M Y, H:i') ?? '-',
'verified_at' => optional($r->verified_at)->translatedFormat('d M Y, H:i') ?? '-',
'completed_at' => optional($r->completed_at)->translatedFormat('d M Y, H:i') ?? '-',
];
@endphp

<tr id="row-{{ $r->id }}" class="border-b border-border hover:bg-muted/50 transition-colors">
    <td class="px-4 py-4">
        <div class="flex items-center gap-3">
            <div
                @style([ "background: {$color}" ,
                ])
                class="h-10 w-10 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0">
                {{ $init }}
            </div>
            <div>
                <div class="font-semibold text-foreground text-sm uppercase">{{ $fullName }}</div>
                <div class="text-xs text-secondary font-mono">{{ $regNumber }}</div>
            </div>
        </div>
    </td>

    <td class="px-4 py-4">
        <div class="text-sm font-medium text-foreground uppercase">{{ $sekolah }}</div>
        <div class="text-xs text-secondary">{{ $jalur }} &middot; {{ $konsentrasi }}</div>
    </td>

    <!-- SEBELUMNYA ADA 2 KOLOM (Status Berkas & Verifikasi), SEKARANG DIGABUNG -->
    <td class="px-4 py-4">
        <div class="flex items-center gap-2">

            {{-- 1. Ikon Status Berkas (Menggunakan ikon File) --}}
            @if($r->data_status === 'complete')
            <div title="Data Lengkap" class="p-1.5 rounded-md bg-success/10 text-success-dark">
                <i data-lucide="file-check" class="size-4"></i>
            </div>
            @else
            <div title="Data Belum Lengkap" class="p-1.5 rounded-md bg-warning/10 text-warning-dark">
                <i data-lucide="file-x" class="size-4"></i>
            </div>
            @endif

            {{-- 2. Ikon Status Verifikasi --}}
            @if($r->verification_status === 'verified')
            <div title="Terverifikasi" class="p-1.5 rounded-md bg-success/10 text-success-dark">
                <i data-lucide="check-circle" class="size-4"></i>
            </div>
            @elseif(in_array($r->verification_status, ['pending', 'processing']))
            <div title="Menunggu / Diproses" class="p-1.5 rounded-md bg-warning/10 text-warning-dark">
                <i data-lucide="clock" class="size-4"></i>
            </div>
            @elseif($r->verification_status === 'rejected')
            <div title="Ditolak" class="p-1.5 rounded-md bg-error/10 text-error-dark">
                <i data-lucide="x-circle" class="size-4"></i>
            </div>
            @else
            <div title="Menunggu" class="p-1.5 rounded-md bg-secondary/10 text-secondary">
                <i data-lucide="help-circle" class="size-4"></i>
            </div>
            @endif

        </div>
    </td>

    <td class="px-4 py-4 text-left">
        <div class="flex items-center gap-2">
            @if(in_array(auth()->user()->role ?? '', ['superadmin', 'admin', 'verifikator']))
            <button @click="openStatus({{ json_encode($alpineData) }})" title="Kelola Status Daftar Ulang"
                class="flex items-center justify-center p-2 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary transition-colors cursor-pointer">
                <i data-lucide="settings-2" class="size-4"></i>
            </button>
            @endif
        </div>
    </td>
</tr>