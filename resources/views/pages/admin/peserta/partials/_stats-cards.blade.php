{{-- File: resources/views/pages/admin/observasi/partials/_stats-cards.blade.php --}}
<div id="stats-container" {!! isset($isOob) && $isOob ? 'hx-swap-oob="true"' : '' !!}>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

        {{-- Total Peserta --}}
        <div class="relative overflow-hidden flex flex-col rounded-2xl border border-border p-5 gap-4 bg-white min-h-[130px] transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-black/5 hover:border-purple-200 cursor-default">
            <div class="absolute -top-5 -right-5 w-20 h-20 rounded-full bg-purple-400 opacity-[0.07]"></div>
            <div class="flex items-center gap-2">
                <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="users" class="size-5 text-primary"></i>
                </div>
                <p class="font-medium text-xs text-secondary">Total Peserta</p>
            </div>
            <div class="border-t border-dashed border-border pt-3">
                <p class="font-bold text-3xl">{{ number_format($totalPesertaStats, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Pending --}}
        <div class="relative overflow-hidden flex flex-col rounded-2xl border border-border p-5 gap-4 bg-white min-h-[130px] transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-black/5 hover:border-yellow-200 cursor-default">
            <div class="absolute -top-5 -right-5 w-20 h-20 rounded-full bg-warning opacity-[0.07]"></div>
            <div class="flex items-center gap-2">
                <div class="size-10 bg-warning/10 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="clock" class="size-5 text-warning"></i>
                </div>
                <p class="font-medium text-xs text-secondary">Pending</p>
            </div>
            <div class="border-t border-dashed border-border pt-3 flex items-baseline gap-2">
                <p class="font-bold text-3xl">{{ number_format($pendingStats, 0, ',', '.') }}</p>
                <span class="text-warning text-xs font-semibold bg-warning/10 px-2 py-0.5 rounded-md">Perlu tindakan</span>
            </div>
        </div>

        {{-- Terverifikasi --}}
        <div class="relative overflow-hidden flex flex-col rounded-2xl border border-border p-5 gap-4 bg-white min-h-[130px] transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-black/5 hover:border-green-200 cursor-default">
            <div class="absolute -top-5 -right-5 w-20 h-20 rounded-full bg-success opacity-[0.07]"></div>
            <div class="flex items-center gap-2">
                <div class="size-10 bg-success/10 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="check-circle" class="size-5 text-success"></i>
                </div>
                <p class="font-medium text-xs text-secondary">Terverifikasi</p>
            </div>
            <div class="border-t border-dashed border-border pt-3 flex items-baseline gap-2">
                <p class="font-bold text-3xl">{{ number_format($passedStats, 0, ',', '.') }}</p>
                <span class="text-success text-xs font-semibold bg-success/10 px-2 py-0.5 rounded-md">{{ $passedPercentage }}%</span>
            </div>
        </div>

        {{-- Ditolak --}}
        <div class="relative overflow-hidden flex flex-col rounded-2xl border border-border p-5 gap-4 bg-white min-h-[130px] transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-black/5 hover:border-red-200 cursor-default">
            <div class="absolute -top-5 -right-5 w-20 h-20 rounded-full bg-error opacity-[0.07]"></div>
            <div class="flex items-center gap-2">
                <div class="size-10 bg-error/10 rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="x-circle" class="size-5 text-error"></i>
                </div>
                <p class="font-medium text-xs text-secondary">Ditolak</p>
            </div>
            <div class="border-t border-dashed border-border pt-3">
                <p class="font-bold text-3xl">{{ number_format($failedStats, 0, ',', '.') }}</p>
            </div>
        </div>

    </div>
</div>