{{-- File: resources/views/pages/admin/daftar-ulang/partials/_status-modal.blade.php --}}

{{-- ==========================================
     MODAL UTAMA (VERIFIKASI)
=========================================== --}}
<div x-show="modalOpen" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    @click.self="closeModal()">

    <div class="bg-white rounded-2xl w-full max-w-xl max-h-[90vh] flex flex-col overflow-hidden shadow-2xl">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-5 border-b border-border shrink-0 bg-gray-50/50">
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 rounded-full flex items-center justify-center text-white text-base font-bold shrink-0"
                    :style="`background: ${activePeserta?.color}`" x-text="activePeserta?.init"></div>
                <div>
                    <h3 class="font-bold text-foreground text-lg" x-text="activePeserta?.name"></h3>
                    <p class="text-sm text-secondary font-mono" x-text="activePeserta?.reg_number"></p>
                </div>
            </div>
            <button @click="closeModal()" class="size-8 rounded-lg border border-border flex items-center justify-center hover:bg-muted transition-colors">
                <i data-lucide="x" class="size-4 text-secondary"></i>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-6">

            {{-- Alert Data Belum Lengkap --}}
            <div x-show="activePeserta?.data_status === 'incomplete'" class="mb-5 rounded-xl border border-warning/50 bg-warning/10 p-4 flex items-start gap-3">
                <i data-lucide="alert-triangle" class="size-5 text-warning-dark shrink-0 mt-0.5"></i>
                <div>
                    <p class="text-sm font-bold text-warning-dark">Data Belum Dilengkapi Siswa</p>
                    <p class="text-xs text-warning-dark/80 mt-1">Anda belum bisa memverifikasi kelulusan daftar ulang sampai siswa mengunggah/menyerahkan seluruh berkas yang dibutuhkan.</p>
                </div>
            </div>

            {{-- Form Verifikasi --}}
            <div x-show="activePeserta?.data_status === 'complete'" class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-foreground mb-2">Keputusan Verifikasi</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label :class="form.verification_status === 'verified' ? 'border-success bg-success/10 text-success-dark' : 'border-border bg-white hover:border-success/50'"
                            class="flex items-center justify-center gap-2 border-2 rounded-xl py-3 cursor-pointer transition-all">
                            <input type="radio" x-model="form.verification_status" value="verified" class="hidden">
                            <i data-lucide="check-circle" class="size-5"></i>
                            <span class="text-sm font-bold">Terima (Verified)</span>
                        </label>
                        <label :class="form.verification_status === 'rejected' ? 'border-error bg-error/10 text-error-dark' : 'border-border bg-white hover:border-error/50'"
                            class="flex items-center justify-center gap-2 border-2 rounded-xl py-3 cursor-pointer transition-all">
                            <input type="radio" x-model="form.verification_status" value="rejected" class="hidden">
                            <i data-lucide="x-circle" class="size-5"></i>
                            <span class="text-sm font-bold">Tolak Berkas</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-foreground mb-2">Catatan Verifikator <span class="text-xs font-normal text-secondary">(Opsional)</span></label>
                    <textarea x-model="form.verification_notes" class="w-full bg-white border border-border rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-primary transition-all" rows="3" placeholder="Tuliskan keterangan (misal: Alasan ditolak...)"></textarea>
                </div>
            </div>

            {{-- Info Log Waktu --}}
            <div class="mt-6 pt-5 border-t border-dashed border-border">
                <p class="text-xs font-bold uppercase tracking-wider text-secondary mb-3">Timeline Daftar Ulang</p>
                <div class="grid grid-cols-2 gap-y-3 text-sm">
                    <div>
                        <p class="text-secondary text-xs">Mulai Daftar Ulang</p>
                        <p class="font-semibold text-foreground text-xs" x-text="activePeserta?.re_registered_at"></p>
                    </div>
                    <div>
                        <p class="text-secondary text-xs">Selesai Unggah Berkas</p>
                        <p class="font-semibold text-foreground text-xs" x-text="activePeserta?.completed_at"></p>
                    </div>
                    <div>
                        <p class="text-secondary text-xs">Diverifikasi Oleh</p>
                        <p class="font-semibold text-foreground text-xs" x-text="activePeserta?.verified_by"></p>
                    </div>
                    <div>
                        <p class="text-secondary text-xs">Waktu Verifikasi</p>
                        <p class="font-semibold text-foreground text-xs" x-text="activePeserta?.verified_at"></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Action --}}
        <div class="px-6 py-4 border-t border-border bg-gray-50/50 flex items-center justify-between shrink-0">

            {{-- Tombol Reset memanggil confirmReset() --}}
            <div>
                {{-- Jika package Spatie tidak jalan, kita gunakan cek manual role user --}}
                @if(in_array(auth()->user()->role ?? '', ['superadmin', 'admin']))
                <button type="button" @click="confirmReset()" :disabled="loading || activePeserta?.data_status === 'incomplete'"
                    class="px-4 py-2 border border-error/30 text-error hover:bg-error/10 text-xs font-bold rounded-lg transition-all flex items-center gap-1 disabled:opacity-50 disabled:cursor-not-allowed">
                    <i data-lucide="rotate-ccw" class="size-3.5"></i> Reset Progres
                </button>
                @endif
            </div>

            <div class="flex gap-2">
                <button type="button" @click="closeModal()" class="px-4 py-2.5 border border-border rounded-xl text-sm font-bold text-secondary hover:bg-muted transition-all">
                    Batal
                </button>
                <button type="button" @click="submitDecision()" x-show="activePeserta?.data_status === 'complete'" :disabled="loading"
                    class="px-5 py-2.5 bg-primary text-white hover:bg-primary-dark shadow-md text-sm font-bold rounded-xl transition-all flex items-center gap-2 disabled:opacity-70">
                    <i data-lucide="loader-2" class="size-4 animate-spin" x-show="loading" style="display: none;"></i>
                    <i data-lucide="save" class="size-4" x-show="!loading"></i>
                    <span x-text="loading ? 'Menyimpan...' : 'Simpan Keputusan'"></span>
                </button>
            </div>
        </div>

    </div>
