{{-- _step_zonasi_jarak.blade.php --}}
{{--
|==========================================================================
| STEP ZONASI — VERIFIKASI JARAK DOMISILI (PINPOINT MAPS VERSION)
|==========================================================================
| Hanya muncul jika jalur === 'zonasi' (dikontrol stepMap di parent).
|
| Alur Baru:
|   1. Peta dimuat langsung sejak awal (default terpusat di koordinat sekolah).
|   2. User menggeser pin merah-bergelombang atau klik peta tepat di atap rumah mereka.
|   3. Koordinat ditangkap otomatis ke input hidden `rumah_lat` dan `rumah_lng`.
|   4. Tersedia tombol GPS Perangkat untuk mendeteksi lokasi instan via HP/Gawai.
|   5. Tombol "Hitung Jarak" mengirim koordinat via HTMX ke backend.
|   6. Backend menghitung Haversine Formula (server-side, PHP) secara gratis & instan.
|   7. Response HTML memuat info jarak, status kelolosan, dan menarik garis putus-putus.
|==========================================================================
--}}

{{-- Tambahkan x-data dan listener event di tag pembungkus utama ini --}}
<div x-show="currentStepId === 'zonasi_jarak'"
    x-data="{ 
        jarakSudahDicek: false, 
        sedangCek: false, 
        alamatManual: false 
    }"
    @jarak-dihitung.window="jarakSudahDicek = true"
    x-effect="if (currentStepId === 'zonasi_jarak') { 
        setTimeout(() => { 
            if (!mapZonasiUtama) { 
                initInstanPetaZonasi(); 
            } else {
                mapZonasiUtama.invalidateSize(); 
            }
        }, 200); 
    }"
    class="bg-white border border-gray-200 rounded-[20px] shadow-sm overflow-hidden">

    {{-- ── HEADER ── --}}
    <div class="px-8 pt-6 pb-5 border-b border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-location-dot text-emerald-600 text-xl"></i>
        </div>
        <div>
            <h2 class="text-lg font-black text-[#080C1A]">Verifikasi Jarak Domisili</h2>
            <p class="text-sm text-[#6A7686]">Tentukan posisi koordinat rumah Anda pada peta untuk menghitung jarak akurat</p>
        </div>
    </div>

    <div class="px-8 py-7 space-y-6">

        {{-- Info metode kalkulasi --}}
        <div class="flex gap-3 items-start bg-emerald-50 border border-emerald-200 rounded-2xl px-4 py-3.5">
            <i class="fa-solid fa-circle-info text-emerald-600 text-base mt-0.5 flex-shrink-0"></i>
            <p class="text-sm font-medium text-emerald-900 leading-relaxed">
                Jarak dihitung menggunakan <strong>metode titik koordinat geospasial (pinpoint)</strong> langsung dari atap rumah Anda menuju sekolah. Pastikan posisi pin diletakkan seakurat mungkin sesuai alamat Kartu Keluarga (KK).
            </p>
        </div>

        {{-- Form Scope Pembungkus Kiriman Data --}}
        @csrf

        {{-- ── INPUT COORDINATE HIDDEN (DIKIRIM KE BACKEND) ── --}}
        <input type="hidden" name="rumah_lat" id="input-rumah-lat">
        <input type="hidden" name="rumah_lng" id="input-rumah-lng">

        <div class="space-y-6">
            {{-- ── BAGIAN ALAMAT TEKS (UNTUK REKAM ARSIP DATA) ── --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-md bg-emerald-500 flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-[10px] font-black">A</span>
                        </div>
                        <h3 class="text-[15px] font-black text-[#080C1A]">Informasi Alamat Tertulis</h3>
                    </div>
                    <button type="button"
                        @click="alamatManual = !alamatManual"
                        class="text-[12px] font-bold text-emerald-600 hover:text-emerald-800 transition-colors flex items-center gap-1.5">
                        <i class="fa-solid fa-pen-to-square text-[11px]"></i>
                        <span x-text="alamatManual ? 'Gunakan data biodata' : 'Ubah alamat manual'"></span>
                    </button>
                </div>

                {{-- Alamat otomatis dari biodata --}}
                <div x-show="!alamatManual" class="border border-gray-200 rounded-2xl overflow-hidden">
                    <div class="px-5 py-3 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                        <i class="fa-solid fa-house text-[#6A7686] text-[12px]"></i>
                        <span class="text-[12px] font-black text-[#6A7686] uppercase tracking-widest">Dari Data Biodata</span>
                        <span class="ml-auto inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-[10px] font-black">
                            <i class="fa-solid fa-lock text-[8px]"></i> Terisi otomatis
                        </span>
                    </div>
                    <div class="px-5 py-4 space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <div class="text-[11px] font-black uppercase tracking-widest text-[#6A7686] mb-1">Jalan / Nomor Rumah</div>
                                <div class="text-[14px] font-bold text-[#080C1A]">{{ $peserta->alamat_jalan ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-[11px] font-black uppercase tracking-widest text-[#6A7686] mb-1">RT / RW</div>
                                <div class="text-[14px] font-bold text-[#080C1A]">RT {{ $peserta->rt ?? '—' }} / RW {{ $peserta->rw ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-[11px] font-black uppercase tracking-widest text-[#6A7686] mb-1">Kelurahan</div>
                                <div class="text-[14px] font-bold text-[#080C1A]">{{ $peserta->kelurahan ?? '—' }}</div>
                            </div>
                            <div>
                                <div class="text-[11px] font-black uppercase tracking-widest text-[#6A7686] mb-1">Kecamatan</div>
                                <div class="text-[14px] font-bold text-[#080C1A]">{{ $peserta->kecamatan ?? '—' }}</div>
                            </div>
                        </div>
                        <input type="hidden" name="alamat_jalan" value="{{ $peserta->alamat_jalan ?? '' }}">
                        <input type="hidden" name="kelurahan" value="{{ $peserta->kelurahan ?? '' }}">
                        <input type="hidden" name="kecamatan" value="{{ $peserta->kecamatan ?? '' }}">
                        <input type="hidden" name="kota" value="{{ $peserta->kota ?? '' }}">
                    </div>
                </div>

                {{-- Input manual jika diubah --}}
                <div x-show="alamatManual"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="border border-amber-200 rounded-2xl overflow-hidden">
                    <div class="px-5 py-3 bg-amber-50 border-b border-amber-100 flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-amber-500 text-[12px]"></i>
                        <span class="text-[12px] font-black text-amber-700 uppercase tracking-widest">Input Manual</span>
                        <span class="ml-auto text-[11px] text-amber-600 font-medium">Pastikan sesuai dengan KK</span>
                    </div>
                    <div class="px-5 py-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-[12px] font-black uppercase tracking-widest text-[#6A7686] mb-2">Jalan / Nomor Rumah</label>
                            <input type="text" name="alamat_jalan" placeholder="cth: Jl. Merdeka No. 12"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-[14px] text-[#080C1A] focus:outline-none focus:ring-2 focus:ring-emerald-400/30 focus:border-emerald-400 transition-all">
                        </div>
                        <div>
                            <label class="block text-[12px] font-black uppercase tracking-widest text-[#6A7686] mb-2">Kelurahan</label>
                            <input type="text" name="kelurahan" placeholder="cth: Sukarami"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-[14px] text-[#080C1A] focus:outline-none focus:ring-2 focus:ring-emerald-400/30 focus:border-emerald-400 transition-all">
                        </div>
                        <div>
                            <label class="block text-[12px] font-black uppercase tracking-widest text-[#6A7686] mb-2">Kecamatan</label>
                            <input type="text" name="kecamatan" placeholder="cth: Ilir Barat I"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-[14px] text-[#080C1A] focus:outline-none focus:ring-2 focus:ring-emerald-400/30 focus:border-emerald-400 transition-all">
                        </div>
                        <div>
                            <label class="block text-[12px] font-black uppercase tracking-widest text-[#6A7686] mb-2">Kota / Kabupaten</label>
                            <input type="text" name="kota" placeholder="cth: Palembang"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-[14px] text-[#080C1A] focus:outline-none focus:ring-2 focus:ring-emerald-400/30 focus:border-emerald-400 transition-all">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── BAGIAN PINPOINT PETA (MUNCUL SEJAK AWAL) ── --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-md bg-emerald-500 flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-[10px] font-black">B</span>
                        </div>
                        <h3 class="text-[15px] font-black text-[#080C1A]">Tandai Lokasi Rumah di Peta</h3>
                    </div>

                    {{-- Tombol GPS Pintar Perangkat --}}
                    <button type="button"
                        onclick="panggilGPSOtomatis()"
                        class="text-[12px] bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-xl border border-emerald-200 font-bold hover:bg-emerald-100 transition-all flex items-center gap-1.5 shadow-sm">
                        <i class="fa-solid fa-location-crosshairs text-emerald-600 animate-pulse"></i> Gunakan GPS Perangkat Saya
                    </button>
                </div>

                {{-- Canvas Utama Peta --}}
                <div id="peta-interaktif-zonasi" class="h-128 w-full rounded-2xl border border-gray-200" style="z-index: 10; position: relative;"></div>

                <p class="text-[11px] text-amber-600 font-semibold flex items-start gap-1">
                    <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
                    <span>Petunjuk: Geser lingkaran hijau penanda rumah atau klik titik manapun di peta untuk menentukan letak tepat atap rumah Anda.</span>
                </p>
            </div>

            {{-- ── TOMBOL ACTIONS HITUNG ── --}}
            <button type="button"
                hx-post="{{ route('registration.zonasi.hitung') }}"
                hx-include="[name='rumah_lat'], [name='rumah_lng'], [name='alamat_jalan'], [name='kelurahan'], [name='kecamatan'], [name='kota']"
                hx-target="#hasil-jarak"
                hx-swap="innerHTML"
                hx-on::before-request="sedangCek = true"
                hx-on::after-request="sedangCek = false"
                :disabled="sedangCek"
                class="w-full inline-flex items-center justify-center gap-2.5 px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[14px] font-black rounded-2xl transition-all shadow-lg shadow-emerald-500/25 hover:-translate-y-px disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:translate-y-0">
                <span x-show="!sedangCek" class="inline-flex items-center gap-2.5">
                    <i class="fa-solid fa-calculator text-[13px]"></i>
                    Kalkulasi Jarak Sekarang
                </span>
                <span x-show="sedangCek" class="inline-flex items-center gap-2.5" x-cloak>
                    <i class="fa-solid fa-circle-notch fa-spin text-[13px]"></i>
                    Memproses Perhitungan & Mengunci Koordinat...
                </span>
            </button>
        </div>

        {{-- ── KONTEN DYNAMIC RESPONSE DARI BACKEND ── --}}
        <div id="hasil-jarak">
            {{-- Bagian ini akan di-swap oleh HTMX membawa data jarak, label zona, dan pemicu garis polyline --}}
        </div>

    </div>

    {{-- ── FOOTER NAVIGATION BAR ── --}}
    <div class="px-8 py-5 border-t border-gray-200 bg-gray-50/50 flex items-center justify-between rounded-b-[20px]">
        <button type="button" @click="step--"
            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </button>

        <div class="flex items-center gap-3">
            <button type="button" onclick="saveDraft()"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-[#6A7686] border border-gray-200 rounded-full hover:border-[#080C1A] hover:text-[#080C1A] transition-all">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Draft
            </button>

            <button type="button"
                hx-post="{{ route('registration.step3') }}"
                hx-include="[name='rumah_lat'], [name='rumah_lng'], [name='alamat_jalan'], [name='kelurahan'], [name='kecamatan'], [name='kota']"
                hx-target="this"
                hx-swap="none"
                hx-on::after-request="
                    const res = JSON.parse(event.detail.xhr.responseText);
                    if (res.success) window.dispatchEvent(new CustomEvent('pindah-step', { detail: { nextStep: 'jurusan' } }))
                "
                :disabled="!jarakSudahDicek"
                :class="jarakSudahDicek ? 'bg-[#FF1443] hover:bg-[#D90F38] hover:-translate-y-px shadow-lg shadow-red-500/30 cursor-pointer' : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                class="inline-flex items-center gap-2 px-8 py-2.5 text-white text-sm font-black rounded-full transition-all">
                Lanjut <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Inisialisasi variabel global scope instansiasi peta Leaflet
    var mapZonasiUtama = null; // Set default null untuk pengecekan x-effect
    var markerRumahDinamis;
    var polylineRuteHubung = null;
    var markerSekolahDinamis = null;

    // Titik Default Awal: Ambil dari konfigurasi koordinat sekolah
    const latSekolahAwal = parseFloat("{{ config('sekolah.lat', -3.45678) }}");
    const lngSekolahAwal = parseFloat("{{ config('sekolah.lng', 102.34567) }}");

    // Hapus document.addEventListener("DOMContentLoaded") agar tidak dipaksa load saat element disembunyikan

    function initInstanPetaZonasi() {
        const mapContainer = document.getElementById('peta-interaktif-zonasi');
        if (!mapContainer || mapZonasiUtama !== null) return;

        // 1. Instansiasi Peta pusat awal di koordinat SMK tujuan
        mapZonasiUtama = L.map('peta-interaktif-zonasi', {
            zoomControl: true,
            scrollWheelZoom: false
        }).setView([latSekolahAwal, lngSekolahAwal], 15);

        // 2. Load Tile OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(mapZonasiUtama);

        // 3. Custom divIcon bergaya modern minimalis (Rumah = Hijau Emerald)
        const styleIkonRumah = L.divIcon({
            html: '<div style="background:#10B981;width:16px;height:16px;border-radius:50%;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.4)"></div>',
            className: '',
            iconAnchor: [8, 8],
        });

        // 4. Tambahkan Marker Rumah yang bersifat Draggable
        markerRumahDinamis = L.marker([latSekolahAwal, lngSekolahAwal], {
            draggable: true,
            icon: styleIkonRumah
        }).addTo(mapZonasiUtama);

        markerRumahDinamis.bindPopup('<div class="text-xs font-bold text-gray-800">Posisi Rumah Anda</div><div class="text-[10px] text-gray-500">Geser titik ini tepat di atas atap rumah domisili Anda sekarang.</div>').openPopup();

        // 5. Fungsi pengisi value koordinat mentah ke input tersembunyi
        function pasokKoordinatForm(lat, lng) {
            document.getElementById('input-rumah-lat').value = lat.toFixed(7);
            document.getElementById('input-rumah-lng').value = lng.toFixed(7);
        }

        // Jalankan pasok data koordinat awal saat halaman dirender pertama kali
        pasokKoordinatForm(latSekolahAwal, lngSekolahAwal);

        // 6. EVENT: Deteksi perpindahan ketika marker selesai digeser user
        markerRumahDinamis.on('dragend', function(e) {
            const titikBaru = markerRumahDinamis.getLatLng();
            pasokKoordinatForm(titikBaru.lat, titikBaru.lng);
            hapusGarisRuteLama();
        });

        // 7. EVENT: Geser marker otomatis ke titik manapun yang diklik pada peta
        mapZonasiUtama.on('click', function(e) {
            markerRumahDinamis.setLatLng(e.latlng);
            pasokKoordinatForm(e.latlng.lat, e.latlng.lng);
            hapusGarisRuteLama();
        });

        // Paksa hitung ulang dimensi peta setelah init sukses
        setTimeout(() => {
            mapZonasiUtama.invalidateSize();
        }, 100);
    }

    // Fungsi pembantu menghapus polyline
    function hapusGarisRuteLama() {
        if (polylineRuteHubung !== null) {
            mapZonasiUtama.removeLayer(polylineRuteHubung);
            polylineRuteHubung = null;
        }

        // Ambil elemen scope Alpine dan kembalikan state ke false karena posisi pin berubah
        const kepinganAlpine = document.querySelector('[x-show="currentStepId === \'zonasi_jarak\'"]');
        if (kepinganAlpine && kepinganAlpine.__x) {
            kepinganAlpine.__x.$data.jarakSudahDicek = false;
        }
    }

    // Fungsi mengambil koordinat sensor GPS dari perangkat
    function panggilGPSOtomatis() {
        if (!navigator.geolocation) {
            alert("Maaf, penjelajah web (browser) Anda belum mendukung deteksi GPS internal perangkat.");
            return;
        }

        navigator.geolocation.getCurrentPosition(function(position) {
            const gpsLat = position.coords.latitude;
            const gpsLng = position.coords.longitude;

            document.getElementById('input-rumah-lat').value = gpsLat.toFixed(7);
            document.getElementById('input-rumah-lng').value = gpsLng.toFixed(7);

            if (markerRumahDinamis && mapZonasiUtama) {
                markerRumahDinamis.setLatLng([gpsLat, gpsLng]);
                mapZonasiUtama.setView([gpsLat, gpsLng], 18);
                markerRumahDinamis.getPopup().setContent('<div class="text-xs font-bold text-emerald-700">Terdeteksi via GPS!</div><div class="text-[10px]">Silakan koreksi atau geser sedikit lagi jika posisi atap bangunan belum presisi.</div>').openPopup();
                hapusGarisRuteLama();
            }
        }, function(error) {
            alert("Gagal mengunci sinyal GPS. Silakan berikan izin akses lokasi pada gawai Anda atau tentukan manual dengan menggeser peta.");
        }, {
            enableHighAccuracy: true,
            timeout: 8000
        });
    }

    // EVENT HTMX: Tangkap pasca swap HTML respon kalkulasi dari backend
    document.body.addEventListener('htmx:afterSwap', function(e) {
        if (e.detail.target.id !== 'hasil-jarak') return;

        if (mapZonasiUtama && markerRumahDinamis) {
            const inputLatRes = parseFloat(document.getElementById('input-rumah-lat').value);
            const inputLngRes = parseFloat(document.getElementById('input-rumah-lng').value);

            hapusGarisRuteLama();

            const styleIkonSekolah = L.divIcon({
                html: '<div style="background:#FF1443;width:16px;height:16px;border-radius:50%;border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.4)"></div>',
                className: '',
                iconAnchor: [8, 8],
            });

            if (markerSekolahDinamis === null) {
                markerSekolahDinamis = L.marker([latSekolahAwal, lngSekolahAwal], {
                    icon: styleIkonSekolah
                }).addTo(mapZonasiUtama);
                markerSekolahDinamis.bindPopup('<b>{{ config("sekolah.nama", "SMK Negeri") }}</b>');
            }

            polylineRuteHubung = L.polyline([
                [inputLatRes, inputLngRes],
                [latSekolahAwal, lngSekolahAwal]
            ], {
                color: '#10B981',
                weight: 3,
                dashArray: '6, 8',
                opacity: 0.8
            }).addTo(mapZonasiUtama);

            mapZonasiUtama.fitBounds([
                [inputLatRes, inputLngRes],
                [latSekolahAwal, lngSekolahAwal]
            ], {
                padding: [40, 40]
            });
        }
    });

    document.body.addEventListener('htmx:afterSwap', function(e) {
        if (e.detail.target.id === 'hasil-jarak') {
            window.dispatchEvent(new CustomEvent('jarak-dihitung'));
        }
    });
</script>
@endpush