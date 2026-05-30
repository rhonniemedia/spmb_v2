{{-- ═══════════════════════════════════════════
    MODAL OBSERVASI MULTI-STEP (DESAIN GEMINI + SKOR KATEGORI)
════════════════════════════════════════════ --}}
<div x-show="obsModalOpen"
    class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-cloak
    @click.self="closeObsModal()">

    <!-- KOTAK FRAME MODAL -->
    <div class="bg-white w-full max-w-3xl rounded-xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col max-h-[90vh]"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4">

        <!-- ── HEADER UTAMA MODAL ── -->
        <div class="px-6 pt-6 pb-5 border-b border-gray-100 flex items-center justify-between bg-white shrink-0">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="clipboard-list" class="size-5 text-blue-600"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-[#080C1A]">Edit Data Hasil Observasi</h2>
                    <p class="text-sm text-[#6A7686]" x-text="`${activePeserta?.name} - ${activePeserta?.reg_number}`"></p>
                </div>
            </div>
            <button type="button" @click="closeObsModal()" class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-gray-700 rounded-lg transition-colors hover:bg-gray-100">
                <i data-lucide="x" class="size-5"></i>
            </button>
        </div>

        <!-- ── SUB-HEADER: TABS PROGRESS INDICATOR ── -->
        <div class="px-6 pt-4 pb-2 border-b border-gray-100 grid gap-2 text-center bg-white shrink-0"
            :class="obsSteps.length === 3 ? 'grid-cols-3' : 'grid-cols-4'">
            <template x-for="(step, idx) in obsSteps" :key="idx">
                <button type="button" @click="obsStep = idx + 1"
                    :class="obsStep >= idx + 1 ? 'border-rose-500 text-rose-600 font-bold' : 'border-gray-200 text-gray-400'"
                    class="border-t-4 pt-1.5 text-xs uppercase tracking-wider transition-all text-center focus:outline-none"
                    x-text="`${idx + 1}. ${step.label}`"></button>
            </template>
        </div>

        <!-- ── ISI KONTEN FORM (SCROLLABLE BODY) ── -->
        <div class="overflow-y-auto flex-1 bg-white">
            <div class="p-6">

                {{-- ══ STEP 1: KONDISI FISIK DAN KESEHATAN ══ --}}
                <div x-show="obsStep === 1" class="space-y-3" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">

                    <div class="bg-rose-50 border border-rose-100 p-3 rounded-xl text-xs text-rose-800 mb-4 flex items-center gap-2">
                        <i data-lucide="alert-circle" class="size-4 shrink-0"></i>
                        <span>Pilih "Ya" atau "Tidak" sesuai dengan kondisi fisik riil calon siswa.</span>
                    </div>

                    {{-- Pendengaran --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 border border-gray-200 rounded-xl bg-gray-50/30 hover:border-rose-300 transition-colors">
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-0.5">Fungsi Pendengaran</label>
                            <p class="text-xs text-gray-500">Dapat mendengar dengan baik tanpa gangguan.</p>
                        </div>
                        <div class="flex gap-2 w-full sm:w-48 shrink-0">
                            <label :class="obsForm.hearing_check === 'yes' ? 'border-rose-400 bg-rose-50 text-rose-700' : 'border-gray-200 bg-white hover:border-rose-300'" class="flex-1 flex items-center justify-center gap-2 border rounded-xl py-2 cursor-pointer transition-all">
                                <input type="radio" x-model="obsForm.hearing_check" value="yes" class="accent-rose-600 w-3.5 h-3.5" required>
                                <span class="text-[13px] font-semibold">Ya</span>
                            </label>
                            <label :class="obsForm.hearing_check === 'no' ? 'border-rose-400 bg-rose-50 text-rose-700' : 'border-gray-200 bg-white hover:border-rose-300'" class="flex-1 flex items-center justify-center gap-2 border rounded-xl py-2 cursor-pointer transition-all">
                                <input type="radio" x-model="obsForm.hearing_check" value="no" class="accent-rose-600 w-3.5 h-3.5">
                                <span class="text-[13px] font-semibold">Tidak</span>
                            </label>
                        </div>
                    </div>

                    {{-- Penglihatan --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 border border-gray-200 rounded-xl bg-gray-50/30 hover:border-rose-300 transition-colors">
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-0.5">Fungsi Penglihatan</label>
                            <p class="text-xs text-gray-500">Normal, boleh menggunakan kacamata penunjang.</p>
                        </div>
                        <div class="flex gap-2 w-full sm:w-48 shrink-0">
                            <label :class="obsForm.vision_check === 'yes' ? 'border-rose-400 bg-rose-50 text-rose-700' : 'border-gray-200 bg-white hover:border-rose-300'" class="flex-1 flex items-center justify-center gap-2 border rounded-xl py-2 cursor-pointer transition-all">
                                <input type="radio" x-model="obsForm.vision_check" value="yes" class="accent-rose-600 w-3.5 h-3.5" required>
                                <span class="text-[13px] font-semibold">Ya</span>
                            </label>
                            <label :class="obsForm.vision_check === 'no' ? 'border-rose-400 bg-rose-50 text-rose-700' : 'border-gray-200 bg-white hover:border-rose-300'" class="flex-1 flex items-center justify-center gap-2 border rounded-xl py-2 cursor-pointer transition-all">
                                <input type="radio" x-model="obsForm.vision_check" value="no" class="accent-rose-600 w-3.5 h-3.5">
                                <span class="text-[13px] font-semibold">Tidak</span>
                            </label>
                        </div>
                    </div>

                    {{-- Aktivitas Fisik --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 border border-gray-200 rounded-xl bg-gray-50/30 hover:border-rose-300 transition-colors">
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-0.5">Kemampuan Aktivitas Fisik</label>
                            <p class="text-xs text-gray-500">Mampu melakukan aktivitas fisik ringan, sedang, hingga berat.</p>
                        </div>
                        <div class="flex gap-2 w-full sm:w-48 shrink-0">
                            <label :class="obsForm.physical_activity === 'yes' ? 'border-rose-400 bg-rose-50 text-rose-700' : 'border-gray-200 bg-white hover:border-rose-300'" class="flex-1 flex items-center justify-center gap-2 border rounded-xl py-2 cursor-pointer transition-all">
                                <input type="radio" x-model="obsForm.physical_activity" value="yes" class="accent-rose-600 w-3.5 h-3.5" required>
                                <span class="text-[13px] font-semibold">Ya</span>
                            </label>
                            <label :class="obsForm.physical_activity === 'no' ? 'border-rose-400 bg-rose-50 text-rose-700' : 'border-gray-200 bg-white hover:border-rose-300'" class="flex-1 flex items-center justify-center gap-2 border rounded-xl py-2 cursor-pointer transition-all">
                                <input type="radio" x-model="obsForm.physical_activity" value="no" class="accent-rose-600 w-3.5 h-3.5">
                                <span class="text-[13px] font-semibold">Tidak</span>
                            </label>
                        </div>
                    </div>

                    {{-- Buta Warna --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 border border-gray-200 rounded-xl bg-gray-50/30 hover:border-rose-300 transition-colors">
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-0.5">Buta Warna</label>
                            <p class="text-xs text-gray-500">Buta warna, tidak dapat membedakan warna tertentu.</p>
                        </div>
                        <div class="flex gap-2 w-full sm:w-48 shrink-0">
                            <label :class="obsForm.color_blind_check === 'yes' ? 'border-rose-400 bg-rose-50 text-rose-700' : 'border-gray-200 bg-white hover:border-rose-300'" class="flex-1 flex items-center justify-center gap-2 border rounded-xl py-2 cursor-pointer transition-all">
                                <input type="radio" x-model="obsForm.color_blind_check" value="yes" class="accent-rose-600 w-3.5 h-3.5" required>
                                <span class="text-[13px] font-semibold">Ya</span>
                            </label>
                            <label :class="obsForm.color_blind_check === 'no' ? 'border-rose-400 bg-rose-50 text-rose-700' : 'border-gray-200 bg-white hover:border-rose-300'" class="flex-1 flex items-center justify-center gap-2 border rounded-xl py-2 cursor-pointer transition-all">
                                <input type="radio" x-model="obsForm.color_blind_check" value="no" class="accent-rose-600 w-3.5 h-3.5">
                                <span class="text-[13px] font-semibold">Tidak</span>
                            </label>
                        </div>
                    </div>

                    <!-- SCORE ROW: Nilai Kondisi Fisik -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 border-2 rounded-xl transition-colors mt-2"
                        :class="(isButaWarna || isDisqualifiedCiri) ? 'border-red-300 bg-red-50/40' : 'border-blue-200 bg-blue-50/40'">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                                :class="(isButaWarna || isDisqualifiedCiri) ? 'bg-red-100' : 'bg-blue-100'">
                                <i data-lucide="star" class="size-4" :class="(isButaWarna || isDisqualifiedCiri) ? 'text-red-500' : 'text-blue-500'"></i>
                            </div>
                            <div>
                                <p class="text-sm font-black text-gray-800">Nilai Kondisi Fisik</p>
                                <p class="text-xs mt-0.5" :class="(isButaWarna || isDisqualifiedCiri) ? 'text-red-600 font-semibold' : 'text-blue-600'">
                                    <span x-show="isButaWarna">⚠ Buta warna = Ya → skor dinol-kan</span>
                                    <span x-show="!isButaWarna && isDisqualifiedCiri">⚠ Tato/Bekas Tato/Tindik = Ya → skor dinol-kan</span>
                                    <span x-show="!isButaWarna && !isDisqualifiedCiri">Akumulasi otomatis: <strong x-text="autoScoreFisik + ' / 100'"></strong></span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 w-full sm:w-auto shrink-0">
                            <div class="flex items-center justify-center w-14 h-10 rounded-xl font-black text-sm border-2"
                                :class="(isButaWarna || isDisqualifiedCiri) ? 'border-red-300 bg-red-100 text-red-600' : 'border-blue-300 bg-blue-100 text-blue-700'"
                                x-text="(isButaWarna || isDisqualifiedCiri) ? '0' : autoScoreFisik"></div>
                            <select x-model="obsForm.fisik_score"
                                :disabled="isButaWarna || isDisqualifiedCiri"
                                class="flex-1 sm:w-32 bg-white border-2 rounded-xl px-3 py-2 text-sm font-bold focus:outline-none transition-all disabled:opacity-40 disabled:cursor-not-allowed"
                                :class="(isButaWarna || isDisqualifiedCiri) ? 'border-red-200 text-red-400' : 'border-blue-300 text-blue-700 focus:border-blue-500'">
                                <option value="">Pilih Nilai</option>
                                <template x-for="n in [50,55,60,65,70,75,80,85,90,95,100]" :key="n">
                                    <option :value="n" x-text="n"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                </div>

                {{-- ══ STEP 2: MODIFIKASI TUBUH DAN CIRI KHUSUS ══ --}}
                <div x-show="obsStep === 2" class="space-y-3" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">

                    @php
                    $ciriItems = [
                    ['field' => 'tattoo', 'label' => 'Tato yang Terlihat', 'desc' => 'Baik permanen maupun semi permanen.', 'disqualify' => true],
                    ['field' => 'tattoo_scar', 'label' => 'Memiliki Bekas Tato', 'desc' => 'Termasuk bekas yang samar atau proses penghilangan.', 'disqualify' => true],
                    ['field' => 'piercing', 'label' => 'Memiliki Tindik (Laki-laki)', 'desc' => 'Pilih "Tidak" untuk perempuan.', 'disqualify' => true],
                    ['field' => 'keloid', 'label' => 'Bekas Luka Mencolok / Keloid', 'desc' => 'Yang tampak jelas di area terbuka.', 'disqualify' => false],
                    ['field' => 'minor_disability', 'label' => 'Cacat Fisik Ringan', 'desc' => 'Seperti bentuk kaki tidak simetris, jari tambahan.', 'disqualify' => false],
                    ['field' => 'aid_tool', 'label' => 'Alat Bantu Permanen', 'desc' => 'Misalnya alat bantu dengar, pen, implan.', 'disqualify' => false],
                    ];
                    @endphp

                    @foreach($ciriItems as $item)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 border rounded-xl transition-colors bg-gray-50/30"
                        :class="obsForm.{{ $item['field'] }} === 'yes' && {{ $item['disqualify'] ? 'true' : 'false' }} ? 'border-red-400 bg-red-50/30' : 'border-gray-200 hover:border-rose-300'">
                        <div>
                            <div class="flex items-center gap-2 mb-0.5">
                                <label class="text-sm font-bold text-gray-800">{{ $item['label'] }}</label>
                                @if($item['disqualify'])
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-700">DISKUALIFIKASI</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500">{{ $item['desc'] }}</p>
                        </div>
                        <div class="flex gap-2 w-full sm:w-48 shrink-0">
                            <label :class="obsForm.{{ $item['field'] }} === 'yes' ? 'border-rose-400 bg-rose-50 text-rose-700' : 'border-gray-200 bg-white hover:border-rose-300'" class="flex-1 flex items-center justify-center gap-2 border rounded-xl py-2 cursor-pointer transition-all">
                                <input type="radio" x-model="obsForm.{{ $item['field'] }}" value="yes" class="accent-rose-600 w-3.5 h-3.5" required>
                                <span class="text-[13px] font-semibold">Ya</span>
                            </label>
                            <label :class="obsForm.{{ $item['field'] }} === 'no' ? 'border-rose-400 bg-rose-50 text-rose-700' : 'border-gray-200 bg-white hover:border-rose-300'" class="flex-1 flex items-center justify-center gap-2 border rounded-xl py-2 cursor-pointer transition-all">
                                <input type="radio" x-model="obsForm.{{ $item['field'] }}" value="no" class="accent-rose-600 w-3.5 h-3.5">
                                <span class="text-[13px] font-semibold">Tidak</span>
                            </label>
                        </div>
                    </div>
                    @endforeach

                    <!-- SCORE ROW: Nilai Ciri Khusus -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 border-2 rounded-xl transition-colors mt-2"
                        :class="(isButaWarna || isDisqualifiedCiri) ? 'border-red-300 bg-red-50/40' : 'border-violet-200 bg-violet-50/40'">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                                :class="(isButaWarna || isDisqualifiedCiri) ? 'bg-red-100' : 'bg-violet-100'">
                                <i data-lucide="star" class="size-4" :class="(isButaWarna || isDisqualifiedCiri) ? 'text-red-500' : 'text-violet-500'"></i>
                            </div>
                            <div>
                                <p class="text-sm font-black text-gray-800">Nilai Ciri Khusus</p>
                                <p class="text-xs mt-0.5" :class="(isButaWarna || isDisqualifiedCiri) ? 'text-red-600 font-semibold' : 'text-violet-600'">
                                    <span x-show="isButaWarna">⚠ Buta warna = Ya → skor dinol-kan</span>
                                    <span x-show="!isButaWarna && isDisqualifiedCiri">⚠ Tato/Bekas Tato/Tindik = Ya → skor step 1 &amp; 2 dinol-kan</span>
                                    <span x-show="!isButaWarna && !isDisqualifiedCiri">Akumulasi otomatis: <strong x-text="autoScoreCiri + ' / 100'"></strong></span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 w-full sm:w-auto shrink-0">
                            <div class="flex items-center justify-center w-14 h-10 rounded-xl font-black text-sm border-2"
                                :class="(isButaWarna || isDisqualifiedCiri) ? 'border-red-300 bg-red-100 text-red-600' : 'border-violet-300 bg-violet-100 text-violet-700'"
                                x-text="(isButaWarna || isDisqualifiedCiri) ? '0' : autoScoreCiri"></div>
                            <select x-model="obsForm.ciri_score"
                                :disabled="isButaWarna || isDisqualifiedCiri"
                                class="flex-1 sm:w-32 bg-white border-2 rounded-xl px-3 py-2 text-sm font-bold focus:outline-none transition-all disabled:opacity-40 disabled:cursor-not-allowed"
                                :class="(isButaWarna || isDisqualifiedCiri) ? 'border-red-200 text-red-400' : 'border-violet-300 text-violet-700 focus:border-violet-500'">
                                <option value="">Pilih Nilai</option>
                                <template x-for="n in [50,55,60,65,70,75,80,85,90,95,100]" :key="n">
                                    <option :value="n" x-text="n"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                </div>

                {{-- ══ STEP 3: DETAIL PRESTASI (Read Only Database + Score Row) ══ --}}
                <div x-show="obsStep === 3 && activePeserta?.has_achievement" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="p-5 border border-amber-200 bg-amber-50/30 rounded-2xl space-y-4">
                        <h4 class="text-xs font-black text-amber-900 uppercase tracking-wider flex items-center">
                            <i data-lucide="crown" class="size-4 mr-2 text-amber-600"></i> Rekam Jejak Prestasi Pendaftar[cite: 1]
                        </h4>
                        <p class="text-xs text-amber-800">Berikut adalah data detail prestasi yang diklaim peserta saat pendaftaran awal.</p>

                        <div class="space-y-3 mt-4">
                            <template x-for="(ach, idx) in activePeserta?.achievements" :key="idx">
                                <div class="rounded-xl border border-amber-200 overflow-hidden bg-white shadow-sm flex items-start gap-4 p-4">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-amber-100 shrink-0">
                                        <i data-lucide="award" class="size-5 text-amber-600"></i>
                                    </div>
                                    <div>
                                        <div class="flex gap-2 items-center mb-1">
                                            <span class="px-2 py-0.5 bg-amber-500 text-white rounded text-[10px] font-bold uppercase tracking-wide" x-text="ach.type_label"></span>
                                            <span x-show="ach.level_label" class="text-[11px] font-bold text-gray-600 border border-gray-200 px-1.5 rounded" x-text="ach.level_label"></span>
                                        </div>
                                        <p x-show="ach.position" class="text-sm font-black text-gray-800 mt-1" x-text="ach.position"></p>
                                        <p x-show="ach.ranks" class="text-xs text-gray-500 font-medium mt-1 font-mono leading-relaxed" x-text="ach.ranks"></p>
                                        <p x-show="!ach.level_label && !ach.position && !ach.ranks" class="text-xs text-gray-400 italic mt-1">Hanya melampirkan sertifikat umum.</p>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- SCORE ROW: Nilai Prestasi -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 mt-3 border-2 border-amber-300 bg-amber-50/40 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="trophy" class="size-4 text-amber-500"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-800">Nilai Prestasi</p>
                                    <p class="text-xs text-amber-700 mt-0.5">Masukkan nilai akhir penilaian prestasi siswa.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 w-full sm:w-auto shrink-0">
                                <select x-model="obsForm.prestasi_score"
                                    class="flex-1 sm:w-44 bg-white border-2 border-amber-300 rounded-xl px-3 py-2 text-sm font-bold text-amber-700 focus:outline-none focus:border-amber-500 transition-all">
                                    <option value="">Pilih Nilai Prestasi</option>
                                    <template x-for="n in [50,55,60,65,70,75,80,85,90,95,100]" :key="n">
                                        <option :value="n" x-text="n"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ══ STEP 4: KONFIRMASI FINAL (Disempurnakan) ══ --}}
                <div x-show="obsStep === obsSteps.length" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="p-5 border border-gray-200 bg-gray-50/30 rounded-2xl space-y-5">
                        <h4 class="text-sm font-black text-gray-900 uppercase tracking-wider border-b border-gray-200 pb-2">Konfirmasi Penilaian Akhir</h4>

                        <div class="space-y-3">
                            <div class="grid grid-cols-2 gap-3 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500 font-bold uppercase">Nilai Fisik</span>
                                    <span class="text-lg font-black text-blue-600 font-mono" x-text="obsForm.fisik_score || '0'"></span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500 font-bold uppercase">Nilai Ciri Khusus</span>
                                    <span class="text-lg font-black text-violet-600 font-mono" x-text="obsForm.ciri_score || '0'"></span>
                                </div>
                            </div>
                            <div class="flex justify-between items-center p-4 bg-gray-900 rounded-xl text-white shadow-md">
                                <div>
                                    <span class="font-bold text-sm tracking-wide block">Total Fisik & Ciri</span>
                                    <span class="text-xs text-gray-400">Rata-rata nilai kondisi fisik & ciri khusus</span>
                                </div>
                                <span class="text-2xl font-black text-rose-400 font-mono"
                                    x-text="calcTotalScore()">
                                </span>
                            </div>

                            <div x-show="activePeserta?.has_achievement"
                                class="flex justify-between items-center p-4 bg-amber-50 rounded-xl border border-amber-200 shadow-sm">
                                <div>
                                    <span class="font-bold text-sm tracking-wide block text-amber-900">Total Prestasi</span>
                                    <span class="text-xs text-amber-700">Skor nilai prestasi calon murid baru</span>
                                </div>
                                <span class="text-2xl font-black text-amber-600 font-mono" x-text="obsForm.prestasi_score || '0'"></span>
                            </div>
                        </div>

                        {{-- Peringatan Diskualifikasi --}}
                        <div x-show="isDisqualifiedCiri || isButaWarna"
                            class="rounded-xl border border-red-300 bg-red-50 p-4 flex items-start gap-3">
                            <i data-lucide="alert-triangle" class="size-5 text-red-600 shrink-0 mt-0.5"></i>
                            <div>
                                <p class="text-sm font-bold text-red-700">Peringatan Syarat Mutlak!</p>
                                <p class="text-xs text-red-600/80 mt-1">Siswa terindikasi memiliki TATO / TINDIK / BUTA WARNA yang menjadi parameter otomatis gugur / diskualifikasi. Harap sesuaikan status keputusan akhir menjadi TIDAK.</p>
                            </div>
                        </div>

                        <div x-effect="if(calcTotalScore() === 0) obsForm.observation_status = 'failed'">
                            <label class="block text-sm font-bold text-gray-800 mb-2">Keputusan Akhir Observasi</label>
                            <div class="grid grid-cols-2 gap-3">

                                <button type="button" @click="obsForm.observation_status = 'passed'"
                                    :disabled="calcTotalScore() === 0"
                                    :class="obsForm.observation_status === 'passed' ? 'bg-green-500 text-white border-green-500 shadow-md ring-2 ring-green-200' : 'bg-white text-gray-500 border-gray-200 hover:border-green-300 hover:text-green-600 disabled:opacity-50 disabled:bg-gray-100 disabled:cursor-not-allowed'"
                                    class="border-2 rounded-xl py-3 text-sm font-black transition-all flex flex-col items-center gap-1">
                                    <i data-lucide="check-circle" class="size-5"></i> JENJANG
                                </button>

                                <button type="button" @click="obsForm.observation_status = 'failed'"
                                    :class="obsForm.observation_status === 'failed' ? 'bg-red-500 text-white border-red-500 shadow-md ring-2 ring-red-200' : 'bg-white text-gray-500 border-gray-200 hover:border-red-300 hover:text-red-600'"
                                    class="border-2 rounded-xl py-3 text-sm font-black transition-all flex flex-col items-center gap-1">
                                    <i data-lucide="x-circle" class="size-5"></i> TIDAK
                                </button>

                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Catatan Ekstra Observer <span class="text-xs font-normal text-gray-400">(Opsional)</span></label>
                            <textarea x-model="obsForm.observation_notes" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all" rows="3" placeholder="Tuliskan keterangan tambahan bila ada..."></textarea>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ── BOTTOM NAVIGATION STICKY FOOTER MODAL ── -->
        <div class="px-6 py-5 bg-gray-50 border-t border-gray-100 flex items-center justify-between shrink-0">
            <button type="button"
                x-show="obsStep > 1"
                @click="obsPrev()"
                class="px-5 py-2.5 border border-gray-300 rounded-xl text-sm font-bold text-gray-600 hover:text-gray-900 hover:bg-gray-200 flex items-center gap-2 bg-white transition-all shadow-sm">
                <i data-lucide="arrow-left" class="size-4"></i> Kembali
            </button>
            <div x-show="obsStep === 1"></div>

            <div class="flex gap-2">
                <button type="button" x-show="obsStep < obsSteps.length" @click="obsNext()" class="px-6 py-2.5 bg-gradient-to-r from-rose-600 to-orange-400 text-white text-sm font-bold rounded-xl shadow-md hover:opacity-90 flex items-center gap-2 transition-all">
                    Lanjut <i data-lucide="arrow-right" class="size-4"></i>
                </button>

                <button type="button" @click="submitObservasi()" x-show="obsStep === obsSteps.length" :disabled="obsLoading" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 disabled:opacity-70 text-white shadow-md cursor-pointer text-sm font-bold rounded-xl transition-all flex items-center gap-2">
                    <template x-if="!obsLoading">
                        <i data-lucide="save" class="size-4"></i>
                    </template>
                    <template x-if="obsLoading">
                        <i data-lucide="loader-2" class="size-4 animate-spin"></i>
                    </template>
                    <span x-text="obsLoading ? 'Menyimpan...' : 'Simpan Data'"></span>
                </button>
            </div>
        </div>

    </div>
</div>