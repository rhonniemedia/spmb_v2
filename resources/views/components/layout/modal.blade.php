<div
    x-data="{
        open: false,
        title: '',
        subtitle: '',
        size: 'md',
        step: 1,
        steps: [],

        get totalSteps() { return this.steps.length },
        get currentIcon() { return this.steps[this.step - 1]?.icon ?? 'layout-grid' },

        init() {
            window.addEventListener('open-modal', e => {
                this.title    = e.detail.title    ?? ''
                this.subtitle = e.detail.subtitle ?? ''
                this.size     = e.detail.size     ?? 'md'
                this.steps    = e.detail.steps    ?? []
                this.step     = e.detail.step     ?? 1
                this.open     = true
                this.$nextTick(() => { if (window.lucide) lucide.createIcons() })
            })
            window.addEventListener('close-modal', () => {
                this.open  = false
                this.step  = 1
                this.steps = []
                document.getElementById('modal-body').innerHTML = ''
            })
            window.addEventListener('modal-step', e => {
                this.step = e.detail.step ?? this.step
                this.$nextTick(() => { if (window.lucide) lucide.createIcons() })
            })
        }
    }"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-[200] flex items-center justify-center p-0 sm:p-4 sm:pt-8">

    {{-- 1. BACKDROP OVERLAY --}}
    <div class="absolute inset-0 bg-black/50"
        @click="open = false"
        x-show="open"
        x-transition:enter="transition-opacity ease-linear duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
    </div>

    {{-- 2. MODAL DIALOG PANEL: Ditambahkan overflow-hidden di sini --}}
    <div
        x-show="open"
        class="relative w-full bg-white z-10 flex flex-col max-h-[92vh] overflow-hidden
               rounded-t-3xl sm:rounded-2xl
               shadow-[0_32px_64px_rgba(0,0,0,0.18),0_0_0_1px_rgba(0,0,0,0.06)]"
        :class="{
            'sm:max-w-sm':  size === 'sm',
            'sm:max-w-lg':  size === 'md',
            'sm:max-w-2xl': size === 'lg',
            'sm:max-w-4xl': size === 'xl'
        }"
        x-transition:enter="transition all ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-[50px]"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition all ease-out duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-[50px]"
        @click.stop>

        {{-- Drag handle (mobile) --}}
        <div class="flex justify-center pt-3 pb-1 sm:hidden shrink-0">
            <div class="w-10 h-1 rounded-full bg-gray-200"></div>
        </div>

        {{-- Header --}}
        <div class="shrink-0 px-6 pt-4" :class="totalSteps > 1 ? 'pb-0' : 'pb-4 border-b border-gray-100'">
            <div class="flex items-center justify-between gap-3" :class="totalSteps > 1 ? 'mb-4' : ''">
                <div class="flex items-center gap-3">
                    <div class="size-9 rounded-xl bg-gradient-to-br from-rose-600 to-orange-400 flex items-center justify-center shrink-0">
                        <i :data-lucide="currentIcon" class="size-4 text-white"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-sm text-foreground leading-tight" x-text="title"></h2>
                        <p class="text-xs text-secondary leading-tight mt-0.5" x-show="subtitle" x-text="subtitle"></p>
                    </div>
                </div>
                <button @click="$dispatch('close-modal')"
                    class="size-8 flex items-center justify-center rounded-lg border border-border text-secondary hover:bg-muted hover:text-foreground transition-all duration-150 cursor-pointer shrink-0">
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>

            {{-- Step Indicator --}}
            <div x-show="totalSteps > 1" x-cloak class="mt-4">
                <div class="grid gap-1 sm:gap-4 border-b border-gray-100"
                    :style="`grid-template-columns: repeat(${totalSteps}, minmax(0, 1fr))`">
                    <template x-for="(s, i) in steps" :key="i">
                        {{-- Menggunakan div agar tidak bisa di-klik --}}
                        <div
                            class="border-t-[3px] pt-2 pb-2.5 text-[10px] uppercase tracking-wider font-bold text-left px-1 transition-all duration-200 cursor-default select-none"
                            :class="{
                                'border-rose-500 text-rose-600': step >= i + 1,
                                'border-transparent text-gray-300': step < i + 1
                            }"
                            x-text="`${i + 1}. ${s.label}`">
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Body — diisi HTMX --}}
        <div id="modal-body"
            class="flex-1 flex flex-col min-h-0 p-6"
            hx-on::after-swap="if(window.lucide) lucide.createIcons()">
        </div>

    </div>
</div>