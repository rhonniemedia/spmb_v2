<div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">

    {{-- Header --}}
    <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-file-circle-check text-emerald-500 text-sm"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Status Dokumen & Berkas</h3>
                <p class="text-xs text-gray-400 mt-0.5">Pastikan semua persyaratan terpenuhi sebelum batas waktu</p>
            </div>
        </div>

        {{-- Progress --}}
        <div class="flex items-center gap-2.5 shrink-0">
            <div class="w-24 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full transition-all" style="width: {{ $progressPercent }}%"></div>
            </div>
            <span class="text-xs font-medium text-emerald-600">{{ $verifiedCount }}/{{ $totalRequirements }}</span>
        </div>
    </div>

    {{-- Tab --}}
    <div class="px-5 pt-4 flex gap-1.5" id="req-tabs">
        <button onclick="switchTab('dokumen')" id="tab-dokumen"
            class="text-xs font-medium px-3 py-1.5 rounded-lg bg-gray-900 text-white transition-all">
            Dokumen
        </button>
        <button onclick="switchTab('akademik')" id="tab-akademik"
            class="text-xs font-medium px-3 py-1.5 rounded-lg bg-gray-100 text-gray-500 transition-all">
            Akademik
        </button>
        <button onclick="switchTab('administratif')" id="tab-administratif"
            class="text-xs font-medium px-3 py-1.5 rounded-lg bg-gray-100 text-gray-500 transition-all">
            Administratif
        </button>
    </div>

    {{-- Panel: Dokumen --}}
    <div id="panel-dokumen" class="px-5 py-4 space-y-2">
        @forelse($requirements as $req)
        @php
        $docStatus = $registration ? $registration->documents->where('requirement_id', $req->id)->first() : null;
        $status = $docStatus ? $docStatus->verification_status : 'pending';

        $themes = [
        'verified' => [
        'bg' => 'bg-green-50 border-green-100',
        'text' => 'text-green-600',
        'badge' => 'bg-white text-green-600 border-green-200',
        'icon' => 'fa-circle-check',
        'label' => 'Terverifikasi',
        ],
        'pending' => [
        'bg' => 'bg-amber-50 border-amber-100',
        'text' => 'text-amber-600',
        'badge' => 'bg-white text-amber-600 border-amber-200',
        'icon' => 'fa-clock',
        'label' => 'Menunggu verifikasi',
        ],
        'rejected' => [
        'bg' => 'bg-red-50 border-red-100',
        'text' => 'text-red-500',
        'badge' => 'bg-white text-red-500 border-red-200',
        'icon' => 'fa-circle-xmark',
        'label' => 'Ditolak',
        ],
        ];
        $theme = $themes[$status] ?? $themes['pending'];
        @endphp

        <div class="flex items-center gap-3 p-3 rounded-xl border {{ $theme['bg'] }}">
            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shrink-0">
                <i class="{{ $req->icon ?? 'fa-solid fa-file-lines' }} {{ $theme['text'] }} text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-medium text-gray-800">{{ $req->name }}</p>
                <p class="text-[11px] text-gray-400 mt-0.5 truncate">
                    {{ $status === 'rejected' && $docStatus?->verification_notes ? $docStatus->verification_notes : $req->description }}
                </p>
            </div>
            <span class="text-[11px] font-medium px-2 py-1 rounded-lg border {{ $theme['badge'] }} shrink-0">
                <i class="fa-solid {{ $theme['icon'] }} text-[10px] mr-1"></i>{{ $theme['label'] }}
            </span>
        </div>
        @empty
        <p class="text-xs text-gray-400 text-center py-6">Belum ada persyaratan dokumen yang dikonfigurasi.</p>
        @endforelse
    </div>

    {{-- Panel: Akademik --}}
    <div id="panel-akademik" class="px-5 py-4 space-y-2 hidden">

        @php
        $hasRapor = $registration && $registration->report_average;
        $hasTka = $registration && $registration->tka_average;
        @endphp

        <div class="flex items-center gap-3 p-3 rounded-xl border {{ $hasRapor ? 'bg-green-50 border-green-100' : 'bg-amber-50 border-amber-100' }}">
            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shrink-0">
                <i class="fa-solid fa-chart-simple {{ $hasRapor ? 'text-green-500' : 'text-amber-500' }} text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-medium text-gray-800">Nilai rapor semester 1–5</p>
                <p class="text-[11px] text-gray-400 mt-0.5">
                    @if($hasRapor)
                    Rata-rata: <span class="font-semibold text-green-600">{{ number_format($registration->report_average, 2) }}</span>
                    @else
                    Belum diverifikasi oleh panitia
                    @endif
                </p>
            </div>
            <i class="fa-solid {{ $hasRapor ? 'fa-circle-check text-green-500' : 'fa-clock text-amber-500' }} text-base shrink-0"></i>
        </div>

        <div class="flex items-center gap-3 p-3 rounded-xl border {{ $hasTka ? 'bg-green-50 border-green-100' : 'bg-amber-50 border-amber-100' }}">
            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shrink-0">
                <i class="fa-solid fa-pen-to-square {{ $hasTka ? 'text-green-500' : 'text-amber-500' }} text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-medium text-gray-800">Nilai Tes Kemampuan Akademik (TKA)</p>
                <p class="text-[11px] text-gray-400 mt-0.5">
                    @if($hasTka)
                    Rata-rata: <span class="font-semibold text-green-600">{{ number_format($registration->tka_average, 2) }}</span>
                    @else
                    Belum mengikuti ujian atau nilai belum tersedia
                    @endif
                </p>
            </div>
            <i class="fa-solid {{ $hasTka ? 'fa-circle-check text-green-500' : 'fa-clock text-amber-500' }} text-base shrink-0"></i>
        </div>

    </div>

    {{-- Panel: Administratif --}}
    <div id="panel-administratif" class="px-5 py-4 space-y-2 hidden">

        <div class="flex items-center gap-3 p-3 rounded-xl border {{ $isPersonalDataComplete ? 'bg-green-50 border-green-100' : 'bg-red-50 border-red-100' }}">
            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shrink-0">
                <i class="fa-solid fa-user-check {{ $isPersonalDataComplete ? 'text-green-500' : 'text-red-400' }} text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-medium text-gray-800">Biodata diri</p>
                <p class="text-[11px] mt-0.5 {{ $isPersonalDataComplete ? 'text-gray-400' : 'text-red-400' }}">
                    {{ $isPersonalDataComplete ? 'Semua kolom wajib sudah terisi' : 'Profil masih draft atau belum lengkap' }}
                </p>
            </div>
            @if($isPersonalDataComplete)
            <i class="fa-solid fa-circle-check text-green-500 text-base shrink-0"></i>
            @else
            <a href="{{ route('biodata') }}" class="text-[11px] font-semibold text-white bg-red-500 hover:bg-red-600 px-2.5 py-1 rounded-lg transition-colors shrink-0">Lengkapi</a>
            @endif
        </div>

        <div class="flex items-center gap-3 p-3 rounded-xl border {{ $isParentDataComplete ? 'bg-green-50 border-green-100' : 'bg-red-50 border-red-100' }}">
            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shrink-0">
                <i class="fa-solid fa-users {{ $isParentDataComplete ? 'text-green-500' : 'text-red-400' }} text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-medium text-gray-800">Data orang tua / wali</p>
                <p class="text-[11px] mt-0.5 {{ $isParentDataComplete ? 'text-gray-400' : 'text-red-400' }}">
                    @if($isParentDataComplete)
                    Data ayah & ibu sudah direkam ({{ $parentDataCount }} data)
                    @else
                    Baru {{ $parentDataCount }} data. Harap lengkapi data ayah & ibu.
                    @endif
                </p>
            </div>
            @if($isParentDataComplete)
            <i class="fa-solid fa-circle-check text-green-500 text-base shrink-0"></i>
            @else
            <a href="{{ route('biodata') }}" class="text-[11px] font-semibold text-white bg-red-500 hover:bg-red-600 px-2.5 py-1 rounded-lg transition-colors shrink-0">Lengkapi</a>
            @endif
        </div>

    </div>

    {{-- Catatan verifikasi --}}
    <div class="mx-5 mb-5 p-3 rounded-xl bg-gray-50 border border-gray-100 flex items-center gap-2">
        <i class="fa-solid fa-circle-info text-gray-300 text-sm shrink-0"></i>
        <p class="text-[11px] text-gray-400">
            Verifikasi berkas fisik ke Panitia SPMB mulai tanggal <span class="font-medium text-gray-600">{{ $verificationDateText }}</span>.
        </p>
    </div>

</div>