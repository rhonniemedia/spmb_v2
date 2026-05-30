<form hx-post="/siswa" hx-target="#modal-body" hx-swap="innerHTML">
    @csrf
    <div class="flex flex-col gap-4">
        <div>
            <label class="text-sm font-medium text-foreground">Nama</label>
            <input type="text" name="nama" class="mt-1 w-full border border-border rounded-xl px-3 py-2 text-sm">
        </div>
        {{-- field lainnya --}}
    </div>
    <div class="flex justify-end items-center gap-3 mt-6">
        <button type="button"
            @click="$dispatch('close-modal')"
            class="px-5 py-2.5 text-sm font-semibold rounded-xl text-secondary hover:text-foreground hover:bg-muted border border-transparent hover:border-border transition-all duration-200 cursor-pointer">
            Batal
        </button>
        <button type="submit"
            class="relative px-5 py-2.5 text-sm font-bold rounded-xl text-white overflow-hidden
               bg-gradient-to-r from-rose-600 to-orange-400
               shadow-[0_4px_14px_rgba(225,29,72,0.35)]
               hover:shadow-[0_6px_20px_rgba(225,29,72,0.45)]
               hover:scale-[1.02] active:scale-[0.98]
               transition-all duration-200 cursor-pointer
               flex items-center gap-2">
            <i data-lucide="save" class="size-4"></i>
            Simpan
        </button>
    </div>
</form>