{{--
    PARTIAL: pendaftar/step-6-konfirmasi.blade.php
--}}

{{--
    PERBAIKAN BUG: x-data dipindah ke <div> wrapper terluar.
    Sebelumnya x-data ada di <form>, sehingga <div x-show="errorMsg"> yang berada
    DI LUAR <form> tidak punya akses ke scope Alpine → Alpine melempar error diam-diam
    → submitForm() tidak dieksekusi sama sekali → modal seolah-olah menutup tanpa log.
--}}
<div x-data="{
        adminVerify1: false,
        adminVerify2: false,
        submitting: false,
        errorMsg: '',
        async submitForm() {
            this.submitting = true;
            this.errorMsg   = '';
            const form = document.getElementById('step6-form');
            const data = new FormData(form);
            
            @if($isEdit)
            data.append('_method', 'PUT');
            @endif
            
            try {
                const res  = await fetch('{{ $postUrl }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: data
                });
                
                const json = await res.json();
                console.log('[store] response:', json);
                
                if (json.success) {
                    // 1. Tutup Modal Wizard Form Secara Halus
                    this.$dispatch('close-modal');

                    // 2. Beri jeda 300ms agar animasi form tertutup selesai, 
                    //    baru pancarkan event untuk membuka modal sukses khusus
                    setTimeout(() => {
                        window.dispatchEvent(new CustomEvent('show-success-registration', {
                            detail: json.data
                        }));
                    }, 300);
                    
                    // (Bagian HTMX di sini SUDAH DIHAPUS, dipindah ke data-peserta.blade.php)
                    
                } else {
                    // Mengolah error agar lebih mudah dibaca
                    if (json.errors) {
                        this.errorMsg = Object.values(json.errors)
                            .map(err => Array.isArray(err) ? err[0] : err)
                            .join(' | ');
                    } else {
                        this.errorMsg = json.message || 'Terjadi kesalahan sistem.';
                    }
                }
            } catch (e) {
                this.errorMsg = 'Terjadi kesalahan koneksi: ' + e.message;
            } finally {
                this.submitting = false;
            }
        }
    }">

    {{-- Error message — sekarang benar-benar dalam scope x-data yang sama --}}
    <div x-show="errorMsg"
        x-cloak
        class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-xs shadow-sm">
        <div class="flex items-start gap-2">
            <i data-lucide="alert-circle" class="size-4 shrink-0 mt-0.5"></i>
            <div>
                <p class="font-bold mb-1">Terjadi Kesalahan:</p>
                <div x-html="errorMsg.replace(/\|/g, '<br>')"></div>
            </div>
        </div>
    </div>

    <form id="step6-form"
        class="flex flex-col max-h-[70vh]">
        @csrf

        {{-- AREA SCROLL KONTEN --}}
        <div class="flex-1 overflow-y-auto pr-2 pb-4 space-y-5">

            {{-- ── Hidden: seluruh data dari step sebelumnya (Otomatis dari Controller) ── --}}
            @foreach ($hiddenFields as $field)
            <input type="hidden" name="{{ $field }}" value="{{ request($field) }}">
            @endforeach

            @php
            // Menerjemahkan UUID yang dikirim dari form sebelumnya menjadi Nama Asli
            $pil1Name = $jurusanList->firstWhere('id', request('pil1'))?->name ?? '—';
            $pil2Name = $jurusanList->firstWhere('id', request('pil2'))?->name ?? '—';
            $pil3Name = $jurusanList->firstWhere('id', request('pil3'))?->name ?? '—';

            $jalurName = $jalurList->firstWhere('id', request('jalur'))?->name ?? '—';
            @endphp

            {{-- ── Resume Data ── --}}
            <div class="border border-gray-200 rounded-xl bg-gray-50/60 p-4 grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                <div>
                    <span class="text-gray-400 block mb-0.5">Kode Registrasi Berkas</span>
                    <strong class="font-mono text-sm text-rose-600">{{ request('reg_number', '—') }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block mb-0.5">Nama Calon Siswa</span>
                    <strong class="text-gray-800 text-sm">{{ request('full_name', '—') }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block mb-0.5">Jenis Kelamin</span>
                    <strong class="text-gray-800">{{ request('gender') === 'L' ? 'Laki-laki' : (request('gender') === 'P' ? 'Perempuan' : '—') }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block mb-0.5">NISN</span>
                    <strong class="text-gray-800 font-mono">{{ request('nisn', '—') }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block mb-0.5">Sekolah Asal</span>
                    <strong class="text-gray-800">{{ request('school_origin', '—') }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block mb-0.5">WhatsApp Aktif</span>
                    <strong class="text-gray-800">{{ request('phone', '—') }}</strong>
                </div>

                {{-- Hasil Terjemahan Jalur & Jurusan --}}
                <div>
                    <span class="text-gray-400 block mb-0.5">Jalur Penerimaan</span>
                    <strong class="text-gray-900">{{ $jalurName }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block mb-0.5">Jurusan Utama (Pilihan 1)</span>
                    <strong class="text-gray-900">{{ $pil1Name }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block mb-0.5">Pilihan Cadangan 2</span>
                    <strong class="text-gray-900">{{ $pil2Name }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block mb-0.5">Pilihan Cadangan 3</span>
                    <strong class="text-gray-900">{{ $pil3Name }}</strong>
                </div>

                <div>
                    <span class="text-gray-400 block mb-0.5">Rata-rata Rapor</span>
                    <strong class="text-gray-900">{{ request('rata_rapor') ?: '—' }}</strong>
                </div>
                <div>
                    <span class="text-gray-400 block mb-0.5">Rata-rata TKA</span>
                    <strong class="text-gray-900">{{ request('rata_tka') ?: '—' }}</strong>
                </div>

                {{-- Ringkasan Berkas Dinamis --}}
                <div class="col-span-2">
                    <span class="text-gray-400 block mb-1">Berkas Fisik Diterima</span>
                    <div class="flex flex-wrap gap-1.5">
                        @php $adaBerkas = false; @endphp

                        @foreach($berkasList as $berkas)
                        @php $field = 'berkas_' . str_replace('-', '_', $berkas->slug); @endphp

                        @if(request($field))
                        @php $adaBerkas = true; @endphp
                        <span class="px-2 py-0.5 bg-green-50 text-green-700 border border-green-200 rounded text-[10px] font-semibold">
                            ✓ {{ $berkas->name }}
                        </span>
                        @endif
                        @endforeach

                        @if(!$adaBerkas)
                        <span class="text-gray-400 text-xs italic">Tidak ada berkas yang dicentang</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── Pernyataan Integritas Admin ── --}}
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 space-y-3 text-xs text-blue-950">
                <p class="text-xs font-black text-blue-900 flex items-center gap-1.5">
                    <i data-lucide="shield-check" class="size-3.5"></i>
                    Pernyataan Integritas Verifikator Loket
                </p>

                <label class="flex items-start gap-3 cursor-pointer group">
                    {{-- Checkbox visual untuk UX, tidak dikirim langsung --}}
                    <input type="checkbox"
                        @change="adminVerify1 = $event.target.checked"
                        :checked="adminVerify1"
                        class="mt-0.5 w-4 h-4 accent-green-600 rounded shrink-0">
                    {{-- Hidden input yang selalu ada di FormData dengan nilai yang benar --}}
                    <input type="hidden" name="admin_verify1" :value="adminVerify1 ? '1' : ''">
                    <span class="leading-relaxed">
                        Saya selaku petugas panitia menyatakan telah memeriksa seluruh berkas dan mengonfirmasi
                        hasil input instrumen data di atas <strong>sesuai dokumen asli</strong>.
                    </span>
                </label>

                <label class="flex items-start gap-3 cursor-pointer group">
                    <input type="checkbox"
                        @change="adminVerify2 = $event.target.checked"
                        :checked="adminVerify2"
                        class="mt-0.5 w-4 h-4 accent-green-600 rounded shrink-0">
                    <input type="hidden" name="admin_verify2" :value="adminVerify2 ? '1' : ''">
                    <span class="leading-relaxed">
                        Saya bertanggung jawab penuh atas keabsahan entri berkas masuk ini ke dalam
                        database utama pendaftaran sekolah.
                    </span>
                </label>
            </div>

        </div>
        {{-- AKHIR AREA SCROLL KONTEN --}}

        {{-- ── Navigasi (Fixed Footer) ── --}}
        <div class="flex-none pt-4 mt-2 bg-white border-t border-gray-200 flex items-center justify-between">
            <button type="button"
                class="px-5 py-2.5 border border-blue-200 rounded-xl text-sm font-bold text-blue-700 hover:bg-blue-100 flex items-center gap-2 bg-white transition-all"
                hx-get="{{ $stepUrls[5] }}"
                hx-target="#modal-body"
                hx-swap="innerHTML"
                hx-include="#step6-form [name]"
                @htmx:before-request="$dispatch('modal-step', { step: 5 })">
                <i data-lucide="arrow-left" class="size-4"></i> Kembali
            </button>

            <button type="button"
                @click="submitForm()"
                :disabled="!adminVerify1 || !adminVerify2 || submitting"
                :class="(adminVerify1 && adminVerify2 && !submitting)
                        ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-md cursor-pointer'
                        : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                class="px-6 py-2.5 text-sm font-bold rounded-xl transition-all flex items-center gap-2">
                <template x-if="!submitting">
                    <span class="flex items-center gap-2"><i data-lucide="save" class="size-4"></i> Simpan Data Pendaftar</span>
                </template>
                <template x-if="submitting">
                    <span class="flex items-center gap-2"><i data-lucide="loader-circle" class="size-4 animate-spin"></i> Menyimpan...</span>
                </template>
            </button>
        </div>

    </form>

</div>{{-- penutup wrapper x-data --}}