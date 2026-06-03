<div class="overflow-x-auto">
    <table class="w-full text-left text-sm">
        <thead class="uppercase tracking-wider border-b border-border text-secondary font-semibold text-xs">
            <tr>
                <th scope="col" class="px-4 py-3">Peserta</th>
                <th scope="col" class="px-4 py-3">Asal Sekolah & Kontak</th>
                <th scope="col" class="px-4 py-3">Pilihan Jurusan</th>
                <th scope="col" class="px-4 py-3">Waktu Daftar</th>
                <th scope="col" class="px-4 py-3">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-border">
            @forelse($applicants as $applicant)
            @php
            $fullName = $applicant->personalData->full_name ?? 'Tanpa Nama';
            $init = strtoupper(substr($fullName, 0, 2));
            $colors = [
            'linear-gradient(135deg,#FF1443,#FF6B6B)',
            'linear-gradient(135deg,#3B82F6,#93C5FD)',
            'linear-gradient(135deg,#F59E0B,#FCD34D)',
            'linear-gradient(135deg,#8B5CF6,#A78BFA)'
            ];
            $color = $colors[$loop->index % 4];
            // phone_number_encrypted → diakses via accessor 'phone_number' di model PersonalData
            // Jika model belum punya accessor, kolom ini tidak akan muncul
            $phone = $applicant->personalData?->phone_number ?? null;
            $schoolCity = $applicant->personalData->previous_school_city ?? null;
            $status = $applicant->verification_status;
            $badgeClass = match($status) {
            'verified' => 'bg-success/10 text-success-dark border-success/20',
            'rejected' => 'bg-error/10 text-error-dark border-error/20',
            default => 'bg-warning/10 text-warning-dark border-warning/20',
            };
            $statusText = match($status) {
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak',
            default => 'Menunggu',
            };
            @endphp
            <tr class="border-b border-border hover:bg-muted/50 transition-colors">

                {{-- Nama & Avatar --}}
                <td class="px-4 py-4">
                    <div class="flex items-center gap-3">
                        @if($applicant->personalData && $applicant->personalData->photo)
                        <img src="{{ asset('storage/' . $applicant->personalData->photo) }}"
                            alt="Foto"
                            class="h-10 w-10 rounded-full object-cover shrink-0">
                        @else
                        <div class="h-10 w-10 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0"
                            style="background: {{ $color }}">
                            {{ $init }}
                        </div>
                        @endif
                        <div>
                            <div class="font-semibold text-foreground text-sm uppercase">{{ $fullName }}</div>
                            <div class="text-xs text-secondary font-mono">{{ $applicant->registration_number ?? '-' }}</div>
                        </div>
                    </div>
                </td>

                {{-- Asal Sekolah & Kontak --}}
                <td class="px-4 py-4">
                    <div class="text-sm font-medium text-foreground uppercase leading-snug">
                        {{ $applicant->personalData->previous_school ?? '-' }}
                    </div>
                    @if($schoolCity)
                    <div class="mt-0.5 inline-flex items-center gap-1 text-xs text-secondary">
                        <i data-lucide="map-pin" class="size-3"></i>
                        <span>{{ $schoolCity }}</span>
                    </div>
                    @elseif($phone)
                    <a href="https://wa.me/62{{ ltrim($phone, '0') }}" target="_blank"
                        class="mt-1 inline-flex items-center gap-x-1.5 text-xs text-secondary hover:text-green-600 hover:underline transition-colors">
                        <i data-lucide="phone" class="size-3"></i>
                        <span>{{ $phone }}</span>
                    </a>
                    @endif
                </td>

                {{-- Pilihan Jurusan --}}
                <td class="px-4 py-4">
                    <div class="flex items-center gap-1.5 flex-wrap">
                        @if($applicant->choice1Concentration)
                        <span class="inline-flex items-center justify-center gap-1 w-[62px] py-1 rounded-md text-xs font-bold border bg-red-100 text-red-700 border-red-200">
                            <span class="font-normal opacity-75">1.</span>
                            <span class="truncate">{{ $applicant->choice1Concentration->alias ?? $applicant->choice1Concentration->name }}</span>
                        </span>
                        @endif
                        @if(isset($applicant->choice2Concentration) && $applicant->choice2Concentration)
                        <span class="inline-flex items-center justify-center gap-1 w-[62px] py-1 rounded-md text-xs font-bold border bg-yellow-100 text-yellow-800 border-yellow-300">
                            <span class="font-normal opacity-75">2.</span>
                            <span class="truncate">{{ $applicant->choice2Concentration->alias ?? $applicant->choice2Concentration->name }}</span>
                        </span>
                        @endif
                        @if(isset($applicant->choice3Concentration) && $applicant->choice3Concentration)
                        <span class="inline-flex items-center justify-center gap-1 w-[62px] py-1 rounded-md text-xs font-bold border bg-gray-700 text-white border-gray-800">
                            <span class="font-normal opacity-75">3.</span>
                            <span class="truncate">{{ $applicant->choice3Concentration->alias ?? $applicant->choice3Concentration->name }}</span>
                        </span>
                        @endif
                        @if(!$applicant->choice1Concentration)
                        <span class="text-secondary text-xs">-</span>
                        @endif
                    </div>
                </td>

                {{-- Waktu Daftar --}}
                <td class="px-4 py-4 text-secondary text-sm">
                    {{ $applicant->submitted_at ? \Carbon\Carbon::parse($applicant->submitted_at)->translatedFormat('d M Y, H:i') : '-' }}
                </td>

                {{-- Status --}}
                <td class="px-4 py-4">
                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold border {{ $badgeClass }}">
                        {{ $statusText }}
                    </span>
                </td>


            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-4 py-10 text-center">
                    <div class="flex flex-col items-center justify-center gap-3">
                        <div class="size-12 rounded-full bg-slate-50 flex items-center justify-center">
                            <i data-lucide="file-search" class="size-6 text-secondary/50"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-foreground">Tidak ada pendaftar ditemukan</p>
                            <p class="text-xs text-secondary mt-0.5">Coba ubah kata kunci atau filter pencarian.</p>
                        </div>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination HTMX --}}
@if($applicants->hasPages())
<div class="mt-4 px-4 flex items-center justify-between">
    <p class="text-xs text-secondary">
        Menampilkan {{ $applicants->firstItem() }}–{{ $applicants->lastItem() }} dari {{ $applicants->total() }} pendaftar
    </p>
    <div class="flex gap-2">
        @if(!$applicants->onFirstPage())
        <button hx-get="{{ $applicants->previousPageUrl() }}"
            hx-target="#applicant-table-wrapper"
            hx-include="#searchInput, #filterStatus, #filterConcentration"
            class="px-3 py-1.5 rounded-lg border border-border text-xs font-semibold text-secondary hover:bg-slate-50 transition-colors">
            ← Sebelumnya
        </button>
        @endif
        @if($applicants->hasMorePages())
        <button hx-get="{{ $applicants->nextPageUrl() }}"
            hx-target="#applicant-table-wrapper"
            hx-include="#searchInput, #filterStatus, #filterConcentration"
            class="px-3 py-1.5 rounded-lg border border-border text-xs font-semibold text-secondary hover:bg-slate-50 transition-colors">
            Selanjutnya →
        </button>
        @endif
    </div>
</div>
@endif

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>