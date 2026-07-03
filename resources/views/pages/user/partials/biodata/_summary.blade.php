<div class="space-y-4 sm:space-y-5">

    {{-- Identitas Diri --}}
    <div class="bg-white border-2 border-dashed border-gray-200 rounded-2xl sm:rounded-3xl overflow-hidden shadow-sm">
        <div class="px-4 sm:px-6 py-4 sm:py-5 flex justify-between items-start">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="bg-[#4A5568] w-9 h-9 sm:w-10 sm:h-10 aspect-square rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-address-card text-white text-base sm:text-lg"></i>
                </div>
                <div>
                    <h3 class="text-[#2D3748] font-bold text-sm leading-tight">Identitas Diri</h3>
                    <p class="text-[#A0AEC0] text-[11px] sm:text-[13px] mt-0.5">Data inti dan profil dasar siswa</p>
                </div>
            </div>
            <span class="text-[11px] sm:text-[13px] text-green-600 font-bold flex items-center gap-1 mt-1 sm:mt-0">
                <i class="fa-solid fa-circle-check"></i> Lengkap
            </span>
        </div>
        <div class="border-b border-gray-200 mx-4 sm:mx-6"></div>
        <div class="px-4 sm:px-6 py-2">

            <div class="flex flex-col sm:flex-row py-3 sm:py-4 border-b border-gray-200 items-start gap-1 sm:gap-0">
                <div class="w-full sm:w-2/5 text-[#718096] text-[11px] sm:text-[13px] font-semibold sm:font-medium uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">Nama Lengkap / Panggilan</div>
                <div class="w-full sm:w-3/5 text-[#2D3748] text-[13px] sm:text-[14px] font-bold sm:font-semibold">{{ $personalData->full_name }} ({{ $personalData->nick_name }})</div>
            </div>

            <div class="flex flex-col sm:flex-row py-3 sm:py-4 border-b border-gray-200 items-start gap-1 sm:gap-0">
                <div class="w-full sm:w-2/5 text-[#718096] text-[11px] sm:text-[13px] font-semibold sm:font-medium uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">NIK / NISN</div>
                <div class="w-full sm:w-3/5 text-[#2D3748] text-[13px] sm:text-[14px] font-bold sm:font-semibold">{{ $personalData->nik }} / {{ $personalData->nisn }}</div>
            </div>

            <div class="flex flex-col sm:flex-row py-3 sm:py-4 border-b border-gray-200 items-start gap-1 sm:gap-0">
                <div class="w-full sm:w-2/5 text-[#718096] text-[11px] sm:text-[13px] font-semibold sm:font-medium uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">Tempat & Tanggal Lahir</div>
                <div class="w-full sm:w-3/5 space-y-0.5 sm:space-y-1">
                    <div class="text-[#2D3748] text-[13px] sm:text-[14px] font-bold">{{ $personalData->pob }}</div>
                    <div class="text-[#718096] text-[12px]">Tgl. {{ \Carbon\Carbon::parse($personalData->dob)->translatedFormat('d F Y') }}</div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row py-3 sm:py-4 border-b border-gray-200 items-start gap-1 sm:gap-0">
                <div class="w-full sm:w-2/5 text-[#718096] text-[11px] sm:text-[13px] font-semibold sm:font-medium uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">Jenis Kelamin / Agama</div>
                <div class="w-full sm:w-3/5 text-[#2D3748] text-[13px] sm:text-[14px] font-bold sm:font-semibold">{{ $personalData->gender == 'L' ? 'Laki-laki' : 'Perempuan' }} / {{ $personalData->religion }}</div>
            </div>

            <div class="flex flex-col sm:flex-row py-3 sm:py-4 items-start gap-1 sm:gap-0">
                <div class="w-full sm:w-2/5 text-[#718096] text-[11px] sm:text-[13px] font-semibold sm:font-medium uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">Anak Ke / Sdr. Kandung</div>
                <div class="w-full sm:w-3/5 space-y-0.5 sm:space-y-1">
                    <div class="text-[#2D3748] text-[13px] sm:text-[14px] font-bold">Anak ke-{{ $personalData->child_order }}</div>
                    <div class="text-[#718096] text-[12px]">Jumlah Saudara. {{ $personalData->number_of_siblings }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kontak & Domisili --}}
    <div class="bg-white border-2 border-dashed border-gray-200 rounded-2xl sm:rounded-3xl overflow-hidden shadow-sm">
        <div class="px-4 sm:px-6 py-4 sm:py-5 flex justify-between items-start">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="bg-[#4A5568] w-9 h-9 sm:w-10 sm:h-10 aspect-square rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-map-location-dot text-white text-base sm:text-lg"></i>
                </div>
                <div>
                    <h3 class="text-[#2D3748] font-bold text-sm leading-tight">Kontak & Domisili</h3>
                    <p class="text-[#A0AEC0] text-[11px] sm:text-[13px] mt-0.5">Informasi tempat tinggal dan komunikasi</p>
                </div>
            </div>
            <span class="text-[11px] sm:text-[13px] text-green-600 font-bold flex items-center gap-1 mt-1 sm:mt-0">
                <i class="fa-solid fa-circle-check"></i> Lengkap
            </span>
        </div>
        <div class="border-b border-gray-200 mx-4 sm:mx-6"></div>
        <div class="px-4 sm:px-6 py-2">

            <div class="flex flex-col sm:flex-row py-3 sm:py-4 border-b border-gray-200 items-start gap-1 sm:gap-0">
                <div class="w-full sm:w-2/5 text-[#718096] text-[11px] sm:text-[13px] font-semibold sm:font-medium uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">Email / WhatsApp</div>
                <div class="w-full sm:w-3/5 space-y-0.5 sm:space-y-1">
                    <div class="text-[#2D3748] text-[13px] sm:text-[14px] font-bold break-all">{{ $personalData->email }}</div>
                    <div class="text-[#718096] text-[12px]">Telp/WA. {{ $personalData->phone_number }}</div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row py-3 sm:py-4 border-b border-gray-200 items-start gap-1 sm:gap-0">
                <div class="w-full sm:w-2/5 text-[#718096] text-[11px] sm:text-[13px] font-semibold sm:font-medium uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">Alamat Lengkap</div>
                <div class="w-full sm:w-3/5 space-y-0.5 sm:space-y-1">
                    <div class="text-[#2D3748] text-[13px] sm:text-[14px] font-bold">{{ $personalData->address }} (RT {{ $personalData->rt }} / RW {{ $personalData->rw }})</div>
                    <div class="text-[#718096] text-[12px]">Desa/Kel. {{ $personalData->village }}, Kec. {{ $personalData->district }}</div>
                    <div class="text-[#718096] text-[12px]">{{ $personalData->regency }}, {{ $personalData->province }} - {{ $personalData->postal_code }}</div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row py-3 sm:py-4 items-start gap-1 sm:gap-0">
                <div class="w-full sm:w-2/5 text-[#718096] text-[11px] sm:text-[13px] font-semibold sm:font-medium uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">Transportasi / Jarak</div>
                <div class="w-full sm:w-3/5 space-y-0.5 sm:space-y-1">
                    <div class="text-[#2D3748] text-[13px] sm:text-[14px] font-bold">{{ ucfirst($personalData->transportation) }}</div>
                    <div class="text-[#718096] text-[12px]">Jarak. {{ $personalData->distance_to_school }} ke sekolah</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Data Orang Tua --}}
    <div class="bg-white border-2 border-dashed border-gray-200 rounded-2xl sm:rounded-3xl overflow-hidden shadow-sm">
        <div class="px-4 sm:px-6 py-4 sm:py-5 flex justify-between items-start">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="bg-[#4A5568] w-9 h-9 sm:w-10 sm:h-10 aspect-square rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-users text-white text-base sm:text-lg"></i>
                </div>
                <div>
                    <h3 class="text-[#2D3748] font-bold text-sm leading-tight">Data Orang Tua</h3>
                    <p class="text-[#A0AEC0] text-[11px] sm:text-[13px] mt-0.5">Informasi wali dan keluarga inti</p>
                </div>
            </div>
            <span class="text-[11px] sm:text-[13px] text-green-600 font-bold flex items-center gap-1 mt-1 sm:mt-0">
                <i class="fa-solid fa-circle-check"></i> Lengkap
            </span>
        </div>
        <div class="border-b border-gray-200 mx-4 sm:mx-6"></div>
        <div class="px-4 sm:px-6 py-2">
            @php
            $ayah = $personalData->parents->where('relationship', 'father')->first();
            $ibu = $personalData->parents->where('relationship', 'mother')->first();
            $wali = $personalData->parents->where('relationship', 'guardian')->first();
            @endphp

            <div class="flex flex-col sm:flex-row py-3 sm:py-4 border-b border-gray-200 items-start gap-1 sm:gap-0">
                <div class="w-full sm:w-2/5 text-[#718096] text-[11px] sm:text-[13px] font-semibold sm:font-medium uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">Data Ayah</div>
                <div class="w-full sm:w-3/5 space-y-0.5 sm:space-y-1.5">
                    <div class="text-[#2D3748] text-[13px] sm:text-[14px] font-bold">
                        {{ $ayah->name ?? '-' }}
                        <span class="text-[12px] font-medium {{ ($ayah->living_status ?? '') == 'alive' ? 'text-green-600' : 'text-red-500' }}">
                            ({{ ($ayah->living_status ?? '') == 'alive' ? 'Masih Hidup' : 'Meninggal' }})
                        </span>
                    </div>

                    @if(($ayah->living_status ?? '') == 'alive')
                    <div class="text-[#718096] text-[12px]">NIK. {{ $ayah->nik ?? '-' }} | Telp. {{ $ayah->phone_number ?? '-' }}</div>
                    <div class="text-[#718096] text-[12px]">Pekerjaan. {{ $ayah->occupation ?? '-' }}</div>
                    <div class="text-[#718096] text-[12px]">Penghasilan. {{ $ayah->income_range ?? '-' }}</div>
                    <div class="text-[#718096] text-[12px]">Pendidikan. {{ $ayah->education ?? '-' }}</div>
                    <div class="text-[#718096] text-[12px]">Alamat. {{ $ayah->address ?? '-' }}</div>
                    @endif
                </div>
            </div>

            <div class="flex flex-col sm:flex-row py-3 sm:py-4 {{ $wali ? 'border-b border-gray-200' : '' }} items-start gap-1 sm:gap-0">
                <div class="w-full sm:w-2/5 text-[#718096] text-[11px] sm:text-[13px] font-semibold sm:font-medium uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">Data Ibu</div>
                <div class="w-full sm:w-3/5 space-y-0.5 sm:space-y-1.5">
                    <div class="text-[#2D3748] text-[13px] sm:text-[14px] font-bold">
                        {{ $ibu->name ?? '-' }}
                        <span class="text-[12px] font-medium {{ ($ibu->living_status ?? '') == 'alive' ? 'text-green-600' : 'text-red-500' }}">
                            ({{ ($ibu->living_status ?? '') == 'alive' ? 'Masih Hidup' : 'Meninggal' }})
                        </span>
                    </div>

                    @if(($ibu->living_status ?? '') == 'alive')
                    <div class="text-[#718096] text-[12px]">NIK. {{ $ibu->nik ?? '-' }} | Telp. {{ $ibu->phone_number ?? '-' }}</div>
                    <div class="text-[#718096] text-[12px]">Pekerjaan. {{ $ibu->occupation ?? '-' }}</div>
                    <div class="text-[#718096] text-[12px]">Penghasilan. {{ $ibu->income_range ?? '-' }}</div>
                    <div class="text-[#718096] text-[12px]">Pendidikan. {{ $ibu->education ?? '-' }}</div>
                    <div class="text-[#718096] text-[12px]">Alamat. {{ $ibu->address ?? '-' }}</div>
                    @endif
                </div>
            </div>

            @if($wali)
            <div class="flex flex-col sm:flex-row py-3 sm:py-4 items-start gap-1 sm:gap-0">
                <div class="w-full sm:w-2/5 text-[#718096] text-[11px] sm:text-[13px] font-semibold sm:font-medium uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">Data Wali</div>
                <div class="w-full sm:w-3/5 space-y-0.5 sm:space-y-1.5">
                    <div class="text-[#2D3748] text-[13px] sm:text-[14px] font-bold">
                        {{ $wali->name ?? '-' }}
                    </div>
                    <div class="text-[#718096] text-[12px]">NIK. {{ $wali->nik ?? '-' }} | Telp. {{ $wali->phone_number ?? '-' }}</div>
                    <div class="text-[#718096] text-[12px]">Pekerjaan. {{ $wali->occupation ?? '-' }}</div>
                    <div class="text-[#718096] text-[12px]">Penghasilan. {{ $wali->income_range ?? '-' }}</div>
                    <div class="text-[#718096] text-[12px]">Pendidikan. {{ $wali->education ?? '-' }}</div>
                    <div class="text-[#718096] text-[12px]">Alamat. {{ $wali->address ?? '-' }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Pendidikan Asal --}}
    <div class="bg-white border-2 border-dashed border-gray-200 rounded-2xl sm:rounded-3xl overflow-hidden shadow-sm">
        <div class="px-4 sm:px-6 py-4 sm:py-5 flex justify-between items-start">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="bg-[#4A5568] w-9 h-9 sm:w-10 sm:h-10 aspect-square rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-graduation-cap text-white text-base sm:text-lg"></i>
                </div>
                <div>
                    <h3 class="text-[#2D3748] font-bold text-sm leading-tight">Pendidikan Asal</h3>
                    <p class="text-[#A0AEC0] text-[11px] sm:text-[13px] mt-0.5">Riwayat sekolah jenjang sebelumnya</p>
                </div>
            </div>
            <span class="text-[11px] sm:text-[13px] text-green-600 font-bold flex items-center gap-1 mt-1 sm:mt-0">
                <i class="fa-solid fa-circle-check"></i> Lengkap
            </span>
        </div>
        <div class="border-b border-gray-200 mx-4 sm:mx-6"></div>
        <div class="px-4 sm:px-6 py-2">

            <div class="flex flex-col sm:flex-row py-3 sm:py-4 border-b border-gray-200 items-start gap-1 sm:gap-0">
                <div class="w-full sm:w-2/5 text-[#718096] text-[11px] sm:text-[13px] font-semibold sm:font-medium uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">Sekolah Asal</div>
                <div class="w-full sm:w-3/5 space-y-0.5 sm:space-y-1">
                    <div class="text-[#2D3748] text-[13px] sm:text-[14px] font-bold">{{ $personalData->previous_school }}</div>
                    <div class="text-[#718096] text-[12px]">NPSN. {{ $personalData->previous_school_npsn ?? '-' }}</div>
                    <div class="text-[#718096] text-[12px]">Kab. {{ $personalData->previous_school_city ?? '-' }} | Provinsi. {{ $personalData->previous_school_province ?? '-' }}</div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row py-3 sm:py-4 border-b border-gray-200 items-start gap-1 sm:gap-0">
                <div class="w-full sm:w-2/5 text-[#718096] text-[11px] sm:text-[13px] font-semibold sm:font-medium uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">Tahun Lulus</div>
                <div class="w-full sm:w-3/5 text-[#2D3748] text-[13px] sm:text-[14px] font-bold sm:font-semibold">{{ $personalData->graduation_year }}</div>
            </div>

            <div class="flex flex-col sm:flex-row py-3 sm:py-4 items-start gap-1 sm:gap-0">
                <div class="w-full sm:w-2/5 text-[#718096] text-[11px] sm:text-[13px] font-semibold sm:font-medium uppercase sm:normal-case tracking-wider sm:tracking-normal pt-0.5">No. Ijazah/SKL</div>
                <div class="w-full sm:w-3/5 text-[#2D3748] text-[13px] sm:text-[14px] font-bold sm:font-semibold">{{ $personalData->graduation_certificate_number ?? '-' }}</div>
            </div>
        </div>
    </div>

    {{-- Pas Foto --}}
    <div class="bg-white border-2 border-dashed border-gray-200 rounded-2xl sm:rounded-3xl overflow-hidden shadow-sm">
        <div class="px-4 sm:px-6 py-4 sm:py-5 flex justify-between items-start">
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="bg-[#4A5568] w-9 h-9 sm:w-10 sm:h-10 aspect-square rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-image text-white text-base sm:text-lg"></i>
                </div>
                <div>
                    <h3 class="text-[#2D3748] font-bold text-sm leading-tight">Pas Foto</h3>
                    <p class="text-[#A0AEC0] text-[11px] sm:text-[13px] mt-0.5">Dokumen visual identitas siswa</p>
                </div>
            </div>
            <span class="text-[11px] sm:text-[13px] text-green-600 font-bold flex items-center gap-1 mt-1 sm:mt-0">
                <i class="fa-solid fa-circle-check"></i> Lengkap
            </span>
        </div>
        <div class="border-b border-gray-200 mx-4 sm:mx-6"></div>
        <div class="px-4 sm:px-6 py-2">
            <div class="flex py-3 sm:py-4 items-center gap-3 sm:gap-4">
                <div class="flex-shrink-0 w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-green-50 border border-green-100 flex items-center justify-center overflow-hidden aspect-square">
                    @if($personalData->photo)
                    <img src="{{ \Storage::url($personalData->photo) }}" class="w-full h-full object-cover">
                    @else
                    <i class="fa-solid fa-file-image text-green-600 text-lg sm:text-xl"></i>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[#718096] text-[11px] sm:text-[13px] font-semibold sm:font-medium mb-0.5 uppercase sm:normal-case tracking-wider sm:tracking-normal">File Terunggah</div>
                    <div class="text-[#2D3748] text-[13px] sm:text-[14px] font-bold sm:font-semibold truncate">
                        {{ $personalData->photo ? basename($personalData->photo) : 'Tidak ada file' }}
                    </div>
                    <div class="text-[#A0AEC0] text-[11px] sm:text-[12px] mt-0.5">Status: Terenkripsi & Terverifikasi</div>
                </div>
            </div>
        </div>
    </div>
</div>