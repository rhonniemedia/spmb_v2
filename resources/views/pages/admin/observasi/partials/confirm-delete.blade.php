<div class="flex flex-col items-center gap-3 py-2 text-center">
    <div class="size-12 rounded-full bg-error/10 flex items-center justify-center">
        <i data-lucide="trash-2" class="size-5 text-error"></i>
    </div>
    <p class="text-sm text-secondary">Data siswa ini akan dihapus permanen.</p>
</div>
<div class="flex justify-end gap-2 mt-4">
    <button @click="$dispatch('close-modal')"
        class="px-4 py-2 text-sm rounded-xl border border-border hover:bg-muted transition-colors">
        Batal
    </button>
    <button
        hx-delete="/siswa/{{ $siswa->id }}"
        hx-target="closest tr"
        hx-swap="outerHTML"
        @click="$dispatch('close-modal')"
        class="px-4 py-2 text-sm rounded-xl bg-error text-white hover:bg-error/90 transition-colors">
        Ya, Hapus
    </button>
</div>