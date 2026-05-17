{{-- _summary_content.blade.php --}}
{{-- Di-load via HTMX ke #pendaftaran-summary saat step konfirmasi --}}

{{-- ── NILAI RAPOR ── --}}
<div class="border border-gray-200 rounded-2xl overflow-hidden">
    <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-table-list text-[#FF1443] text-[12px]"></i>
            <span class="text-[13px] font-black text-[#080C1A]">Nilai Rapor</span>
        </div>
        @if($registration?->report_average)
        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 border border-red-100 rounded-full text-[11px] font-black text-[#FF1443]">
            <i class="fa-solid fa-calculator text-[9px]"></i>
            Rata-rata: {{ number_format($registration->report_average, 2) }}
        </span>
        @endif
    </div>
    <div class="divide-y divide-gray-100">
        @php
        $rapor = [
        'Semester 1 (Kelas VII / Ganjil)' => $registration?->report_sem_1,
        'Semester 2 (Kelas VII / Genap)' => $registration?->report_sem_2,
        'Semester 3 (Kelas VIII / Ganjil)'=> $registration?->report_sem_3,
        'Semester 4 (Kelas VIII / Genap)' => $registration?->report_sem_4,
        'Semester 5 (Kelas IX / Ganjil)' => $registration?->report_sem_5,
        ];
        @endphp
        @foreach($rapor as $label => $value)
        <div class="flex items-center justify-between px-5 py-3 hover:bg-gray-50/60 transition-colors">
            <span class="text-[13px] text-[#6A7686] font-medium">{{ $label }}</span>
            <span class="text-[13px] font-black text-[#080C1A]">
                {{ $value ? number_format($value, 2) : '—' }}
            </span>
        </div>
        @endforeach
    </div>
</div>

{{-- ── NILAI TKA ── --}}
<div class="border border-gray-200 rounded-2xl overflow-hidden">
    <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-brain text-indigo-500 text-[12px]"></i>
            <span class="text-[13px] font-black text-[#080C1A]">Nilai TKA</span>
        </div>
        @if($registration?->tka_average)
        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-50 border border-indigo-100 rounded-full text-[11px] font-black text-indigo-600">
            <i class="fa-solid fa-calculator text-[9px]"></i>
            Rata-rata: {{ number_format($registration->tka_average, 2) }}
        </span>
        @endif
    </div>
    <div class="divide-y divide-gray-100">
        @php
        $tka = [
        'Matematika' => $registration?->tka_math,
        'Bahasa Indonesia' => $registration?->tka_indonesian,
        ];
        @endphp
        @foreach($tka as $label => $value)
        <div class="flex items-center justify-between px-5 py-3 hover:bg-gray-50/60 transition-colors">
            <span class="text-[13px] text-[#6A7686] font-medium">{{ $label }}</span>
            <span class="text-[13px] font-black text-[#080C1A]">
                {{ $value ? number_format($value, 2) : '—' }}
            </span>
        </div>
        @endforeach
    </div>
</div>