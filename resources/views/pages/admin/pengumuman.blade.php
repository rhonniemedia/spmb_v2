@extends('layouts.admin')

@section('title', 'Beranda')
@section('page_title', 'Beranda')
@section('page_subtitle', 'Beranda Admin')

@section('content')

<div class="flex-1 overflow-y-auto p-5 md:p-8 bg-white" x-data="pengumumanApp()">

    <!-- ── Header ── -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-foreground text-2xl md:text-3xl font-bold mb-1">Pengumuman</h1>
            <p class="text-secondary text-sm">Kelola dan publikasikan pengumuman kepada peserta SPMB.</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="flex items-center gap-2 px-4 py-3 ring-1 ring-border hover:ring-primary rounded-full text-foreground font-semibold text-sm transition-all duration-300 cursor-pointer bg-white">
                <i data-lucide="archive" class="w-4 h-4"></i>
                <span>Arsip</span>
            </button>
            <button @click="openModal()" class="flex items-center gap-2 px-4 py-3 bg-primary text-white rounded-full font-bold text-sm hover:bg-primary-hover transition-all duration-300 cursor-pointer">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Buat Pengumuman</span>
            </button>
        </div>
    </div>

    <!-- ── Stat Cards ── -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white">
            <div class="size-10 bg-primary/10 rounded-xl flex items-center justify-center">
                <i data-lucide="megaphone" class="size-5 text-primary"></i>
            </div>
            <div>
                <p class="font-bold text-3xl text-foreground">12</p>
                <p class="text-sm text-secondary mt-0.5">Total Pengumuman</p>
            </div>
        </div>
        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white">
            <div class="size-10 bg-success/10 rounded-xl flex items-center justify-center">
                <i data-lucide="send" class="size-5 text-success"></i>
            </div>
            <div>
                <p class="font-bold text-3xl text-foreground">8</p>
                <p class="text-sm text-secondary mt-0.5">Sudah Terkirim</p>
            </div>
        </div>
        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white">
            <div class="size-10 bg-warning/10 rounded-xl flex items-center justify-center">
                <i data-lucide="clock" class="size-5 text-warning"></i>
            </div>
            <div>
                <p class="font-bold text-3xl text-foreground">3</p>
                <p class="text-sm text-secondary mt-0.5">Terjadwal</p>
            </div>
        </div>
        <div class="flex flex-col rounded-2xl border border-border p-5 gap-3 bg-white">
            <div class="size-10 bg-muted rounded-xl flex items-center justify-center">
                <i data-lucide="file-text" class="size-5 text-secondary"></i>
            </div>
            <div>
                <p class="font-bold text-3xl text-foreground">1</p>
                <p class="text-sm text-secondary mt-0.5">Draft</p>
            </div>
        </div>
    </div>

    <!-- ── Filter & Search ── -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 mb-5">
        <!-- Tab filter -->
        <div class="flex items-center gap-1 p-1 bg-muted rounded-xl">
            <button @click="activeTab='semua'"
                :class="activeTab==='semua' ? 'bg-white text-foreground shadow-sm font-bold' : 'text-secondary hover:text-foreground'"
                class="px-4 py-1.5 rounded-lg text-sm font-medium transition-all cursor-pointer">Semua</button>
            <button @click="activeTab='terkirim'"
                :class="activeTab==='terkirim' ? 'bg-white text-foreground shadow-sm font-bold' : 'text-secondary hover:text-foreground'"
                class="px-4 py-1.5 rounded-lg text-sm font-medium transition-all cursor-pointer">Terkirim</button>
            <button @click="activeTab='terjadwal'"
                :class="activeTab==='terjadwal' ? 'bg-white text-foreground shadow-sm font-bold' : 'text-secondary hover:text-foreground'"
                class="px-4 py-1.5 rounded-lg text-sm font-medium transition-all cursor-pointer">Terjadwal</button>
            <button @click="activeTab='draft'"
                :class="activeTab==='draft' ? 'bg-white text-foreground shadow-sm font-bold' : 'text-secondary hover:text-foreground'"
                class="px-4 py-1.5 rounded-lg text-sm font-medium transition-all cursor-pointer">Draft</button>
        </div>

        <div class="flex items-center gap-2 sm:ml-auto">
            <div class="relative">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 size-4 text-secondary"></i>
                <input type="text" placeholder="Cari pengumuman..." x-model="search"
                    class="pl-9 pr-4 py-2 rounded-xl border border-border text-sm focus:ring-1 focus:ring-primary outline-none w-[200px] transition-all" />
            </div>
            <select x-model="filterTarget" class="py-2 pl-3 rounded-xl border border-border text-sm focus:ring-1 focus:ring-primary outline-none text-secondary">
                <option value="all">Semua Target</option>
                <option value="semua">Semua Peserta</option>
                <option value="pending">Peserta Pending</option>
                <option value="verified">Peserta Verified</option>
                <option value="rejected">Peserta Ditolak</option>
            </select>
        </div>
    </div>

    <!-- ── Daftar Pengumuman ── -->
    <div class="flex flex-col gap-4">

        <template x-for="item in filteredPengumuman" :key="item.id">
            <div class="flex flex-col rounded-2xl border border-border bg-white p-5 gap-4 hover:shadow-sm transition-all duration-200">
                <div class="flex items-start justify-between gap-4">
                    <!-- Icon + Judul -->
                    <div class="flex items-start gap-4 flex-1 min-w-0">
                        <div class="size-11 rounded-xl flex items-center justify-center shrink-0"
                            :class="{
                      'bg-primary/10': item.tipe === 'penting',
                      'bg-info/10': item.tipe === 'info',
                      'bg-success/10': item.tipe === 'jadwal',
                      'bg-warning/10': item.tipe === 'peringatan'
                    }">
                            <i :data-lucide="item.icon" class="size-5"
                                :class="{
                        'text-primary': item.tipe === 'penting',
                        'text-info': item.tipe === 'info',
                        'text-success': item.tipe === 'jadwal',
                        'text-warning': item.tipe === 'peringatan'
                      }"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <h3 class="font-bold text-foreground text-sm" x-text="item.judul"></h3>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold"
                                    :class="{
                          'bg-success/10 text-success-dark': item.status === 'terkirim',
                          'bg-warning/10 text-warning-dark': item.status === 'terjadwal',
                          'bg-muted text-secondary': item.status === 'draft'
                        }">
                                    <i :data-lucide="item.status === 'terkirim' ? 'check' : item.status === 'terjadwal' ? 'clock' : 'file'" class="size-3"></i>
                                    <span x-text="item.status === 'terkirim' ? 'Terkirim' : item.status === 'terjadwal' ? 'Terjadwal' : 'Draft'"></span>
                                </span>
                            </div>
                            <p class="text-sm text-secondary line-clamp-2" x-text="item.isi"></p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-1 shrink-0">
                        <button @click="editItem(item)" class="size-8 rounded-lg hover:bg-muted flex items-center justify-center text-secondary hover:text-primary transition-colors cursor-pointer" title="Edit">
                            <i data-lucide="pencil" class="size-4"></i>
                        </button>
                        <button @click="deleteItem(item.id)" class="size-8 rounded-lg hover:bg-error/10 flex items-center justify-center text-secondary hover:text-error transition-colors cursor-pointer" title="Hapus">
                            <i data-lucide="trash-2" class="size-4"></i>
                        </button>
                    </div>
                </div>

                <!-- Meta bawah -->
                <div class="flex items-center flex-wrap gap-3 pt-3 border-t border-border">
                    <div class="flex items-center gap-1.5 text-xs text-secondary">
                        <i data-lucide="users" class="size-3.5"></i>
                        <span>Target: </span><span class="font-semibold text-foreground" x-text="item.target"></span>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-secondary">
                        <i data-lucide="send" class="size-3.5"></i>
                        <span>Dikirim ke </span><span class="font-semibold text-foreground" x-text="item.jumlahPenerima + ' peserta'"></span>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-secondary">
                        <i data-lucide="calendar" class="size-3.5"></i>
                        <span x-text="item.status === 'terjadwal' ? 'Dijadwalkan: ' : 'Dikirim: '"></span>
                        <span class="font-semibold text-foreground" x-text="item.tanggal"></span>
                    </div>
                    <div class="flex items-center gap-1.5 text-xs text-secondary ml-auto flex-wrap justify-end gap-y-1">
                        <template x-for="ch in item.channel" :key="ch">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-muted text-xs font-semibold">
                                <i :data-lucide="ch === 'Email' ? 'mail' : ch === 'WhatsApp' ? 'message-circle' : 'bell'" class="size-3"></i>
                                <span x-text="ch"></span>
                            </span>
                        </template>
                    </div>
                </div>

                <!-- Preview button jika draft -->
                <div x-show="item.status === 'terjadwal'" class="flex items-center gap-2">
                    <button @click="kirimSekarang(item)" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary text-white text-xs font-bold hover:bg-primary-hover transition-colors cursor-pointer">
                        <i data-lucide="send" class="size-3.5"></i>Kirim Sekarang
                    </button>
                    <button class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-border text-xs font-semibold text-secondary hover:bg-muted transition-colors cursor-pointer">
                        <i data-lucide="pencil" class="size-3.5"></i>Edit Jadwal
                    </button>
                </div>

            </div>
        </template>

        <!-- Empty state -->
        <div x-show="filteredPengumuman.length === 0" class="flex flex-col items-center gap-3 text-secondary py-16">
            <i data-lucide="inbox" class="size-12 text-border"></i>
            <p class="font-semibold">Tidak ada pengumuman ditemukan</p>
            <p class="text-sm">Coba ubah filter atau buat pengumuman baru</p>
        </div>

    </div>

    <!-- ══ MODAL BUAT / EDIT PENGUMUMAN ══ -->
    <div x-show="modalOpen" @click.self="modalOpen = false"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-foreground/60 z-[200] flex items-center justify-center p-4" style="display:none">

        <div x-show="modalOpen"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
            class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden shadow-2xl">

            <!-- Header modal -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-border shrink-0">
                <div>
                    <h3 class="font-bold text-foreground" x-text="isEditing ? 'Edit Pengumuman' : 'Buat Pengumuman Baru'"></h3>
                    <p class="text-xs text-secondary mt-0.5">Isi form berikut untuk mempublikasikan pengumuman</p>
                </div>
                <button @click="modalOpen = false" class="size-9 rounded-xl bg-muted hover:bg-border flex items-center justify-center cursor-pointer transition-colors">
                    <i data-lucide="x" class="size-4 text-secondary"></i>
                </button>
            </div>

            <!-- Body modal -->
            <div class="overflow-y-auto flex-1 p-6 scrollbar-hide flex flex-col gap-5">

                <!-- Judul -->
                <div>
                    <label class="text-xs font-semibold text-secondary block mb-1.5">Judul Pengumuman <span class="text-primary">*</span></label>
                    <input type="text" x-model="form.judul" placeholder="Contoh: Pengumuman Jadwal Wawancara SPMB 2026"
                        class="w-full px-4 py-3 rounded-xl border border-border text-sm text-foreground focus:ring-1 focus:ring-primary outline-none transition-all" />
                </div>

                <!-- Isi -->
                <div>
                    <label class="text-xs font-semibold text-secondary block mb-1.5">Isi Pengumuman <span class="text-primary">*</span></label>
                    <textarea x-model="form.isi" rows="5" placeholder="Tulis isi pengumuman di sini..."
                        class="w-full px-4 py-3 rounded-xl border border-border text-sm text-foreground focus:ring-1 focus:ring-primary outline-none transition-all resize-none"></textarea>
                </div>

                <!-- Tipe + Target -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-secondary block mb-1.5">Tipe</label>
                        <select x-model="form.tipe" class="w-full px-4 py-3 rounded-xl border border-border text-sm text-foreground focus:ring-1 focus:ring-primary outline-none">
                            <option value="info">Informasi</option>
                            <option value="penting">Penting</option>
                            <option value="jadwal">Jadwal</option>
                            <option value="peringatan">Peringatan</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-secondary block mb-1.5">Target Penerima</label>
                        <select x-model="form.target" class="w-full px-4 py-3 rounded-xl border border-border text-sm text-foreground focus:ring-1 focus:ring-primary outline-none">
                            <option value="Semua Peserta">Semua Peserta</option>
                            <option value="Peserta Pending">Peserta Pending</option>
                            <option value="Peserta Verified">Peserta Verified</option>
                            <option value="Peserta Ditolak">Peserta Ditolak</option>
                        </select>
                    </div>
                </div>

                <!-- Channel -->
                <div>
                    <label class="text-xs font-semibold text-secondary block mb-2">Channel Pengiriman</label>
                    <div class="flex items-center gap-3 flex-wrap">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" x-model="form.channelEmail" class="rounded border-border accent-primary w-4 h-4" />
                            <span class="text-sm font-medium text-foreground flex items-center gap-1.5">
                                <i data-lucide="mail" class="size-4 text-secondary"></i>Email
                            </span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" x-model="form.channelWA" class="rounded border-border accent-primary w-4 h-4" />
                            <span class="text-sm font-medium text-foreground flex items-center gap-1.5">
                                <i data-lucide="message-circle" class="size-4 text-secondary"></i>WhatsApp
                            </span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" x-model="form.channelApp" class="rounded border-border accent-primary w-4 h-4" />
                            <span class="text-sm font-medium text-foreground flex items-center gap-1.5">
                                <i data-lucide="bell" class="size-4 text-secondary"></i>Notifikasi App
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Jadwalkan -->
                <div>
                    <label class="flex items-center gap-2 cursor-pointer mb-3">
                        <input type="checkbox" x-model="form.jadwalkan" class="rounded border-border accent-primary w-4 h-4" />
                        <span class="text-sm font-semibold text-foreground">Jadwalkan pengiriman</span>
                    </label>
                    <div x-show="form.jadwalkan" class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs text-secondary block mb-1.5">Tanggal</label>
                            <input type="date" x-model="form.tanggalKirim"
                                class="w-full px-4 py-3 rounded-xl border border-border text-sm text-foreground focus:ring-1 focus:ring-primary outline-none transition-all" />
                        </div>
                        <div>
                            <label class="text-xs text-secondary block mb-1.5">Waktu</label>
                            <input type="time" x-model="form.waktuKirim"
                                class="w-full px-4 py-3 rounded-xl border border-border text-sm text-foreground focus:ring-1 focus:ring-primary outline-none transition-all" />
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer modal -->
            <div class="flex items-center justify-between px-6 py-4 border-t border-border bg-muted/50 shrink-0 gap-3">
                <button @click="saveDraft()" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-border bg-white text-sm font-semibold text-secondary hover:bg-muted transition-colors cursor-pointer">
                    <i data-lucide="save" class="size-4"></i>Simpan Draft
                </button>
                <div class="flex items-center gap-2">
                    <button @click="modalOpen = false" class="px-4 py-2.5 rounded-xl border border-border bg-white text-sm font-semibold text-secondary hover:bg-muted transition-colors cursor-pointer">
                        Batal
                    </button>
                    <button @click="submitPengumuman()" class="flex items-center gap-2 px-5 py-2.5 rounded-xl bg-primary text-white text-sm font-bold hover:bg-primary-hover transition-colors cursor-pointer">
                        <i data-lucide="send" class="size-4"></i>
                        <span x-text="form.jadwalkan ? 'Jadwalkan' : 'Kirim Sekarang'"></span>
                    </button>
                </div>
            </div>

        </div>
    </div>
    <!-- /modal -->

    <!-- Toast -->
    <div id="toast-peng" class="fixed bottom-6 right-6 z-[400] flex items-center gap-3 px-4 py-3 rounded-xl text-white text-sm font-semibold shadow-xl transition-all duration-300 translate-y-20 opacity-0 pointer-events-none">
        <i data-lucide="circle-check" class="size-4 shrink-0"></i>
        <span id="toast-peng-msg">Berhasil</span>
    </div>

