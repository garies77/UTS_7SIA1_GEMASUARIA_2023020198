<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-2xl p-6 text-white shadow-lg">
            <h2 class="text-2xl font-bold mb-2">Selamat Datang di Portal Ujian CBT</h2>
            <p class="text-emerald-100 text-sm">
                Pilih mata pelajaran di bawah ini untuk memulai ujian Computer Based Test (CBT). Setiap ujian terdiri dari 150 soal pilihan ganda.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($subjects as $subject)
                @php
                    $result = $userResults->get($subject->id);
                @endphp
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition duration-200 overflow-hidden flex flex-col justify-between">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300">
                                {{ $subject->questions_count }} Soal
                            </span>
                            @if ($result)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300">
                                    Nilai: {{ number_format($result->score, 1) }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                    Belum Diikuti
                                </span>
                            @endif
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                            {{ $subject->name }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-3 mb-4">
                            {{ $subject->description ?? 'Ujian CBT evaluasi pemahaman mata pelajaran ' . $subject->name . '.' }}
                        </p>
                    </div>

                    <div class="p-6 pt-0 border-t border-gray-100 dark:border-gray-700/50 mt-auto">
                        <a href="{{ url('/student/kerjakan/' . $subject->id) }}" 
                           class="w-full mt-4 inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg text-sm transition-colors duration-150 shadow">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            {{ $result ? 'Ulangi Ujian' : 'Mulai Ujian' }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
