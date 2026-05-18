    <section id="faq" class="py-24">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center mb-16 fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 glass rounded-full text-xs font-semibold text-cyan-400 border border-cyan-400/20 mb-6">
                    <i class="fa-solid fa-circle-question text-cyan-400"></i> Bantuan
                </div>
                <h2 class="font-display text-4xl lg:text-5xl font-extrabold text-white mb-4">Pertanyaan <span class="text-gradient-cyan">Umum</span></h2>
                <p class="text-slate-400">Temukan jawaban dari pertanyaan yang paling sering ditanyakan.</p>
            </div>

            <div class="space-y-3 fade-up">

                @forelse($faqs as $faq)
                <div class="faq-item glass rounded-2xl overflow-hidden">
                    <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-6 py-5 text-left group">
                        <span class="font-semibold text-white group-hover:text-cyan-400 transition-colors">
                            {{ $faq->question }}
                        </span>
                        <i class="fa-solid fa-plus faq-icon text-cyan-400 shrink-0 ml-4"></i>
                    </button>
                    <div class="faq-content px-6">
                        <div class="text-slate-400 text-sm leading-relaxed pb-5">
                            {!! $faq->answer !!}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 glass rounded-2xl">
                    <p class="text-slate-500 text-sm">Belum ada pertanyaan untuk kategori pendaftaran saat ini.</p>
                </div>
                @endforelse

            </div>

        </div>
    </section>