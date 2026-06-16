@extends('layouts.admin')

@section('title', 'Peserta Ditolak')
@section('page_title', 'Peserta Ditolak')
@section('page_subtitle', 'Daftar peserta yang tidak lolos penjenjangan')

@section('content')

{{-- Tambahkan ID container untuk target HTMX --}}
<div id="rejected-container" class="flex-1 overflow-y-auto p-5 md:p-8 bg-white">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('admin.penjenjangan.index') }}"
                class="inline-flex items-center gap-1.5 text-sm text-secondary hover:text-primary transition-colors mb-2">
                <i data-lucide="arrow-left" class="size-4"></i>
                Kembali ke Rekapitulasi
            </a>
            <h1 class="text-foreground text-2xl md:text-3xl font-bold mb-1">Peserta Ditolak</h1>
            <p class="text-secondary text-sm">
                Batch #{{ $latestBatch }} —
                @if($search)
                <span class="font-semibold text-foreground">{{ number_format($rejected->total(), 0, ',', '.') }}</span> hasil untuk
                <span class="font-semibold text-primary">"{{ $search }}"</span>
                @else
                {{ number_format($rejected->total(), 0, ',', '.') }} peserta tidak lolos
                @endif
            </p>
        </div>
        {{-- Search --}}
        <div class="relative shrink-0">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary pointer-events-none"></i>
            <input
                type="text"
                id="rejected-search"
                hx-preserve="true" {{-- Menjaga agar elemen input tidak di-re-render HTMX, kursor tidak hilang saat mengetik --}}
                value="{{ $search }}"
                placeholder="Nama / no. pendaftaran…"
                oninput="handleRejectedSearch(this)"
                class="pl-9 pr-8 py-2.5 text-sm border border-border rounded-xl bg-white text-foreground placeholder:text-secondary focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-all w-72" />
            <button
                id="rejected-search-clear"
                type="button"
                onclick="clearRejectedSearch()"
                class="{{ $search ? '' : 'hidden' }} absolute right-2.5 top-1/2 -translate-y-1/2 text-secondary hover:text-foreground transition-colors cursor-pointer">
                <i data-lucide="x" class="size-4"></i>
            </button>
        </div>
    </div>

    <div class="flex flex-col rounded-2xl border border-border p-6 bg-white gap-6">

        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px] border-collapse">
                <thead class="border-b border-border">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-bold text-foreground">Peserta</th>
                        <th class="px-4 py-3 text-left text-sm font-bold text-foreground">Asal Sekolah</th>
                        <th class="px-4 py-3 text-left text-sm font-bold text-foreground">Pilihan Jurusan</th>
                        <th class="px-4 py-3 text-left text-sm font-bold text-foreground">Jalur</th>
                        <th class="px-4 py-3 text-center text-sm font-bold text-foreground">Skor Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($rejected as $i => $r)
                    @php
                    $reg = $r->registration;
                    $fullName = $reg->personalData->full_name ?? 'Tanpa Nama';
                    $init = strtoupper(substr($fullName, 0, 2));
                    $colors = ['linear-gradient(135deg,#FF1443,#FF6B6B)', 'linear-gradient(135deg,#3B82F6,#93C5FD)', 'linear-gradient(135deg,#F59E0B,#FCD34D)', 'linear-gradient(135deg,#8B5CF6,#A78BFA)'];
                    $color = $colors[$i % 4];
                    $phone = $reg->personalData->phone_number ?? '-';
                    @endphp
                    <tr class="hover:bg-muted/50 transition-colors">
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0"
                                    style="background: {{ $color }}">{{ $init }}</div>
                                <div>
                                    <div class="font-semibold text-sm text-foreground uppercase">{{ $fullName }}</div>
                                    <div class="text-xs text-secondary font-mono">{{ $reg->registration_number ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-sm font-medium text-foreground uppercase">{{ $reg->personalData->previous_school ?? '-' }}</div>

                            @if($phone !== '-')
                            @php
                            // 1. Bersihkan semua karakter selain angka (seperti tanda +, spasi, atau strip)
                            $waNumber = preg_replace('/[^0-9]/', '', $phone);

                            // 2. Jika nomor diawali dengan angka '0', ganti menjadi '62'
                            if (str_starts_with($waNumber, '0')) {
                            $waNumber = '62' . substr($waNumber, 1);
                            }
                            @endphp

                            <a href="https://wa.me/{{ $waNumber }}" target="_blank" class="mt-1 inline-flex items-center gap-x-1.5 text-xs text-secondary hover:text-green-600 hover:underline transition-colors">
                                <i data-lucide="phone" class="size-3"></i>
                                <span>{{ $phone }}</span>
                            </a>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2">
                                @if($reg->choice1)
                                <span class="inline-flex items-center justify-center gap-1 w-[60px] py-1 rounded-md text-xs font-bold border bg-red-100 text-red-700 border-red-200">
                                    <span class="font-normal opacity-75">1.</span>
                                    <span class="truncate">{{ $reg->choice1->alias ?? '-' }}</span>
                                </span>
                                @endif
                                @if($reg->choice2)
                                <span class="inline-flex items-center justify-center gap-1 w-[60px] py-1 rounded-md text-xs font-bold border bg-yellow-100 text-yellow-800 border-yellow-300">
                                    <span class="font-normal opacity-75">2.</span>
                                    <span class="truncate">{{ $reg->choice2->alias ?? '-' }}</span>
                                </span>
                                @endif
                                @if($reg->choice3)
                                <span class="inline-flex items-center justify-center gap-1 w-[60px] py-1 rounded-md text-xs font-bold border bg-gray-700 text-white border-gray-800">
                                    <span class="font-normal opacity-75">3.</span>
                                    <span class="truncate">{{ $reg->choice3->alias ?? '-' }}</span>
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border"
                                style="background:#EEEDFE;color:#3C3489;border-color:#AFA9EC">
                                {{ $reg->admissionPath->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="font-mono font-bold text-sm {{ $r->final_score > 0 ? 'text-foreground' : 'text-secondary' }}">
                                {{ $r->final_score > 0 ? number_format($r->final_score, 2) : '—' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-secondary">
                                @if($search)
                                <i data-lucide="search-x" class="size-10 text-border"></i>
                                <p class="font-medium">Tidak ada hasil untuk "{{ $search }}"</p>
                                @else
                                <i data-lucide="check-circle" class="size-10 text-success"></i>
                                <p class="font-medium">Semua peserta berhasil diterima!</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- HTMX Pagination Terintegrasi --}}
        @if($rejected->hasPages())
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 pt-4">
            <span class="text-sm text-secondary text-center">
                Menampilkan <span class="font-semibold text-foreground">{{ $rejected->firstItem() ?? 0 }}</span>
                sampai <span class="font-semibold text-foreground">{{ $rejected->lastItem() ?? 0 }}</span>
                dari <span class="font-semibold text-foreground">{{ number_format($rejected->total() ?? 0, 0, ',', '.') }}</span> peserta
            </span>
            <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0">
                @if ($rejected->onFirstPage())
                <button class="p-2 rounded-lg border border-border bg-white hover:bg-muted cursor-not-allowed opacity-50 transition-colors" disabled>
                    <i data-lucide="chevron-left" class="size-4"></i>
                </button>
                @else
                <button type="button" hx-get="{{ $rejected->previousPageUrl() }}" hx-target="#rejected-container"
                    hx-select="#rejected-container" hx-swap="outerHTML show:window:top" hx-push-url="true"
                    class="p-2 rounded-lg border border-border bg-white hover:bg-muted cursor-pointer transition-colors">
                    <i data-lucide="chevron-left" class="size-4"></i>
                </button>
                @endif

                @php
                $curr = $rejected->currentPage();
                $last = $rejected->lastPage();
                $pages = collect([1, $curr - 1, $curr, $curr + 1, $last])
                ->filter(fn($p) => $p >= 1 && $p <= $last)
                    ->unique()->sort()->values();
                    @endphp

                    @foreach ($pages as $i => $page)
                    @if ($i > 0 && $page - $pages[$i - 1] > 1)
                    <span class="px-1 text-secondary text-sm">…</span>
                    @endif

                    @if ($page === $curr)
                    <button class="w-9 h-9 rounded-lg bg-primary text-white text-sm font-bold" disabled>{{ $page }}</button>
                    @else
                    <button type="button"
                        hx-get="{{ $rejected->url($page) }}"
                        hx-target="#rejected-container" hx-select="#rejected-container"
                        hx-swap="outerHTML show:window:top" hx-push-url="true"
                        class="w-9 h-9 rounded-lg border border-border bg-white hover:bg-muted text-sm cursor-pointer transition-colors">
                        {{ $page }}
                    </button>
                    @endif
                    @endforeach

                    @if ($rejected->hasMorePages())
                    <button type="button" hx-get="{{ $rejected->nextPageUrl() }}" hx-target="#rejected-container"
                        hx-select="#rejected-container" hx-swap="outerHTML show:window:top" hx-push-url="true"
                        class="p-2 rounded-lg border border-border bg-white hover:bg-muted cursor-pointer transition-colors">
                        <i data-lucide="chevron-right" class="size-4"></i>
                    </button>
                    @else
                    <button class="p-2 rounded-lg border border-border bg-white hover:bg-muted cursor-not-allowed opacity-50 transition-colors" disabled>
                        <i data-lucide="chevron-right" class="size-4"></i>
                    </button>
                    @endif
            </div>
        </div>
        @endif

    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
    });

    document.addEventListener('htmx:afterSwap', function() {
        if (window.lucide) lucide.createIcons();

        // Sinkronisasi nilai tombol X saja, nilai input dijaga oleh hx-preserve
        const input = document.getElementById('rejected-search');
        const clearBtn = document.getElementById('rejected-search-clear');

        if (clearBtn && input) {
            clearBtn.classList.toggle('hidden', !input.value);
            clearBtn.onclick = clearRejectedSearch;
        }
    });

    let _rejectedTimer = null;

    function handleRejectedSearch(input) {
        const clearBtn = document.getElementById('rejected-search-clear');
        if (clearBtn) clearBtn.classList.toggle('hidden', input.value === '');

        clearTimeout(_rejectedTimer);
        _rejectedTimer = setTimeout(() => {
            doRejectedSearch(input.value.trim());
        }, 400);
    }

    function clearRejectedSearch() {
        const input = document.getElementById('rejected-search');
        if (input) {
            input.value = '';
            input.focus();
        }
        const clearBtn = document.getElementById('rejected-search-clear');
        if (clearBtn) clearBtn.classList.add('hidden');
        doRejectedSearch('');
    }

    function doRejectedSearch(query) {
        const url = new URL(window.location.href);
        if (query) {
            url.searchParams.set('search', query);
        } else {
            url.searchParams.delete('search');
        }
        url.searchParams.delete('page'); // reset ke halaman 1

        htmx.ajax('GET', url.toString(), {
            target: '#rejected-container',
            select: '#rejected-container',
            swap: 'outerHTML show:window:top',
            pushUrl: true,
        });
    }
</script>
@endpush