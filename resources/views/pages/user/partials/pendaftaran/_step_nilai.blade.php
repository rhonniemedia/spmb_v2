{{-- _step_nilai.blade.php --}}
{{-- _step_nilai.blade.php --}}
<div x-show="currentStepId === 'nilai'"
    x-data="{ isNilaiLengkap: false }" {{-- 1. Tambahkan state lokal untuk mendeteksi kelengkapan --}}
    x-init="
        $nextTick(() => {
            const raporFields = ['rapor_sem1','rapor_sem2','rapor_sem3','rapor_sem4','rapor_sem5'];
            const tkaFields   = ['tka_mtk','tka_bind'];
            const allFields   = [...raporFields, ...tkaFields]; {{-- Gabungan semua field --}}

            function calcAvg(ids, targetId) {
                const vals = ids.map(id => parseFloat(document.getElementById(id)?.value) || 0);
                const filled = vals.filter(v => v > 0);
                const avg = filled.length ? filled.reduce((a, b) => a + b, 0) / ids.length : 0;
                
                const formattedAvg = avg > 0 ? avg.toFixed(2) : '';
                
                const el = document.getElementById(targetId);
                if (el) el.value = formattedAvg;
                
                if (targetId === 'rata_rapor') {
                    $data.rataRapor = formattedAvg;
                } else if (targetId === 'rata_tka') {
                    $data.rataTka = formattedAvg;
                }

                {{-- 2. 🌟 VALIDASI LIVE: Cek apakah ke-7 field sudah terisi semua dengan nilai > 0 --}}
                const raporFilled = raporFields.map(id => parseFloat(document.getElementById(id)?.value) || 0).filter(v => v > 0);
                isNilaiLengkap = raporFilled.length === raporFields.length;
            }

            {{-- Daftarkan event listener ke semua input --}}
            allFields.forEach(id => {
                document.getElementById(id)?.addEventListener('input', () => {
                    calcAvg(raporFields, 'rata_rapor');
                    calcAvg(tkaFields, 'rata_tka');
                });
            });

            // Jalankan kalkulasi awal jika data lama sudah ada (prefilled)
            calcAvg(raporFields, 'rata_rapor');
            calcAvg(tkaFields, 'rata_tka');
        })
    "
    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">

    {{-- ── HEADER ── --}}
    <div class="px-8 pt-6 pb-5 border-b border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-table-list text-[#FF1443] text-xl"></i>
        </div>
        <div>
            <h2 class="text-lg font-black text-[#080C1A]">Nilai Rapor & TKA</h2>
            <p class="text-sm text-[#6A7686]">Masukkan nilai rata-rata per semester dan hasil Tes Kemampuan Akademik</p>
        </div>
    </div>

    <div class="px-8 py-7 space-y-8">

        <div class="flex gap-3 items-start bg-blue-50 border border-blue-200 rounded-2xl px-4 py-3.5">
            <i class="fa-solid fa-circle-info text-blue-500 text-base mt-0.5 flex-shrink-0"></i>
            <p class="text-sm font-medium text-blue-800 leading-relaxed">
                Masukkan nilai <strong>rata-rata</strong> seluruh mata pelajaran per semester. Nilai yang dimasukkan harus sesuai dengan rapor asli dan dapat diverifikasi panitia.
            </p>
        </div>

        {{-- ── BAGIAN A: NILAI RAPOR ── --}}
        <div>
            <div class="flex items-center gap-2 mb-4">
                <div class="w-6 h-6 rounded-md bg-[#FF1443] flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-[10px] font-black">A</span>
                </div>
                <h3 class="text-[15px] font-black text-[#080C1A]">Nilai Rapor (Semester 1–5)</h3>
            </div>

            <div class="border border-gray-200 rounded-2xl overflow-hidden">
                <div class="grid grid-cols-[1fr_160px] bg-gray-50 border-b border-gray-200 px-5 py-3">
                    <span class="text-[12px] font-black uppercase tracking-widest text-[#6A7686]">Semester</span>
                    <span class="text-[12px] font-black uppercase tracking-widest text-[#6A7686] text-center">Nilai Rata-Rata</span>
                </div>

                @php
                $semesters = [
                ['label' => 'Semester 1', 'sub' => 'Kelas VII / Ganjil', 'id' => 'rapor_sem1'],
                ['label' => 'Semester 2', 'sub' => 'Kelas VII / Genap', 'id' => 'rapor_sem2'],
                ['label' => 'Semester 3', 'sub' => 'Kelas VIII / Ganjil', 'id' => 'rapor_sem3'],
                ['label' => 'Semester 4', 'sub' => 'Kelas VIII / Genap', 'id' => 'rapor_sem4'],
                ['label' => 'Semester 5', 'sub' => 'Kelas IX / Ganjil', 'id' => 'rapor_sem5'],
                ];
                @endphp

                @foreach($semesters as $sem)
                <div class="grid grid-cols-[1fr_160px] items-center px-5 py-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }} hover:bg-gray-50/60 transition-colors group">
                    <div>
                        <div class="text-[14px] font-bold text-[#080C1A]">{{ $sem['label'] }}</div>
                        <div class="text-[12px] text-[#6A7686] mt-0.5">{{ $sem['sub'] }}</div>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <div class="flex items-center justify-end gap-2">
                            <input type="number"
                                id="{{ $sem['id'] }}"
                                name="{{ $sem['id'] }}"
                                value="{{ old($sem['id'], $registrationData->{$sem['id']} ?? '') }}"
                                min="0" max="100" step="0.01"
                                placeholder="0.00"
                                class="w-[100px] text-right border border-gray-200 rounded-xl px-3 py-2 text-[14px] font-bold text-[#080C1A] focus:outline-none focus:ring-[#FF1443]/30 focus:border-[#FF1443] transition-all">
                            <span class="text-[12px] text-[#6A7686] font-medium w-4 flex-shrink-0"></span>
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- Rata-rata Rapor --}}
                <div class="grid grid-cols-[1fr_160px] items-center px-5 py-4 bg-red-50/50 border-t-2 border-dashed border-red-200">
                    <div>
                        <div class="text-[14px] font-black text-[#080C1A]">Nilai Rata-Rata Rapor</div>
                        <div class="text-[12px] text-[#6A7686] mt-0.5">Dihitung otomatis dari Sem 1–5</div>
                    </div>
                    <div class="flex items-center justify-end gap-2">
                        <input type="text"
                            id="rata_rapor"
                            readonly
                            placeholder="—"
                            class="w-[100px] text-right bg-white border border-red-200 rounded-xl px-3 py-2 text-[14px] font-black text-[#FF1443] cursor-not-allowed">
                        <span class="text-[12px] text-[#6A7686] font-medium w-4 flex-shrink-0"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── BAGIAN B: NILAI TKA ── --}}
        <div>
            <div class="flex items-center gap-2 mb-4">
                <div class="w-6 h-6 rounded-md bg-[#6366F1] flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-[10px] font-black">B</span>
                </div>
                <h3 class="text-[15px] font-black text-[#080C1A]">
                    Nilai TKA <span class="text-[13px] text-[#6A7686] font-medium">(Tes Kemampuan Akademik)</span>
                </h3>
            </div>

            <div class="border border-gray-200 rounded-2xl overflow-hidden">
                <div class="grid grid-cols-[1fr_160px] bg-gray-50 border-b border-gray-200 px-5 py-3">
                    <span class="text-[12px] font-black uppercase tracking-widest text-[#6A7686]">Mata Uji</span>
                    <span class="text-[12px] font-black uppercase tracking-widest text-[#6A7686] text-center">Nilai</span>
                </div>

                @php
                $tka = [
                ['label' => 'Matematika', 'sub' => 'Penalaran numerik & aljabar', 'id' => 'tka_mtk'],
                ['label' => 'Bahasa Indonesia', 'sub' => 'Literasi & pemahaman teks', 'id' => 'tka_bind'],
                ];
                @endphp

                @foreach($tka as $item)
                <div class="grid grid-cols-[1fr_160px] items-center px-5 py-4 {{ !$loop->last ? 'border-b border-gray-100' : '' }} hover:bg-gray-50/60 transition-colors group">
                    <div>
                        <div class="text-[14px] font-bold text-[#080C1A]">{{ $item['label'] }}</div>
                        <div class="text-[12px] text-[#6A7686] mt-0.5">{{ $item['sub'] }}</div>
                    </div>
                    <div class="flex items-center justify-end gap-2">
                        <input type="number"
                            id="{{ $item['id'] }}"
                            name="{{ $item['id'] }}"
                            value="{{ old($item['id'], $registrationData->{$item['id']} ?? '') }}"
                            min="0" max="100" step="0.01"
                            placeholder="0.00"
                            class="w-[100px] text-right border border-gray-200 rounded-xl px-3 py-2 text-[14px] font-bold text-[#080C1A] focus:outline-none focus:ring-[#6366F1]/30 focus:border-[#6366F1] transition-all">
                        <span class="text-[12px] text-[#6A7686] font-medium w-4 flex-shrink-0"></span>
                    </div>
                </div>
                @endforeach

                {{-- Rata-rata TKA --}}
                <div class="grid grid-cols-[1fr_160px] items-center px-5 py-4 bg-indigo-50/50 border-t-2 border-dashed border-indigo-200">
                    <div>
                        <div class="text-[14px] font-black text-[#080C1A]">Nilai Rata-Rata TKA</div>
                        <div class="text-[12px] text-[#6A7686] mt-0.5">Dihitung otomatis dari 2 mata uji</div>
                    </div>
                    <div class="flex items-center justify-end gap-2">
                        <input type="text"
                            id="rata_tka"
                            readonly
                            placeholder="—"
                            class="w-[100px] text-right bg-white border border-indigo-200 rounded-xl px-3 py-2 text-[14px] font-black text-[#6366F1] cursor-not-allowed">
                        <span class="text-[12px] text-[#6A7686] font-medium w-4 flex-shrink-0"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── FOOTER NAV ── --}}
    <div class="px-8 py-5 border-t border-gray-200 bg-gray-50/50 flex items-center justify-end rounded-b-[20px]">
        {{-- 🌟 INTEGRASI HTMX & ALPINE: Mengontrol state tombol berdasarkan kelengkapan nilai --}}
        <button type="button"
            hx-post="{{ route('registration.step1') }}"
            hx-include="#rapor_sem1, #rapor_sem2, #rapor_sem3, #rapor_sem4, #rapor_sem5, #tka_mtk, #tka_bind"
            hx-target="this"
            hx-swap="none"
            hx-indicator="#loading-spinner"
            hx-on::after-request="
                const res = JSON.parse(event.detail.xhr.responseText);
                if (res.success) window.dispatchEvent(new CustomEvent('pindah-step', { detail: { nextStep: 'jalur' } }))
            "
            :disabled="!isNilaiLengkap"
            :class="isNilaiLengkap 
                ? 'bg-[#FF1443] hover:bg-[#D90F38] hover:-translate-y-px shadow-lg shadow-red-500/30 cursor-pointer' 
                : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
            class="inline-flex items-center gap-2 px-8 py-2.5 text-white text-sm font-black rounded-full transition-all">

            {{-- Loading Spinner khusus HTMX --}}
            <span id="loading-spinner" class="htmx-indicator mr-1">
                <i class="fa-solid fa-circle-notch animate-spin"></i>
            </span>

            Lanjut <i class="fa-solid fa-arrow-right ml-1"></i>
        </button>
    </div>
</div>