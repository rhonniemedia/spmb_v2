<!-- STEP 5 — PAS FOTO -->

{{--
|==========================================================================
| STEP 5 — UPLOAD PAS FOTO
|==========================================================================
| Perubahan (dari versi sebelumnya):
|   + Error validasi: ditangkap dari JSON 422 HTMX via @htmx:after-request
|   + Error box merah muncul di atas area upload saat gagal validasi
|   + Area upload/preview mendapat border merah saat error photo
|==========================================================================
--}}

<div x-show="step === 5"
    x-data="{
        fotoFile: null,
        fotoPreview: null,
        existingPhoto: {{ $personalData?->photo ? 'true' : 'false' }},
        errors5: {},
        hasError5: false,

        handleFoto(e) {
            const file = e.target.files[0];
            if (!file) return;
            this.fotoFile = file.name;
            this.existingPhoto = false;
            /* Reset error foto saat user memilih file baru */
            this.errors5 = {};
            this.hasError5 = false;
            const reader = new FileReader();
            reader.onload = (ev) => { this.fotoPreview = ev.target.result; };
            reader.readAsDataURL(file);
        },

        setErrors5(xhr) {
            if (xhr.status === 422) {
                try {
                    const body = JSON.parse(xhr.response);
                    this.errors5   = body.errors ?? {};
                    this.hasError5 = Object.keys(this.errors5).length > 0;
                } catch(e) {
                    this.errors5   = {};
                    this.hasError5 = false;
                }
            } else {
                this.errors5   = {};
                this.hasError5 = false;
            }
        },

        err5(field) {
            return this.errors5[field]?.[0] ?? null;
        }
    }"
    x-init="
        @if($personalData?->photo)
            fotoPreview = '{{ \Storage::url($personalData->photo) }}';
            fotoFile = '{{ basename($personalData->photo) }}';
        @endif
    "
    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">

    {{-- Header --}}
    <div class="px-8 pt-6 border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-violet-50 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-camera text-violet-600 text-xl"></i>
        </div>
        <div>
            <h2 class="text-lg font-black text-[#080C1A]">Upload Pas Foto</h2>
            <p class="text-sm text-[#6A7686]">Pas foto terbaru ukuran 3×4 atau 4×6, format JPG/PNG</p>
        </div>
    </div>

    <div class="px-8 py-8 space-y-6">

        {{-- Info box --}}
        <div class="flex gap-3 items-start bg-blue-50 border border-blue-200 rounded-2xl px-4 py-3.5">
            <i class="fa-solid fa-circle-info text-blue-500 text-base mt-0.5 flex-shrink-0"></i>
            <p class="text-sm font-medium text-blue-800 leading-relaxed">
                Foto harus berupa foto formal terbaru (maks. 6 bulan terakhir), dengan latar belakang <strong>merah atau biru</strong>.
                Wajah terlihat jelas, tidak memakai kacamata atau topi.
            </p>
        </div>

        {{-- ── ERROR SUMMARY BOX ─────────────────────────────────────── --}}
        {{-- Muncul saat server tolak file (ukuran, format, wajib, dll) --}}
        <div x-show="hasError5"
            x-transition
            class="flex gap-3 items-start bg-red-50 border border-red-200 rounded-2xl px-4 py-3.5">
            <i class="fa-solid fa-circle-exclamation text-red-500 text-base mt-0.5 flex-shrink-0"></i>
            <div>
                <p class="text-sm font-black text-red-700 mb-1">Upload foto gagal:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    <template x-for="(msgs, field) in errors5" :key="field">
                        <template x-for="msg in msgs" :key="msg">
                            <li class="text-sm text-red-600" x-text="msg"></li>
                        </template>
                    </template>
                </ul>
            </div>
        </div>

        {{-- Ketentuan pas foto --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach([
            ['icon' => 'fa-image', 'label' => 'Format', 'desc' => 'JPG atau PNG'],
            ['icon' => 'fa-weight-hanging', 'label' => 'Ukuran File', 'desc' => 'Maks. 1 MB'],
            ['icon' => 'fa-expand', 'label' => 'Dimensi', 'desc' => '3×4 atau 4×6 cm'],
            ['icon' => 'fa-palette', 'label' => 'Latar', 'desc' => 'Merah / Biru'],
            ] as $item)
            <div class="flex flex-col items-center gap-2 p-4 bg-gray-50 rounded-2xl border border-gray-200 text-center">
                <div class="w-10 h-10 rounded-full bg-violet-100 flex items-center justify-center">
                    <i class="fa-solid {{ $item['icon'] }} text-violet-600 text-base"></i>
                </div>
                <span class="text-[12px] font-bold text-[#080C1A]">{{ $item['label'] }}</span>
                <span class="text-[12px] text-[#6A7686]">{{ $item['desc'] }}</span>
            </div>
            @endforeach
        </div>

        {{-- Upload area --}}
        <div class="space-y-3">
            <label class="text-[13px] font-black text-[#6A7686] uppercase tracking-wide">
                Pas Foto Terbaru <span class="text-primary">*</span>
            </label>

            {{-- Empty state: belum ada foto --}}
            <div x-show="!fotoPreview"
                @click="$refs.inputFoto.click()"
                :class="err5('photo') ? 'border-red-400 bg-red-50/40 hover:border-red-500' : 'border-gray-200 hover:border-violet-400 hover:bg-violet-50/50'"
                class="border-2 border-dashed rounded-2xl p-10 text-center cursor-pointer transition-all group">
                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 transition-colors"
                    :class="err5('photo') ? 'bg-red-100' : 'bg-gray-100 group-hover:bg-violet-100'">
                    <i class="fa-solid fa-camera text-2xl transition-colors"
                        :class="err5('photo') ? 'text-red-400' : 'text-gray-300 group-hover:text-violet-500'"></i>
                </div>
                <p class="text-[15px] font-bold text-[#080C1A] mb-1">Klik untuk memilih foto</p>
                <p class="text-sm text-[#6A7686]">atau seret & lepas file ke sini</p>
                <p class="text-[12px] text-[#6A7686] mt-2 font-semibold">JPG, PNG — Maksimal 1 MB</p>

                {{-- Pesan error inline di dalam empty state --}}
                <p class="text-[12px] text-red-600 font-bold mt-3 flex items-center justify-center gap-1.5"
                    x-show="err5('photo')">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span x-text="err5('photo')"></span>
                </p>
            </div>

            {{-- Preview state --}}
            <div x-show="fotoPreview" class="relative">
                <div class="flex flex-col sm:flex-row items-center gap-6 p-6 rounded-2xl border-2 transition-colors"
                    :class="err5('photo') ? 'bg-red-50 border-red-300' : 'bg-violet-50 border-violet-200'">
                    <div class="flex-shrink-0">
                        <img :src="fotoPreview" alt="Preview Pas Foto"
                            class="w-[100px] h-[130px] object-cover rounded-xl shadow-md border-2"
                            :class="err5('photo') ? 'border-red-300' : 'border-violet-300'">
                    </div>
                    <div class="flex-1 text-center sm:text-left">

                        {{-- Badge: foto lama dari DB --}}
                        <template x-if="existingPhoto && !err5('photo')">
                            <div class="flex items-center justify-center sm:justify-start gap-2 mb-2">
                                <i class="fa-solid fa-circle-check text-blue-500 text-lg"></i>
                                <span class="text-sm font-black text-blue-700">Foto tersimpan dari sesi sebelumnya</span>
                            </div>
                        </template>

                        {{-- Badge: foto baru dipilih --}}
                        <template x-if="!existingPhoto && !err5('photo')">
                            <div class="flex items-center justify-center sm:justify-start gap-2 mb-2">
                                <i class="fa-solid fa-circle-check text-green-500 text-lg"></i>
                                <span class="text-sm font-black text-green-700">Foto berhasil dipilih</span>
                            </div>
                        </template>

                        {{-- Badge: error dari server (ukuran/format salah) --}}
                        <template x-if="err5('photo')">
                            <div class="flex items-center justify-center sm:justify-start gap-2 mb-2">
                                <i class="fa-solid fa-circle-xmark text-red-500 text-lg"></i>
                                <span class="text-sm font-black text-red-700" x-text="err5('photo')"></span>
                            </div>
                        </template>

                        <p class="text-sm font-semibold text-[#080C1A] truncate max-w-[200px]" x-text="fotoFile"></p>
                        <p class="text-[13px] text-[#6A7686] mt-1">Pastikan wajah terlihat jelas dan foto sesuai ketentuan</p>

                        <button type="button"
                            @click="fotoFile = null; fotoPreview = null; existingPhoto = false; errors5 = {}; hasError5 = false; $refs.inputFoto.value = ''"
                            class="mt-3 inline-flex items-center gap-1.5 px-4 py-2 text-[13px] font-bold text-red-600 border border-red-200 rounded-full hover:bg-red-50 transition-all">
                            <i class="fa-solid fa-trash-alt text-xs"></i> Ganti Foto
                        </button>
                    </div>
                </div>
            </div>

            {{-- Input file tersembunyi --}}
            <input type="file" x-ref="inputFoto" name="photo"
                accept=".jpg,.jpeg,.png"
                class="hidden"
                @change="handleFoto($event)">

        </div>
    </div>

    {{-- ── Footer Navigasi Step 5 ──────────────────────────────────────── --}}
    <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50 flex items-center justify-between rounded-b-[20px]">
        <button type="button" @click="step--"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </button>
        <div class="flex items-center gap-3">
            <button type="button"
                hx-post="{{ route('biodata.step5') }}"
                hx-indicator="#biodata-form"
                hx-encoding="multipart/form-data"
                hx-swap="none"
                @htmx:after-request="
                    const xhr = $event.detail.xhr;
                    if (xhr.status === 422) {
                        setErrors5(xhr);   // panggil helper, biarkan Alpine urus scope
                    } else if (xhr.status === 200) {
                        setErrors5(xhr);
                        let r = JSON.parse(xhr.response);
                        if (r.success) step = r.step;
                    }
                "
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-full hover:bg-primary-hover hover:-translate-y-px transition-all shadow-lg shadow-primary/30">
                <span hx-dis-indicator>
                    Berikutnya <i class="fa-solid fa-arrow-right"></i>
                </span>
                <span id="next-indicator" class="htmx-indicator gap-2">
                    <i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...
                </span>
            </button>
        </div>
    </div>

</div>{{-- /step 5 --}}