</div>

{{-- ==========================================
     MODAL KONFIRMASI RESET PROGRES
=========================================== --}}
<div x-show="confirmResetOpen" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

    <div class="bg-white rounded-2xl w-full max-w-sm overflow-hidden shadow-2xl p-6 text-center border border-border relative">

        <div class="w-16 h-16 rounded-full bg-error/10 flex items-center justify-center mx-auto mb-4 border border-error/20 shadow-sm">
            <i data-lucide="alert-triangle" class="size-8 text-error"></i>
        </div>

        <h3 class="font-bold text-foreground text-lg mb-2">Reset Progres?</h3>

        <p class="text-sm text-secondary mb-8">
            Yakin ingin mereset progres pendaftaran <span class="font-bold text-foreground" x-text="activePeserta?.name"></span>? <br><br>
            <span class="text-xs text-error font-semibold bg-error/10 px-2 py-1 rounded">Berkas akan dianggap Belum Lengkap.</span>
        </p>

        <div class="flex gap-3 justify-center">
            <button type="button" @click="cancelReset()" :disabled="loading"
                class="px-5 py-2.5 border border-border rounded-xl text-sm font-bold text-secondary hover:bg-muted transition-all flex-1">
                Kembali
            </button>
            <button type="button" @click="executeReset()" :disabled="loading"
                class="px-5 py-2.5 bg-error text-white hover:bg-red-600 shadow-md text-sm font-bold rounded-xl transition-all flex items-center justify-center gap-2 flex-1 disabled:opacity-70">
                <i data-lucide="loader-2" class="size-4 animate-spin" x-show="loading" style="display: none;"></i>
                <span x-text="loading ? 'Proses...' : 'Ya, Reset'"></span>
            </button>
        </div>
    </div>
</div>