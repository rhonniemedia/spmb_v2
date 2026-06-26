<div class="bg-white border border-gray-100 rounded-2xl overflow-hidden">

    {{-- Header --}}
    <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-route text-blue-500 text-sm"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-900">Alur Seleksi PPDB</h3>
                <p class="text-xs text-gray-400 mt-0.5">SMK Negeri 1 Rejang Lebong</p>
            </div>
        </div>

        <div class="flex items-center gap-2 text-xs font-medium text-blue-600">
            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
            {{ $currentActiveStepText }}
        </div>
    </div>

    {{-- Step Cards: Baris 1 --}}
    <div class="p-5">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach($topSteps as $step)
            @include('pages.user.partials.dashboard._step-card', ['step' => $step])
            @endforeach
        </div>

        {{-- Step Cards: Baris 2 --}}
        @if(!empty($bottomSteps))
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-3 pt-4 border-t border-dashed border-gray-100">
            @foreach($bottomSteps as $step)
            @include('pages.user.partials.dashboard._step-card', ['step' => $step])
            @endforeach
        </div>
        @endif
    </div>

    {{-- Catatan --}}
    <div class="mx-5 mb-5 p-3 rounded-xl bg-blue-50 border border-blue-100 flex items-start gap-2.5">
        <i class="fa-solid fa-circle-info text-blue-400 text-sm mt-0.5 shrink-0"></i>
        <p class="text-xs text-gray-500 leading-relaxed">
            Kemajuan tahap pendaftaran diperbarui secara otomatis. Pastikan seluruh berkas siap sebelum tenggat waktu tahap aktif berakhir.
        </p>
    </div>

</div>