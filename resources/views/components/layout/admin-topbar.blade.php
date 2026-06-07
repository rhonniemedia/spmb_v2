      <div class="flex items-center justify-between w-full h-[90px] shrink-0 border-b border-border bg-white px-5 md:px-8">
          <button @click="sidebarOpen = true" class="lg:hidden size-11 flex items-center justify-center rounded-xl ring-1 ring-border hover:ring-primary transition-all duration-300 cursor-pointer">
              <i data-lucide="menu" class="size-6 text-foreground"></i>
          </button>
          <h2 class="hidden lg:block font-bold text-2xl text-foreground">Portal SPMB</h2>

          <div class="flex items-center gap-3">
              <!-- Search -->
              <button class="size-11 flex items-center justify-center rounded-xl ring-1 ring-border hover:ring-primary transition-all duration-300 cursor-pointer">
                  <i data-lucide="search" class="size-5 text-secondary"></i>
              </button>
              <!-- Notif -->
              <!-- <div class="relative" x-data="{ open: false }">
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
              </div> -->

              <!-- Avatar -->
              @php
              // Mengambil data user yang sedang login
              $authUser = Auth::user();
              $authName = $authUser->name ?? 'User';

              // Membuat inisial dinamis (2 huruf pertama dari kata ke-1 & ke-2)
              $nameParts = explode(' ', $authName);
              $authInitials = count($nameParts) >= 2
              ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
              : strtoupper(substr($authName, 0, 2));
              @endphp

              <div
                  class="hidden md:flex items-center gap-3 pl-3 border-l border-border relative"
                  x-data="{ openProfile: false }">

                  {{-- Avatar inisial / Foto --}}
                  <div
                      class="size-11 rounded-full bg-primary flex items-center justify-center ring-2 ring-border cursor-pointer shrink-0 overflow-hidden"
                      @click="openProfile = !openProfile">

                      @if($authUser->photo)
                      <img src="{{ asset('storage/' . $authUser->photo) }}" alt="Avatar" class="w-full h-full object-cover">
                      @else
                      <span class="text-white font-black text-sm">{{ $authInitials }}</span>
                      @endif
                  </div>

                  <div
                      x-show="openProfile"
                      x-transition:enter="transition ease-out duration-200"
                      x-transition:enter-start="opacity-0 scale-95"
                      x-transition:enter-end="opacity-100 scale-100"
                      x-transition:leave="transition ease-in duration-75"
                      x-transition:leave-start="opacity-100 scale-100"
                      x-transition:leave-end="opacity-0 scale-95"
                      @click.away="openProfile = false"
                      class="absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-border z-[100]"
                      style="display: none">
                      <div class="p-2">

                          {{-- Info user di atas --}}
                          <div class="flex items-center gap-3 px-2 py-2 mb-1">
                              <div class="size-9 rounded-full bg-primary flex items-center justify-center shrink-0 overflow-hidden">
                                  @if($authUser->photo)
                                  <img src="{{ asset('storage/' . $authUser->photo) }}" alt="Avatar" class="w-full h-full object-cover">
                                  @else
                                  <span class="text-white font-black text-xs">{{ $authInitials }}</span>
                                  @endif
                              </div>
                              <div class="min-w-0">
                                  <p class="font-bold text-sm text-foreground truncate">{{ $authName }}</p>
                                  <p class="text-xs text-secondary capitalize">{{ $authUser->role }}</p>
                              </div>
                          </div>

                          <hr class="my-1 border-border" />

                          <a href="{{ route('admin.profil.index') }}"
                              class="flex items-center gap-2 px-2 py-2 rounded-md text-sm text-secondary hover:bg-muted hover:text-primary transition-colors">
                              <i data-lucide="user" class="size-4"></i> My Profile
                          </a>

                          <a href="#"
                              class="flex items-center gap-2 px-2 py-2 rounded-md text-sm text-secondary hover:bg-muted hover:text-primary transition-colors">
                              <i data-lucide="settings" class="size-4"></i> Account Settings
                          </a>

                          <hr class="my-1 border-border" />

                          <a href="{{ route('logout') }}"
                              class="flex items-center gap-2 px-2 py-2 rounded-md text-sm text-error hover:bg-error/10 transition-colors"
                              onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                              <i data-lucide="log-out" class="size-4"></i>
                              <span>Sign Out</span>
                          </a>

                          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                              @csrf
                          </form>
                      </div>
                  </div>
              </div>
          </div>
      </div>