</div>

@push('scripts')
<script>
    function pengumumanApp() {
        return {
            activeTab: 'semua',
            search: '',
            filterTarget: 'all',
            modalOpen: false,
            isEditing: false,

            form: {
                judul: '',
                isi: '',
                tipe: 'info',
                target: 'Semua Peserta',
                channelEmail: true,
                channelWA: true,
                channelApp: false,
                jadwalkan: false,
                tanggalKirim: '',
                waktuKirim: ''
            },

            pengumumanData: [{
                    id: 1,
                    judul: 'Pengumuman Jadwal Wawancara SPMB 2026',
                    isi: 'Kepada seluruh peserta yang telah lolos seleksi administrasi, wawancara akan dilaksanakan pada 20–25 Januari 2026 di Ruang Aula SMK Negeri 1 Palembang. Harap membawa dokumen asli.',
                    tipe: 'jadwal',
                    icon: 'calendar',
                    status: 'terkirim',
                    target: 'Semua Peserta',
                    jumlahPenerima: 847,
                    tanggal: '10 Jan 2026 · 08.00',
                    channel: ['Email', 'WhatsApp']
                },
                {
                    id: 2,
                    judul: 'Peringatan: Batas Akhir Upload Dokumen',
                    isi: 'Kepada peserta yang belum melengkapi dokumen persyaratan, harap segera upload sebelum 15 Januari 2026 pukul 23.59 WIB. Dokumen yang tidak lengkap akan dinyatakan gugur.',
                    tipe: 'peringatan',
                    icon: 'alert-triangle',
                    status: 'terkirim',
                    target: 'Peserta Pending',
                    jumlahPenerima: 305,
                    tanggal: '8 Jan 2026 · 14.30',
                    channel: ['Email', 'WhatsApp', 'Notifikasi App']
                },
                {
                    id: 3,
                    judul: 'Informasi Pengumuman Hasil Seleksi Administrasi',
                    isi: 'Hasil seleksi administrasi gelombang pertama telah diumumkan. Peserta yang lolos dapat mengecek statusnya di portal SPMB menggunakan nomor pendaftaran masing-masing.',
                    tipe: 'info',
                    icon: 'info',
                    status: 'terkirim',
                    target: 'Semua Peserta',
                    jumlahPenerima: 1284,
                    tanggal: '5 Jan 2026 · 09.00',
                    channel: ['Email', 'WhatsApp']
                },
                {
                    id: 4,
                    judul: 'Pengumuman Jadwal Daftar Ulang Peserta Diterima',
                    isi: 'Bagi peserta yang dinyatakan diterima, wajib melakukan daftar ulang pada 1–10 Februari 2026. Membawa surat keterangan penerimaan dan dokumen asli.',
                    tipe: 'jadwal',
                    icon: 'calendar-check',
                    status: 'terjadwal',
                    target: 'Peserta Verified',
                    jumlahPenerima: 320,
                    tanggal: '25 Jan 2026 · 07.00',
                    channel: ['Email', 'WhatsApp']
                },
                {
                    id: 5,
                    judul: 'Reminder: Cek Status Verifikasi Dokumen',
                    isi: 'Halo! Dokumen kamu sedang dalam proses verifikasi. Pantau terus status verifikasi di portal SPMB dan pastikan semua berkas sudah terunggah dengan benar.',
                    tipe: 'info',
                    icon: 'bell',
                    status: 'terjadwal',
                    target: 'Peserta Pending',
                    jumlahPenerima: 24,
                    tanggal: '12 Jan 2026 · 10.00',
                    channel: ['WhatsApp', 'Notifikasi App']
                },
                {
                    id: 6,
                    judul: 'Panduan Teknis: Cara Upload Dokumen di Portal SPMB',
                    isi: 'Draft panduan teknis lengkap mengenai tata cara upload dokumen melalui portal SPMB dalam format PDF. Panduan ini mencakup persyaratan ukuran file dan format yang diterima.',
                    tipe: 'info',
                    icon: 'file-text',
                    status: 'draft',
                    target: 'Semua Peserta',
                    jumlahPenerima: 0,
                    tanggal: 'Belum dikirim',
                    channel: ['Email']
                },
            ],

            get filteredPengumuman() {
                return this.pengumumanData.filter(p => {
                    const matchTab = this.activeTab === 'semua' || p.status === this.activeTab;
                    const matchSearch = p.judul.toLowerCase().includes(this.search.toLowerCase()) || p.isi.toLowerCase().includes(this.search.toLowerCase());
                    return matchTab && matchSearch;
                });
            },

            openModal() {
                this.isEditing = false;
                this.form = {
                    judul: '',
                    isi: '',
                    tipe: 'info',
                    target: 'Semua Peserta',
                    channelEmail: true,
                    channelWA: true,
                    channelApp: false,
                    jadwalkan: false,
                    tanggalKirim: '',
                    waktuKirim: ''
                };
                this.modalOpen = true;
                this.$nextTick(() => {
                    if (window.lucide) lucide.createIcons();
                });
            },

            editItem(item) {
                this.isEditing = true;
                this.form = {
                    judul: item.judul,
                    isi: item.isi,
                    tipe: item.tipe,
                    target: item.target,
                    channelEmail: true,
                    channelWA: true,
                    channelApp: false,
                    jadwalkan: false,
                    tanggalKirim: '',
                    waktuKirim: ''
                };
                this.modalOpen = true;
                this.$nextTick(() => {
                    if (window.lucide) lucide.createIcons();
                });
            },

            deleteItem(id) {
                this.pengumumanData = this.pengumumanData.filter(p => p.id !== id);
                showToastPeng('Pengumuman dihapus', '#ED6B60');
            },

            saveDraft() {
                this.modalOpen = false;
                showToastPeng('Disimpan sebagai draft', '#6A7686');
            },

            submitPengumuman() {
                if (!this.form.judul || !this.form.isi) {
                    showToastPeng('Judul dan isi wajib diisi!', '#ED6B60');
                    return;
                }
                const ch = [];
                if (this.form.channelEmail) ch.push('Email');
                if (this.form.channelWA) ch.push('WhatsApp');
                if (this.form.channelApp) ch.push('Notifikasi App');
                const statusBaru = this.form.jadwalkan ? 'terjadwal' : 'terkirim';
                const tglBaru = this.form.jadwalkan ? `${this.form.tanggalKirim} · ${this.form.waktuKirim}` : 'Baru saja';
                const iconMap = {
                    info: 'info',
                    penting: 'alert-circle',
                    jadwal: 'calendar',
                    peringatan: 'alert-triangle'
                };
                this.pengumumanData.unshift({
                    id: Date.now(),
                    judul: this.form.judul,
                    isi: this.form.isi,
                    tipe: this.form.tipe,
                    icon: iconMap[this.form.tipe],
                    status: statusBaru,
                    target: this.form.target,
                    jumlahPenerima: statusBaru === 'terkirim' ? 847 : 0,
                    tanggal: tglBaru,
                    channel: ch
                });
                this.modalOpen = false;
                showToastPeng(statusBaru === 'terjadwal' ? '✓ Pengumuman dijadwalkan!' : '✓ Pengumuman berhasil dikirim!', '#30B22D');
                this.$nextTick(() => {
                    if (window.lucide) lucide.createIcons();
                });
            },

            kirimSekarang(item) {
                item.status = 'terkirim';
                item.jumlahPenerima = 320;
                item.tanggal = 'Baru saja';
                showToastPeng('✓ Pengumuman berhasil dikirim!', '#30B22D');
            }
        };
    }

    function showToastPeng(msg, bg = '#080C1A') {
        const t = document.getElementById('toast-peng');
        const tm = document.getElementById('toast-peng-msg');
        t.style.background = bg;
        tm.textContent = msg;
        t.style.opacity = '1';
        t.style.transform = 'translateY(0)';
        setTimeout(() => {
            t.style.opacity = '0';
            t.style.transform = 'translateY(80px)';
        }, 2800);
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (window.lucide) lucide.createIcons();
    });
</script>
@endpush

@endsection