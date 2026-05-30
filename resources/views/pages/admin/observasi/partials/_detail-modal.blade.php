{{-- ═══════════════════════════════════════════
        MODAL DETAIL (Lihat detail info peserta)
════════════════════════════════════════════ --}}
<div x-show="modalOpen" x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    @click.self="modalOpen = false">

    <div class="bg-white rounded-2xl w-full max-w-3xl max-h-[90vh] flex flex-col overflow-hidden shadow-2xl">
        <div class="flex items-center justify-between px-6 py-5 border-b border-border shrink-0">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0"
                    :style="`background: ${activePeserta?.color}`" x-text="activePeserta?.init"></div>
                <div>
                    <h3 class="font-bold text-foreground" x-text="activePeserta?.name"></h3>
                    <p class="text-xs text-secondary font-mono" x-text="`${activePeserta?.reg_number}`"></p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-bold"
                    :class="{
                        'bg-warning/10 text-warning-dark': activePeserta?.status === 'pending',
                        'bg-success/10 text-success-dark': activePeserta?.status === 'verified',
                        'bg-error/10 text-error-dark': activePeserta?.status === 'rejected',
                        'bg-blue-50 text-blue-700': activePeserta?.status === 'incomplete'
                    }" x-text="activePeserta?.statusLabel"></span>
                <button @click="modalOpen = false"
                    class="size-8 rounded-lg border border-border flex items-center justify-center hover:bg-muted transition-colors cursor-pointer">
                    <i data-lucide="x" class="size-4 text-secondary"></i>
                </button>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-6 md:p-8">
            <div class="flex items-center gap-2 flex-wrap mb-6">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium border"
                    style="background:#EEEDFE;color:#3C3489;border-color:#AFA9EC">
                    <i data-lucide="route" class="size-3"></i>
                    <span x-text="activePeserta?.jalur"></span>
                </span>
                <template x-if="activePeserta?.jurusan1 && activePeserta?.jurusan1 !== '-'">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium border"
                        style="background:#EEEDFE;color:#3C3489;border-color:#AFA9EC">
                        <i data-lucide="monitor" class="size-3"></i>
                        <span x-text="activePeserta?.jurusan1"></span>
                    </span>
                </template>
            </div>

            <div class="mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <i data-lucide="user" class="size-3 text-secondary shrink-0"></i>
                    <span class="text-[10px] font-medium tracking-[0.08em] uppercase text-secondary">Data diri</span>
                    <div class="flex-1 h-px bg-border"></div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-xl px-3 py-2.5 bg-muted/50">
                        <p class="text-[11px] text-secondary mb-1">Jenis Kelamin</p>
                        <p class="text-sm font-medium text-foreground" x-text="activePeserta?.gender"></p>
                    </div>
                    <div class="rounded-xl px-3 py-2.5 bg-muted/50">
                        <p class="text-[11px] text-secondary mb-1">NISN</p>
                        <p class="text-sm font-medium text-foreground font-mono" x-text="activePeserta?.nisn"></p>
                    </div>
                    <div class="rounded-xl px-3 py-2.5 bg-muted/50">
                        <p class="text-[11px] text-secondary mb-1">Sekolah Asal</p>
                        <p class="text-sm font-medium text-foreground" x-text="activePeserta?.sekolah"></p>
                    </div>
                    <div class="rounded-xl px-3 py-2.5 bg-muted/50">
                        <p class="text-[11px] text-secondary mb-1">WhatsApp</p>
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-medium text-foreground font-mono"
                                x-text="`+62${activePeserta?.phone?.replace(/^0/, '')}`"></p>
                            <a :href="`https://wa.me/62${activePeserta?.phone?.replace(/^0/, '')}`"
                                target="_blank" x-show="activePeserta?.phone !== '-'"
                                class="inline-flex items-center justify-center size-5 rounded-md hover:opacity-80 transition-opacity shrink-0"
                                style="background:#dcfce7;color:#16a34a" title="Chat WhatsApp">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-2.5 h-2.5 fill-current">
                                    <path d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64C0 311.4 200.6 512 448 512c18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368C234.3 334.7 177.3 277.7 144 207.3L193.3 167c13.7-11.2 18.4-30 11.6-46.3l-40-96z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <i data-lucide="layout-grid" class="size-3 text-secondary shrink-0"></i>
                    <span class="text-[10px] font-medium tracking-[0.08em] uppercase text-secondary">Pilihan jurusan</span>
                    <div class="flex-1 h-px bg-border"></div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-xl px-3 py-2.5 bg-muted/50 col-span-2">
                        <p class="text-[11px] text-secondary mb-1">Pilihan 1</p>
                        <p class="text-sm font-medium"
                            :class="activePeserta?.jurusan1 === '-' ? 'text-secondary italic' : 'text-foreground'"
                            x-text="activePeserta?.jurusan1 === '-' ? 'Tidak memilih' : activePeserta?.jurusan1"></p>
                    </div>
                    <div class="rounded-xl px-3 py-2.5 bg-muted/50">
                        <p class="text-[11px] text-secondary mb-1">Pilihan 2</p>
                        <p class="text-sm font-medium text-foreground"
                            x-text="activePeserta?.jurusan2 === '-' ? '—' : activePeserta?.jurusan2"></p>
                    </div>
                    <div class="rounded-xl px-3 py-2.5 bg-muted/50">
                        <p class="text-[11px] text-secondary mb-1">Pilihan 3</p>
                        <p class="text-sm font-medium"
                            :class="activePeserta?.jurusan3 === '-' ? 'text-secondary italic' : 'text-foreground'"
                            x-text="activePeserta?.jurusan3 === '-' ? 'Tidak memilih' : activePeserta?.jurusan3"></p>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <i data-lucide="bar-chart-2" class="size-3 text-secondary shrink-0"></i>
                    <span class="text-[10px] font-medium tracking-[0.08em] uppercase text-secondary">Nilai</span>
                    <div class="flex-1 h-px bg-border"></div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-xl px-4 py-3" style="background:#EEEDFE;border:0.5px solid #AFA9EC">
                        <p class="text-[22px] font-medium font-mono" style="color:#3C3489" x-text="activePeserta?.rata_rapor"></p>
                        <p class="text-[11px] mt-0.5" style="color:#534AB7">Rata-rata Rapor</p>
                    </div>
                    <div class="rounded-xl px-4 py-3" style="background:#E1F5EE;border:0.5px solid #9FE1CB">
                        <p class="text-[22px] font-medium font-mono" style="color:#085041" x-text="activePeserta?.rata_tka"></p>
                        <p class="text-[11px] mt-0.5" style="color:#0F6E56">Rata-rata TKA</p>
                    </div>
                </div>
            </div>

            <div>
                <div class="flex items-center gap-2 mb-3">
                    <i data-lucide="file-check" class="size-3 text-secondary shrink-0"></i>
                    <span class="text-[10px] font-medium tracking-[0.08em] uppercase text-secondary">Berkas fisik diterima</span>
                    <div class="flex-1 h-px bg-border"></div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <template x-for="berkas in activePeserta?.berkas" :key="berkas">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium"
                            style="background:#EAF3DE;color:#27500A;border:0.5px solid #C0DD97">
                            <i data-lucide="check" class="size-3" style="color:#3B6D11"></i>
                            <span x-text="berkas"></span>
                        </span>
                    </template>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between gap-3 px-6 py-4 border-t border-border bg-white shrink-0">
            <p class="text-xs text-secondary">
                <span class="font-semibold text-foreground" x-text="activePeserta?.berkas?.length ?? 0"></span> dari <span class="font-semibold text-foreground">6</span> berkas diterima
            </p>
            <button type="button" @click="modalOpen = false"
                class="px-5 py-2.5 rounded-xl border border-border text-sm font-bold text-secondary hover:bg-muted hover:text-foreground transition-colors cursor-pointer shadow-sm">
                Tutup
            </button>
        </div>
    </div>
</div>