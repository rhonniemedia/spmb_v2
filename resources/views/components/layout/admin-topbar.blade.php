      <div class="flex items-center justify-between w-full h-[90px] shrink-0 border-b border-border bg-white px-5 md:px-8">
          <button @click="sidebarOpen = true" class="lg:hidden size-11 flex items-center justify-center rounded-xl ring-1 ring-border hover:ring-primary transition-all duration-300 cursor-pointer">
              <i data-lucide="menu" class="size-6 text-foreground"></i>
          </button>
          <h2 class="hidden lg:block font-bold text-2xl text-foreground">Dashboard Verifikasi</h2>

          <div class="flex items-center gap-3">
              <!-- Search -->
              <button class="size-11 flex items-center justify-center rounded-xl ring-1 ring-border hover:ring-primary transition-all duration-300 cursor-pointer">
                  <i data-lucide="search" class="size-5 text-secondary"></i>
              </button>
              <!-- Notif -->
              <div class="relative" x-data="{ open: false }">
                  <button @click="open = !open" class="size-11 flex items-center justify-center rounded-xl ring-1 ring-border hover:ring-primary transition-all duration-300 cursor-pointer relative">
                      <i data-lucide="bell" class="size-5 text-secondary"></i>
                      <span class="absolute -top-1 -right-1 h-5 px-1.5 rounded-full bg-error text-white text-xs font-medium flex items-center justify-center">3</span>
                  </button>
                  <div x-show="open" @click.away="open = false"
                      x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                      x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                      class="absolute right-0 top-full mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-border z-[100] overflow-hidden" style="display:none">
                      <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-gray-50">
                          <h3 class="font-bold text-foreground text-sm">Notifikasi</h3>
                          <button class="text-xs font-medium text-primary hover:underline cursor-pointer">Tandai dibaca</button>
                      </div>
                      <div class="max-h-[280px] overflow-y-auto scrollbar-hide flex flex-col">
                          <div class="flex gap-3 p-4 border-b border-border hover:bg-muted transition-colors bg-blue-50/30 cursor-pointer">
                              <div class="size-9 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                  <i data-lucide="file-text" class="size-4 text-primary"></i>
                              </div>
                              <div class="flex-1 min-w-0">
                                  <p class="text-sm font-semibold text-foreground">Dokumen baru masuk</p>
                                  <p class="text-xs text-secondary mt-0.5">Ahmad Fauzi mengirim berkas tambahan</p>
                                  <p class="text-[10px] text-secondary mt-1">5 menit lalu</p>
                              </div>
                              <div class="size-2 rounded-full bg-primary mt-1.5 shrink-0"></div>
                          </div>
                          <div class="flex gap-3 p-4 border-b border-border hover:bg-muted transition-colors cursor-pointer">
                              <div class="size-9 rounded-full bg-warning/10 flex items-center justify-center shrink-0">
                                  <i data-lucide="alert-triangle" class="size-4 text-warning"></i>
                              </div>
                              <div class="flex-1 min-w-0">
                                  <p class="text-sm font-semibold text-foreground">Dokumen hampir kadaluarsa</p>
                                  <p class="text-xs text-secondary mt-0.5">3 peserta belum melengkapi berkas</p>
                                  <p class="text-[10px] text-secondary mt-1">1 jam lalu</p>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <!-- Avatar -->
              <div
                  class="size-11 rounded-full bg-red-700 flex items-center justify-center ring-2 ring-white shadow-md cursor-pointer shrink-0"
                  @click="openProfile = !openProfile">
                  <span class="text-white font-black text-sm">RS</span>
              </div>
          </div>
      </div>