<x-filament-panels::page>
    @if ($isCompleted && $finalResult)
        <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8 text-center space-y-6">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 mb-2">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Ujian Selesai!</h2>
            <p class="text-gray-600 dark:text-gray-300">Hasil evaluasi ujian CBT untuk mata pelajaran <strong>{{ $subject->name }}</strong>:</p>

            <div class="grid grid-cols-3 gap-4 py-4 border-y border-gray-100 dark:border-gray-700">
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 rounded-xl">
                    <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $finalResult['correct'] }}</div>
                    <div class="text-xs font-semibold text-emerald-800 dark:text-emerald-300">Jawaban Benar</div>
                </div>
                <div class="p-4 bg-rose-50 dark:bg-rose-950/40 rounded-xl">
                    <div class="text-2xl font-black text-rose-600 dark:text-rose-400">{{ $finalResult['wrong'] }}</div>
                    <div class="text-xs font-semibold text-rose-800 dark:text-rose-300">Jawaban Salah</div>
                </div>
                <div class="p-4 bg-amber-50 dark:bg-amber-950/40 rounded-xl">
                    <div class="text-3xl font-black text-amber-600 dark:text-amber-400">{{ number_format($finalResult['score'], 1) }}</div>
                    <div class="text-xs font-semibold text-amber-800 dark:text-amber-300">Nilai Akhir</div>
                </div>
            </div>

            <div>
                <a href="{{ url('/student/ujian') }}" class="inline-flex items-center justify-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg transition-all">
                    Kembali ke Daftar Ujian
                </a>
            </div>
        </div>
    @elseif (count($questions) > 0)
        @php
            $currentQ = $questions[$currentIndex];
            $currentQId = $currentQ['id'];
            $selectedOpt = $selectedAnswers[$currentQId] ?? null;
            $isDoubtful = $doubtfulAnswers[$currentQId] ?? false;
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Kolom Utama: Pertanyaan & Opsi --}}
            <div class="lg:col-span-3 space-y-6">
                {{-- Info Soal Bar --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 flex items-center justify-between shadow-sm">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Mata Pelajaran</span>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $subject->name }}</h2>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Nomor Soal</span>
                        <div class="text-xl font-extrabold text-gray-900 dark:text-white">
                            {{ $currentIndex + 1 }} <span class="text-sm font-normal text-gray-500">/ {{ count($questions) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Card Pertanyaan --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="text-gray-900 dark:text-gray-100 text-lg leading-relaxed font-medium">
                        {{ $currentQ['payload'] }}
                    </div>

                    {{-- Opsi Jawaban A - E --}}
                    <div class="space-y-3 pt-4">
                        @foreach ($currentQ['options'] as $letter => $optionText)
                            @php
                                $isSelected = $selectedOpt === $letter;
                            @endphp
                            <button type="button" 
                                    wire:click="selectOption('{{ $letter }}')"
                                    class="w-full text-left p-4 rounded-xl border transition-all duration-150 flex items-start space-x-4 
                                    {{ $isSelected ? 'border-emerald-600 bg-emerald-50/80 dark:bg-emerald-950/40 ring-2 ring-emerald-500/20' : 'border-gray-200 dark:border-gray-700 hover:border-emerald-300 dark:hover:border-emerald-700 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                                <span class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm shrink-0 
                                      {{ $isSelected ? 'bg-emerald-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                                    {{ $letter }}
                                </span>
                                <span class="text-gray-800 dark:text-gray-200 pt-1 text-base leading-relaxed">
                                    {{ $optionText }}
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Navigasi Bawah --}}
                <div class="flex items-center justify-between pt-2">
                    <button type="button" 
                            wire:click="goToPrev" 
                            @disabled($currentIndex === 0)
                            class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 disabled:opacity-40 text-gray-800 dark:text-white font-semibold rounded-xl text-sm transition shadow-sm">
                        &larr; Sebelumnya
                    </button>

                    <button type="button" 
                            wire:click="toggleDoubtful"
                            class="px-5 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm 
                            {{ $isDoubtful ? 'bg-amber-500 text-white hover:bg-amber-600' : 'bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 hover:bg-amber-200' }}">
                        {{ $isDoubtful ? '✓ Ragu-ragu (Aktif)' : 'Ragu-ragu' }}
                    </button>

                    @if ($currentIndex < count($questions) - 1)
                        <button type="button" 
                                wire:click="goToNext"
                                class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-sm transition shadow-sm">
                            Selanjutnya &rarr;
                        </button>
                    @else
                        <button type="button" 
                                wire:click="submitExam"
                                wire:confirm="Apakah Anda yakin ingin menyelesaikan dan mengumpulkan Ujian ini?"
                                class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-sm transition shadow-lg">
                            Selesai Ujian
                        </button>
                    @endif
                </div>
            </div>

            {{-- Sidebar Kanan: Grid Navigator Soal --}}
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm space-y-4">
                    <h3 class="font-bold text-gray-900 dark:text-white text-base border-b border-gray-100 dark:border-gray-700 pb-3">
                        Navigasi Soal (150 Soal)
                    </h3>

                    {{-- Legend warna --}}
                    <div class="grid grid-cols-3 gap-2 text-xs font-medium pb-2 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center space-x-1.5">
                            <span class="w-3 h-3 rounded bg-emerald-500 inline-block"></span>
                            <span class="text-gray-600 dark:text-gray-300">Sudah</span>
                        </div>
                        <div class="flex items-center space-x-1.5">
                            <span class="w-3 h-3 rounded bg-amber-500 inline-block"></span>
                            <span class="text-gray-600 dark:text-gray-300">Ragu</span>
                        </div>
                        <div class="flex items-center space-x-1.5">
                            <span class="w-3 h-3 rounded bg-gray-200 dark:bg-gray-700 inline-block"></span>
                            <span class="text-gray-600 dark:text-gray-300">Belum</span>
                        </div>
                    </div>

                    {{-- Grid 1 s.d. 150 --}}
                    <div class="grid grid-cols-5 gap-2 max-h-96 overflow-y-auto pr-1">
                        @foreach ($questions as $idx => $q)
                            @php
                                $qId = $q['id'];
                                $hasAns = isset($selectedAnswers[$qId]);
                                $isDbt = $doubtfulAnswers[$qId] ?? false;
                                $isCurr = $currentIndex === $idx;

                                $bgClass = 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300';
                                if ($isDbt) {
                                    $bgClass = 'bg-amber-500 text-white';
                                } elseif ($hasAns) {
                                    $bgClass = 'bg-emerald-600 text-white';
                                }

                                $borderClass = $isCurr ? 'ring-2 ring-emerald-500 ring-offset-2 scale-105 font-black' : '';
                            @endphp
                            <button type="button" 
                                    wire:click="goToQuestion({{ $idx }})"
                                    class="w-full h-9 rounded-lg font-semibold text-xs flex items-center justify-center transition-all duration-100 {{ $bgClass }} {{ $borderClass }}">
                                {{ $idx + 1 }}
                            </button>
                        @endforeach
                    </div>

                    <div class="pt-3">
                        <button type="button" 
                                wire:click="submitExam"
                                wire:confirm="Apakah Anda yakin ingin mengumpulkan ujian ini sekarang?"
                                class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-sm shadow transition">
                            Selesai & Kumpulkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="p-8 text-center text-gray-500 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200">
            Belum ada soal aktif untuk mata pelajaran ini.
        </div>
    @endif
</x-filament-panels::page>
