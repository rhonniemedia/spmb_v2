 <div x-show="isSubmitted" x-transition
     class="bg-white border border-gray-200 rounded-[20px] shadow-sm px-8 py-16 text-center">
     <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-6">
         <i class="fa-solid fa-circle-check text-green-500 text-4xl"></i>
     </div>
     <h2 class="text-2xl font-black text-[#080C1A] mb-2">Biodata Berhasil Dikirim! 🎉</h2>
     <p class="text-base text-[#6A7686] max-w-md mx-auto leading-relaxed mb-8">
         Data kamu telah diterima sistem dan sedang dalam proses verifikasi oleh panitia SPMB. Pantau status melalui dashboard peserta.
     </p>
     <div class="inline-flex flex-col gap-2 bg-gray-50 border border-gray-200 rounded-2xl px-8 py-5 mb-8 text-left">
         <div class="flex gap-4 text-sm"><span class="text-[#6A7686] font-semibold min-w-[130px]">No. Peserta</span><span class="font-bold">SPMB-2026-004821</span></div>
         <div class="flex gap-4 text-sm"><span class="text-[#6A7686] font-semibold min-w-[130px]">Nama</span><span class="font-bold">Ahmad Fauzi</span></div>
         <div class="flex gap-4 text-sm"><span class="text-[#6A7686] font-semibold min-w-[130px]">Pilihan Jurusan</span><span class="font-bold">Rekayasa Perangkat Lunak</span></div>
         <div class="flex gap-4 text-sm"><span class="text-[#6A7686] font-semibold min-w-[130px]">Status</span><span class="font-bold text-amber-500"><i class="fa-solid fa-clock"></i> Menunggu Verifikasi</span></div>
     </div>
     <div class="flex flex-col sm:flex-row gap-3 justify-center">
         <a href="{{ route('dashboard') }}"
             class="px-8 py-3 bg-gray-100 text-[#080C1A] rounded-full text-base font-bold hover:bg-gray-200 transition-all">
             <i class="fa-solid fa-gauge mr-2"></i> Kembali ke Dashboard
         </a>
         <button @click="window.print()"
             class="px-8 py-3 bg-primary text-white rounded-full text-base font-bold hover:bg-primary-hover transition-all">
             <i class="fa-solid fa-print mr-2"></i> Cetak Bukti Pendaftaran
         </button>
     </div>
 </div>