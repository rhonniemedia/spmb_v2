@extends('layouts.admin')

@section('title', 'Riwayat Penjenjangan')
@section('page_title', 'Riwayat Penjenjangan')
@section('page_subtitle', 'Histori semua batch penjenjangan yang pernah dijalankan')

@section('content')

<div class="flex-1 overflow-y-auto p-5 md:p-8 bg-white">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('admin.penjenjangan.index') }}"
                class="inline-flex items-center gap-1.5 text-sm text-secondary hover:text-primary transition-colors mb-2">
                <i data-lucide="arrow-left" class="size-4"></i>
                Kembali ke Rekapitulasi
            </a>
            <h1 class="text-foreground text-2xl md:text-3xl font-bold mb-1">Riwayat Penjenjangan</h1>
            <p class="text-secondary text-sm">Total {{ $batches->count() }} batch pernah dijalankan</p>
        </div>
    </div>

    @forelse($batches as $b)
    @php
    $pct = $b->total > 0 ? round($b->accepted / $b->total * 100) : 0;
    $isLatest = $loop->first;
    @endphp
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-5 rounded-2xl border
        {{ $isLatest ? 'border-primary/30 bg-primary/5' : 'border-border bg-white' }}
        mb-4 transition-all">

        {{-- Badge Batch --}}
        <div class="flex items-center justify-center size-14 rounded-2xl shrink-0
            {{ $isLatest ? 'bg-primary text-white' : 'bg-muted text-secondary' }}
            font-bold text-lg">
            #{{ $b->batch }}
        </div>

        {{-- Info --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1 flex-wrap">
                <p class="font-bold text-foreground">Batch #{{ $b->batch }}</p>
                @if($isLatest)
                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-primary/10 text-primary">Terbaru</span>
                @endif
            </div>
            <div class="flex items-center gap-4 text-xs text-secondary flex-wrap">
                <span class="flex items-center gap-1">
                    <i data-lucide="user" class="size-3"></i>
                    {{ $b->processor?->name ?? 'Sistem (Otomatis)' }}
                </span>
                <span class="flex items-center gap-1">
                    <i data-lucide="calendar" class="size-3"></i>
                    {{ $b->processed_at ? \Carbon\Carbon::parse($b->processed_at)->translatedFormat('d M Y, H:i') : '-' }}
                </span>
            </div>

            {{-- Progress bar --}}
            <div class="mt-3 w-full overflow-hidden bg-border rounded-full h-1.5 max-w-xs">
                <div class="h-1.5 rounded-full"
                    style="width: {{ $pct }}%; max-width:100%; background: {{ $pct >= 100 ? '#EF4444' : ($pct >= 75 ? '#F59E0B' : '#30B22D') }}">
                </div>
            </div>
        </div>

        {{-- Stats --}}
        <div class="flex items-center gap-6 shrink-0 flex-wrap">
            <div class="text-center">
                <p class="font-bold text-lg text-foreground">{{ number_format($b->total, 0, ',', '.') }}</p>
                <p class="text-[10px] text-secondary uppercase tracking-wide">Total</p>
            </div>
            <div class="text-center">
                <p class="font-bold text-lg text-success">{{ number_format($b->accepted, 0, ',', '.') }}</p>
                <p class="text-[10px] text-secondary uppercase tracking-wide">Diterima</p>
            </div>
            <div class="text-center">
                <p class="font-bold text-lg text-error">{{ number_format($b->rejected, 0, ',', '.') }}</p>
                <p class="text-[10px] text-secondary uppercase tracking-wide">Ditolak</p>
            </div>
            <div class="text-center min-w-[48px]">
                <p class="font-bold text-lg text-foreground">{{ $pct }}%</p>
                <p class="text-[10px] text-secondary uppercase tracking-wide">Terima</p>
            </div>
        </div>

    </div>
    @empty
    <div class="flex flex-col items-center gap-3 text-secondary py-16">
        <i data-lucide="inbox" class="size-10 text-border"></i>
        <p class="font-medium">Belum ada riwayat penjenjangan</p>
    </div>
    @endforelse

</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.lucide) lucide.createIcons();
    });
</script>
@endpush