{{--
    PARTIAL: _parent-fields.blade.php
    Lokasi: resources/views/partials/resume/_parent-fields.blade.php

    Props yang dibutuhkan:
        $p     — instance dari ParentData model (sudah didekripsi)
        $label — string, contoh: 'Ayah', 'Ibu', 'Wali'
--}}

<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

    {{-- Nama --}}
    <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB] sm:col-span-2">
        <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Nama {{ $label }}</div>
        <div class="text-[13px] font-bold text-[#080C1A]">{{ $p->name ?? '—' }}</div>
    </div>

    {{-- Status Kehidupan --}}
    @php
    $isDeceased = ($p->living_status ?? '') === 'deceased';
    $statusCellClass = $isDeceased ? 'bg-gray-50 border-[#E5E7EB]' : 'bg-[#DCFCE7] border-[rgba(48,178,45,0.18)]';
    $statusValueClass = $isDeceased ? 'text-[#B0B8C5] italic font-medium' : 'text-[#166534]';
    @endphp
    <div class="p-3 rounded-[16px] border transition-all {{ $statusCellClass }}">
        <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Status Kehidupan</div>
        <div class="text-[13px] font-bold flex items-center gap-1.5 {{ $statusValueClass }}">
            @if(($p->living_status ?? '') === 'alive')
            <i class="fa-solid fa-circle text-[8px]"></i> Masih Hidup
            @elseif(($p->living_status ?? '') === 'deceased')
            <i class="fa-solid fa-circle-minus text-[11px]"></i> Almarhum / Almarhumah
            @else
            —
            @endif
        </div>
    </div>

    {{-- NIK --}}
    <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
        <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">NIK {{ $label }}</div>
        <div class="text-[13px] font-bold font-mono tracking-wide text-[#080C1A]">
            @if(isset($p->nik) && $p->nik)
            ••••••••••{{ substr($p->nik ?? '', -4) }}
            @else
            <span class="text-[#b0b8c4] font-normal italic">Tidak diisi</span>
            @endif
        </div>
    </div>

    {{-- Pendidikan Terakhir --}}
    <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
        <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Pendidikan Terakhir</div>
        <div class="text-[13px] font-bold text-[#080C1A]">{{ $p->education ?? '—' }}</div>
    </div>

    {{-- Pekerjaan --}}
    <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
        <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Pekerjaan</div>
        <div class="text-[13px] font-bold text-[#080C1A]">{{ $p->occupation ?? '—' }}</div>
    </div>

    {{-- Rentang Penghasilan --}}
    <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
        <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Rentang Penghasilan</div>
        <div class="text-[13px] font-bold text-[#080C1A]">{{ $p->income_range ?? '—' }}</div>
    </div>

    {{-- Nomor Telepon --}}
    <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB]">
        <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Nomor Telepon</div>
        <div class="text-[13px] font-bold font-mono tracking-wide text-[#080C1A]">
            @if($p->phone_number ?? null)
            0{{ $p->phone_number }}
            @else
            <span class="text-[#b0b8c4] font-normal italic">Tidak diisi</span>
            @endif
        </div>
    </div>

    {{-- Alamat --}}
    @if($p->address ?? null)
    <div class="p-3 rounded-[16px] bg-gray-50 border border-[#E5E7EB] sm:col-span-2">
        <div class="text-[12px] font-bold uppercase tracking-[0.06em] text-[#6A7686] mb-1">Alamat {{ $label }}</div>
        <div class="text-[13px] font-bold text-[#080C1A] leading-relaxed">{{ $p->address }}</div>
    </div>
    @endif

</div>