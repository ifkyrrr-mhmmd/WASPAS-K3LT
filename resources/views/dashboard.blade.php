<x-app-layout>
    <!-- Premium Dynamic Header with Deep Purple to Teal Gradient -->
    <div class="bg-gradient-to-br from-[#281C59] via-[#2f2066] to-[#4E8D9C] rounded-b-[2.5rem] shadow-xl pb-28 pt-10 relative overflow-hidden">
        <!-- Decorative subtle background particles -->
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-white/10 via-transparent to-transparent opacity-50 pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/4 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-cyan-400/10 rounded-full blur-[80px] opacity-40 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">
                    Halo, {{ Auth::user()->name }}!
                </h1>
                <p class="text-white/80 font-medium text-sm sm:text-base mt-2 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                    Sistem Pendukung Keputusan Seleksi Kepala Divisi K3LT
                </p>
            </div>
            
            <!-- User Avatar & Profile Summary -->
            <div class="flex items-center gap-4 bg-white/5 border border-white/10 px-5 py-3 rounded-2xl backdrop-blur-md">
                <x-user-avatar size="w-12 h-12 border-2 border-white/20" />
                <div class="text-left">
                    <div class="text-xs text-white/60 font-semibold tracking-wider uppercase">Peran Saat Ini</div>
                    <div class="text-sm font-bold text-white">Administrator</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Overlapping Metrics Section (Overlays the header like in the Flutter App) -->
    <div class="-mt-16 relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Card 1: Total Seleksi (Deep Purple theme) -->
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-[0_10px_30px_rgba(40,28,89,0.06)] hover:shadow-[0_15px_35px_rgba(40,28,89,0.1)] transition-all duration-300 flex items-center justify-between group">
                <div class="flex items-center gap-5">
                    <div class="p-4 rounded-2xl bg-[#281C59]/10 text-[#281C59] group-hover:bg-[#281C59] group-hover:text-white transition-all duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Seleksi</p>
                        <p class="text-3xl font-black text-gray-800 mt-1">{{ $totalSeleksi }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                        Histori Aktif
                    </span>
                </div>
            </div>

            <!-- Card 2: Kandidat Terbaik (Mint Green theme) -->
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-[0_10px_30px_rgba(133,199,154,0.06)] hover:shadow-[0_15px_35px_rgba(133,199,154,0.1)] transition-all duration-300 flex items-center justify-between group">
                <div class="flex items-center gap-5">
                    <div class="p-4 rounded-2xl bg-[#5FA876]/10 text-[#5FA876] group-hover:bg-[#5FA876] group-hover:text-white transition-all duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 8V8m0 0a3 3 0 00-3-3H9m3 3a3 3 0 013-3h3"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M5 20h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2 2v9a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kandidat Terbaik (Terakhir)</p>
                        <p class="text-xl sm:text-2xl font-black text-gray-800 mt-1 truncate" title="{{ $alternatifTerbaik }}">
                            {{ $alternatifTerbaik }}
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600 shadow shadow-yellow-200">
                        <svg class="w-4 h-4 inline-block text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12zm0-9.5a.5.5 0 00-.5.5v4a.5.5 0 001 0v-4a.5.5 0 00-.5-.5z" clip-rule="evenodd"></path></svg>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Body Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 pb-16 space-y-10">

        <!-- Section: Quick Actions (Aksi Cepat) -->
        <div>
            <h3 class="text-lg font-black text-slate-800 tracking-tight mb-5 flex items-center gap-2">
                <span class="w-1.5 h-6 rounded-full bg-[#4E8D9C]"></span>
                Aksi Cepat
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- Action 1: Kalkulator WASPAS -->
                <a href="{{ route('calculation.index') }}" class="bg-white hover:bg-slate-50 border border-gray-100 p-5 rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center group">
                    <div class="w-14 h-14 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-500 mb-4 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="text-sm font-bold text-slate-700">Hitung WASPAS</span>
                </a>

                <!-- Action 2: Riwayat Seleksi -->
                <a href="{{ route('history.index') }}" class="bg-white hover:bg-slate-50 border border-gray-100 p-5 rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center group">
                    <div class="w-14 h-14 rounded-full bg-amber-50 flex items-center justify-center text-amber-500 mb-4 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-sm font-bold text-slate-700">Riwayat Seleksi</span>
                </a>

                <!-- Action 3: Analisis Sensitivitas -->
                <a href="{{ route('sensitivity.index') }}" class="bg-white hover:bg-slate-50 border border-gray-100 p-5 rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center group">
                    <div class="w-14 h-14 rounded-full bg-orange-50 flex items-center justify-center text-orange-500 mb-4 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <span class="text-sm font-bold text-slate-700">Analisis Sensitivitas</span>
                </a>

                <!-- Action 4: Audit Log -->
                <a href="{{ route('audit.index') }}" class="bg-white hover:bg-slate-50 border border-gray-100 p-5 rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center group">
                    <div class="w-14 h-14 rounded-full bg-cyan-50 flex items-center justify-center text-cyan-500 mb-4 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <span class="text-sm font-bold text-slate-700">Audit Log</span>
                </a>

            </div>
        </div>

        <!-- Section: Tips K3 Hari Ini (Daily Safety Quotes) -->
        @if($safetyTip)
            <div class="bg-gradient-to-br from-white to-slate-50/50 rounded-3xl p-6 border-l-8 border-[#4E8D9C] shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-[#4E8D9C]/10 text-[#4E8D9C] rounded-2xl flex-shrink-0">
                        <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#4E8D9C]/10 text-xs font-bold text-[#4E8D9C] mb-2 uppercase tracking-widest">
                            Tips K3 Hari Ini
                        </div>
                        <blockquote class="text-base font-medium text-slate-700 italic leading-relaxed">
                            "{{ $safetyTip['quote'] }}"
                        </blockquote>
                        <cite class="block text-xs font-bold text-slate-400 mt-2 not-italic">
                            — {{ $safetyTip['author'] }}
                        </cite>
                    </div>
                </div>
            </div>
        @endif

        <!-- Section: Recent History (Riwayat Perhitungan Terakhir) -->
        <div>
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-lg font-black text-slate-800 tracking-tight flex items-center gap-2">
                    <span class="w-1.5 h-6 rounded-full bg-[#281C59]"></span>
                    Riwayat Terakhir
                </h3>
                <a href="{{ route('history.index') }}" class="inline-flex items-center gap-1 text-sm font-bold text-[#4E8D9C] hover:text-[#376A76] hover:underline transition duration-200">
                    Lihat Semua
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>

            @if($recentHistories->isEmpty())
                <div class="bg-white rounded-3xl p-10 border border-gray-100 shadow-sm text-center flex flex-col items-center justify-center">
                    <div class="p-4 bg-slate-100 text-slate-400 rounded-full mb-4">
                        <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                    </div>
                    <h4 class="text-base font-bold text-slate-700">Belum Ada Riwayat Perhitungan</h4>
                    <p class="text-xs text-slate-400 mt-1 max-w-xs leading-relaxed">Silakan lakukan simulasi seleksi pada menu "Hitung WASPAS" terlebih dahulu.</p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-4">
                    @foreach($recentHistories as $history)
                        @php
                            $topCandidate = $history->result_data['rankings'][0]['alternative_name'] ?? 'N/A';
                            $topQi = $history->result_data['rankings'][0]['qi'] ?? 0;
                        @endphp
                        <div class="bg-white hover:bg-slate-50 border border-gray-100 p-5 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-500 font-bold group-hover:scale-105 transition-transform duration-200 shadow-sm">
                                    ⭐
                                </div>
                                <div>
                                    <h4 class="text-sm sm:text-base font-black text-slate-800 group-hover:text-indigo-900 transition-colors">{{ $history->title }}</h4>
                                    <p class="text-xs text-slate-400 mt-1 flex flex-wrap items-center gap-3">
                                        <span>Terbaik: <strong class="text-[#5FA876]">{{ $topCandidate }}</strong> (Qi: {{ number_format($topQi, 4) }})</span>
                                        <span class="text-slate-300">|</span>
                                        <span>&lambda;: {{ number_format($history->lambda, 2) }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="w-full sm:w-auto flex items-center justify-between sm:justify-end gap-4 border-t sm:border-t-0 pt-3 sm:pt-0 border-slate-100">
                                <span class="text-xs text-slate-400 font-medium">
                                    {{ $history->created_at->diffForHumans() }}
                                </span>
                                <a href="{{ route('history.show', $history->id) }}" class="inline-flex items-center justify-center p-2 rounded-full hover:bg-indigo-50 text-[#4E8D9C] hover:text-indigo-600 transition-all duration-200">
                                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
