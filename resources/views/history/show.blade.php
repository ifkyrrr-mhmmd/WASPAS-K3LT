<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Riwayat: ') }} {{ $history->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Detail Metadata Riwayat -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Detail Arsip Hasil Perhitungan</h3>
                        <p class="text-xs text-slate-400 mt-1">Data riwayat ini bersifat read-only untuk menjamin integritas keputusan.</p>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-3">
                            <span class="text-xs text-slate-500">Parameter &lambda;: <strong class="text-slate-800">{{ number_format($history->lambda, 2) }}</strong></span>
                            <span class="text-slate-300">|</span>
                            <span class="text-xs text-slate-500">Tanggal: <strong class="text-slate-800">{{ $history->created_at->translatedFormat('d M Y, H:i') }} WIB</strong></span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <!-- Tombol Export PDF Laporan -->
                        <a href="{{ route('history.export-pdf', $history->id) }}"
                           class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 rounded-md font-semibold text-xs text-indigo-700 uppercase tracking-widest transition ease-in-out duration-150">
                            <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg> Download PDF
                        </a>
                        
                        <a href="{{ route('history.index') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center px-4 py-2 bg-slate-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Winner Announcement (Pengumuman Pemenang) -->
            @if(isset($result['rankings'][0]))
                @php
                    $winner = $result['rankings'][0];
                @endphp
                <div class="bg-gradient-to-r from-amber-500 via-yellow-400 to-amber-500 rounded-3xl p-6 shadow-lg text-white relative overflow-hidden group border border-yellow-300">
                    <!-- Decorative crown icon behind -->
                    <div class="absolute right-6 top-1/2 -translate-y-1/2 text-8xl opacity-15 select-none pointer-events-none group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    </div>
                    <div class="flex items-center gap-5 relative z-10">
                        <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center text-3xl shadow-inner animate-bounce">
                            <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                        </div>
                        <div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-[10px] font-black uppercase tracking-widest text-white mb-1">
                                Pemenang Utama (Peringkat 1)
                            </span>
                            <h3 class="text-2xl font-black">{{ $winner['alternative_name'] }}</h3>
                            <p class="text-sm text-white/90 mt-1 font-medium">
                                Terpilih sebagai Kepala Divisi K3LT terbaik dengan skor akhir WASPAS Qi: <strong class="text-white text-base">{{ number_format($winner['qi'], 4) }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Hasil Perankingan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4 text-[#281C59]">Hasil Perankingan Tersimpan</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" style="width: 15%;">Peringkat</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Alternatif</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Q1 (SAW)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Q2 (WP)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-bold">Qi (Final)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($result['rankings'] as $rank)
                                    <tr class="hover:bg-gray-100 {{ $loop->first ? 'bg-amber-50/50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-center">
                                            @if($rank['rank'] === 1)
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-400 text-white font-bold shadow-md ring-2 ring-yellow-300 animate-pulse text-sm"><span class="font-bold">1</span></span>
                                            @elseif($rank['rank'] === 2)
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-300 text-slate-800 font-bold shadow-md ring-2 ring-slate-200 text-sm"><span class="font-bold">2</span></span>
                                            @elseif($rank['rank'] === 3)
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-600 text-white font-bold shadow-md ring-2 ring-amber-500 text-sm"><span class="font-bold">3</span></span>
                                            @else
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-600 font-bold border border-slate-200 text-xs">{{ $rank['rank'] }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $rank['alternative_name'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($rank['q1'], 4) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($rank['q2'], 4) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">{{ number_format($rank['qi'], 4) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Data kosong.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Visualisasi Grafik (Chart.js Horizontal Bar) -->
            @if(count($result['rankings']) > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-1 text-slate-800">Visualisasi Nilai Preferensi (Qi)</h3>
                        <p class="text-xs text-slate-400 mb-6">Grafik perbandingan skor akhir antar kandidat dari data arsip.</p>
                        <div style="max-height: 400px;">
                            <canvas id="historyRankChart"></canvas>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Matriks Keputusan (Ternormalisasi) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4 text-gray-700">Matriks Ternormalisasi Tersimpan</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alternatif</th>
                                    @foreach ($result['criteria'] as $c)
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" title="{{ $c['name'] }}">
                                            C{{ $c['id'] }} <br> <span class="text-[10px] font-normal lowercase">({{ $c['type'] }})</span>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($result['alternatives'] as $alt)
                                    <tr class="hover:bg-gray-100">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $alt['name'] }}</td>
                                        @foreach ($result['criteria'] as $c)
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ number_format($result['normalizedMatrix'][$alt['id']][$c['id']] ?? 0, 4) }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @if(count($result['rankings']) > 0)
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const rankings = @json($result['rankings']);
        const labels = rankings.map(r => r.alternative_name);
        const qiData = rankings.map(r => parseFloat(r.qi).toFixed(4));
        const bgColors = rankings.map((r, i) => {
            if (i === 0) return 'rgba(251, 191, 36, 0.85)';
            if (i === 1) return 'rgba(148, 163, 184, 0.75)';
            if (i === 2) return 'rgba(217, 119, 6, 0.7)';
            return 'rgba(99, 102, 241, 0.6)';
        });
        const borderColors = rankings.map((r, i) => {
            if (i === 0) return 'rgb(245, 158, 11)';
            if (i === 1) return 'rgb(100, 116, 139)';
            if (i === 2) return 'rgb(180, 83, 9)';
            return 'rgb(79, 70, 229)';
        });

        new Chart(document.getElementById('historyRankChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Skor Qi',
                    data: qiData,
                    backgroundColor: bgColors,
                    borderColor: borderColors,
                    borderWidth: 2,
                    borderRadius: 8,
                    barPercentage: 0.7,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(30, 20, 70, 0.9)',
                        titleFont: { weight: 'bold' },
                        callbacks: {
                            label: function(ctx) {
                                return 'Qi: ' + parseFloat(ctx.raw).toFixed(6);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { font: { weight: 'bold', size: 11 } }
                    },
                    y: {
                        grid: { display: false },
                        ticks: { font: { weight: 'bold', size: 12 } }
                    }
                }
            }
        });
    });
    </script>
    @endpush
    @endif
</x-app-layout>
