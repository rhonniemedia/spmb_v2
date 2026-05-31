@php
$fullName = $p->personalData->full_name ?? 'Tanpa Nama';
$init = strtoupper(substr($fullName, 0, 2));
$colors = ['linear-gradient(135deg,#FF1443,#FF6B6B)', 'linear-gradient(135deg,#3B82F6,#93C5FD)', 'linear-gradient(135deg,#F59E0B,#FCD34D)', 'linear-gradient(135deg,#8B5CF6,#A78BFA)'];
$color = $colors[isset($loop) ? ($loop->index % 4) : 0];

// Data Personal
$phone = $p->personalData->phone_number ?? '-';
$gender = ($p->personalData->gender ?? '') === 'L' ? 'Laki-laki' : 'Perempuan';
$nisn = $p->personalData->nisn ?? '-';
$sekolah = $p->personalData->previous_school ?? '-';
$jalur = $p->admissionPath->name ?? 'Jalur Reguler';

// Mockup Berkas (Bisa disesuaikan dengan relasi tabel berkas Anda nantinya)
$berkasDiterima = [
'Akta Kelahiran', 'Ijazah SMP / Sederajat', 'Surat Keterangan Lulus (SKL)',
'Rapor Semester 1-5', 'Pas Foto 3x4', 'Surat Keterangan Domisili'
];

$hasObservation = $p->observationData !== null;
$obsData = $p->observationData;
$achievements = $p->achievements ?? collect();

// ── DATA GABUNGAN UNTUK MODAL DETAIL & MODAL OBSERVASI ──
$alpineData = [
// 1. Kebutuhan Modal Detail
'id' => $p->id,
'reg_number' => $p->registration_number ?? '-',
'name' => $fullName,
'init' => $init,
'color' => $color,
'gender' => $gender,
'nisn' => $nisn,
'sekolah' => $sekolah,
'phone' => $phone,
'jalur' => $jalur,
'jurusan1' => $p->choice1->alias ?? '-',
'jurusan2' => $p->choice2->alias ?? '-',
'jurusan3' => $p->choice3->alias ?? '-',
'status' => $p->verification_status ?? 'pending',
'statusLabel' => ucfirst($p->verification_status ?? 'pending'),
'rata_rapor' => number_format($p->report_average ?? 0, 2),
'rata_tka' => number_format($p->tka_average ?? 0, 2),
'berkas' => $berkasDiterima,

// 2. Kebutuhan Modal Observasi
'has_observation' => $hasObservation,
'has_achievement' => $achievements->isNotEmpty(),
'achievements' => $achievements->map(function ($a) {
return [
'type_label' => ucfirst($a->achievement_type),
'level_label'=> ucfirst($a->level ?? ''),
'position' => $a->leadership_position,
'ranks' => $a->class_ranks,
];
})->toArray(),

'hearing_check' => $obsData?->hearing_check ?? '',
'vision_check' => $obsData?->vision_check ?? '',
'physical_activity' => $obsData?->physical_activity ?? '',
'color_blind_check' => $obsData?->color_blind_check ?? '',

'tattoo' => $obsData?->tattoo ?? '',
'tattoo_scar' => $obsData?->tattoo_scar ?? '',
'piercing' => $obsData?->piercing ?? '',
'keloid' => $obsData?->keloid ?? '',
'minor_disability' => $obsData?->minor_disability ?? '',
'aid_tool' => $obsData?->aid_tool ?? '',

'physical_score' => $obsData?->physical_score ?? '',
'special_trait_score' => $obsData?->special_trait_score ?? '',
'achievement_score' => $obsData?->achievement_score ?? '',

'obs_status' => $obsData?->observation_status ?? 'pending',
'observation_notes' => $obsData?->observation_notes ?? '',
];
@endphp

<tr id="row-{{ $p->id }}" class="border-b border-border hover:bg-muted/50 transition-colors">
    <td class="px-4 py-4">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0" :style="'background: {{ $color }}'">{{ $init }}</div>
            <div>
                <div class="font-semibold text-foreground text-sm uppercase">{{ $fullName }}</div>
                <div class="text-xs text-secondary font-mono">{{ $p->registration_number ?? '-' }}</div>
            </div>
        </div>
    </td>

    <td class="px-4 py-4">
        <div class="text-sm font-medium text-foreground uppercase">{{ $p->personalData->previous_school ?? '-' }}</div>
        @if($phone !== '-')
        <a href="https://wa.me/62{{ ltrim($phone, '0') }}" target="_blank" class="mt-1 inline-flex items-center gap-x-1.5 text-xs text-secondary hover:text-green-600 hover:underline transition-colors">
            <i data-lucide="phone" class="size-3"></i>
            <span>{{ $phone }}</span>
        </a>
        @endif
    </td>

    <td class="px-4 py-4">
        <div class="flex items-center gap-2">
            @if($p->choice1)
            <span class="inline-flex items-center justify-center gap-1 w-[60px] py-1 rounded-md text-xs font-bold border bg-red-100 text-red-700 border-red-200">
                <span class="font-normal opacity-75">1.</span>
                <span class="truncate">{{ $p->choice1->alias }}</span>
            </span>
            @endif
            @if($p->choice2)
            <span class="inline-flex items-center justify-center gap-1 w-[60px] py-1 rounded-md text-xs font-bold border bg-yellow-100 text-yellow-800 border-yellow-300">
                <span class="font-normal opacity-75">2.</span>
                <span class="truncate">{{ $p->choice2->alias }}</span>
            </span>
            @endif
            @if($p->choice3)
            <span class="inline-flex items-center justify-center gap-1 w-[60px] py-1 rounded-md text-xs font-bold border bg-gray-700 text-white border-gray-800">
                <span class="font-normal opacity-75">3.</span>
                <span class="truncate">{{ $p->choice3->alias }}</span>
            </span>
            @endif
        </div>
    </td>

    <td class="px-4 py-4 text-left">
        <div class="flex items-center gap-2">
            <button @click="openDetail({{ json_encode($alpineData) }})" title="Lihat Detail" class="flex items-center justify-center p-2 rounded-lg bg-primary/10 hover:bg-primary/20 text-primary transition-colors cursor-pointer">
                <i data-lucide="eye" class="size-4"></i>
            </button>

            @if(!$hasObservation)
            <button @click="openObservasi({{ json_encode($alpineData) }})" title="Mulai Observasi" class="flex items-center justify-center gap-1.5 p-2 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 transition-colors cursor-pointer text-xs font-semibold">
                <i data-lucide="clipboard-list" class="size-4"></i>
            </button>
            @else
            <button @click="openObservasi({{ json_encode($alpineData) }})" title="Edit Data Observasi" class="flex items-center justify-center p-2 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-700 transition-colors cursor-pointer">
                <i data-lucide="edit" class="size-4"></i>
            </button>
            @endif
        </div>
    </td>
</tr>