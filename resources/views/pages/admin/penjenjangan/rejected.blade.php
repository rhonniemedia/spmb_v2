@extends('layouts.admin')

@section('title', 'Peserta Ditolak')
@section('page_title', 'Peserta Ditolak')
@section('page_subtitle', 'Daftar peserta yang tidak lolos penjenjangan')

@section('content')

<div class="flex-1 overflow-y-auto p-5 md:p-8 bg-white">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('admin.penjenjangan.index') }}"
                class="inline-flex items-center gap-1.5 text-sm text-secondary hover:text-primary transition-colors mb-2">
                <i data-lucide="arrow-left" class="size-4"></i>
                Kembali ke Rekapitulasi
            </a>
            <h1 class="text-foreground text-2xl md:text-3xl font-bold mb-1">Peserta Ditolak</h1>
            <p class="text-secondary text-sm">Batch #{{ $latestBatch }} — {{ $rejected->total() }} peserta tidak lolos</p>
        </div>
    </div>

    <div class="flex flex-col rounded-2xl border border-border p-6 bg-white gap-6">

        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px] border-collapse">
                <thead class="border-b border-border">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-bold text-foreground">Peserta</th>
                        <th class="px-4 py-3 text-left text-sm font-bold text-foreground">Jalur</th>
                        <th class="px-4 py-3 text-left text-sm font-bold text-foreground">Pilihan Jurusan</th>
                        <th class="px-4 py-3 text-center text-sm font-bold text-foreground">Skor Akhir</th>
                        <th class="px-4 py-3 text-left text-sm font-bold text-foreground">Sekolah Asal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($rejected as $i => $r)
                    @php
                    $fullName = $r->registration->personalData->full_name ?? 'Tanpa Nama';
                    $init = strtoupper(substr($fullName, 0, 2));
                    $colors = ['linear-gradient(135deg,#FF1443,#FF6B6B)', 'linear-gradient(135deg,#3B82F6,#93C5FD)', 'linear-gradient(135deg,#F59E0B,#FCD34D)', 'linear-gradient(135deg,#8B5CF6,#A78BFA)'];
                    $color = $colors[$i % 4];
                    $reg = $r->registration;
                    @endphp
                    <tr class="hover:bg-muted/50 transition-colors">
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0"
                                    style="background: {{ $color }}">{{ $init }}</div>
                                <div>
                                    <p class="font-semibold text-sm text-foreground uppercase">{{ $fullName }}</p>
                                    <p class="text-xs text-secondary font-mono">{{ $reg->registration_number ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border"
                                style="background:#EEEDFE;color:#3C3489;border-color:#AFA9EC">
                                {{ $reg->admissionPath->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col gap-1 text-xs">
                                @if($reg->choice1)
                                <span class="inline-flex items-center gap-1">
                                    <span class="text-secondary">1.</span>
                                    <span class="font-medium text-foreground">{{ $reg->choice1->alias ?? '-' }}</span>
                                </span>
                                @endif
                                @if($reg->choice2)
                                <span class="inline-flex items-center gap-1">
                                    <span class="text-secondary">2.</span>
                                    <span class="font-medium text-foreground">{{ $reg->choice2->alias ?? '-' }}</span>
                                </span>
                                @endif
                                @if($reg->choice3)
                                <span class="inline-flex items-center gap-1">
                                    <span class="text-secondary">3.</span>
                                    <span class="font-medium text-foreground">{{ $reg->choice3->alias ?? '-' }}</span>
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="font-mono font-bold text-sm {{ $r->final_score > 0 ? 'text-foreground' : 'text-secondary' }}">
                                {{ $r->final_score > 0 ? number_format($r->final_score, 2) : '—' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-sm text-foreground">
                            {{ $reg->personalData->previous_school ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-secondary">
                                <i data-lucide="check-circle" class="size-10 text-success"></i>
                                <p class="font-medium">Semua peserta berhasil diterima!</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($rejected->hasPages())
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 pt-4">
            <span class="text-sm text-secondary">
                Menampilkan <span class="font-semibold text-foreground">{{ $rejected->firstItem() }}</span>
                sampai <span class="font-semibold text-foreground">{{ $rejected->lastItem() }}</span>
                dari <span class="font-semibold text-foreground">{{ number_format($rejected->total(), 0, ',', '.') }}</span> peserta
            </span>
            <div class="flex items-center gap-2">
                @if(!$rejected->onFirstPage())
                <a href="{{ $rejected->previousPageUrl() }}" class="p-2 rounded-lg border border-border bg-white hover:bg-muted transition-colors">
                    <i data-lucide="chevron-left" class="size-4"></i>
                </a>
                @endif
                @if($rejected->hasMorePages())
                <a href="{{ $rejected->nextPageUrl() }}" class="p-2 rounded-lg border border-border bg-white hover:bg-muted transition-colors">
                    <i data-lucide="chevron-right" class="size-4"></i>
                </a>
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
</script>
@endpush