<x-app-layout>
    <!-- Header Section -->
    <div class="bg-gradient-to-br from-[#281C59] via-[#2f2066] to-[#4E8D9C] rounded-b-[2.5rem] shadow-xl pb-20 pt-10 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-white/10 via-transparent to-transparent opacity-50 pointer-events-none"></div>
        <div class="absolute top-1/2 left-1/4 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-cyan-400/10 rounded-full blur-[80px] opacity-40 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 relative z-10">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">
                <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg> Analisis Sensitivitas Kriteria
            </h1>
            <p class="text-white/80 font-medium text-sm sm:text-base mt-2">
                Uji stabilitas kandidat terpilih terhadap perubahan bobot kriteria
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20 pb-16" x-data="sensitivityApp()">

        <!-- Form Pilih Sumber Data & Kriteria -->
        <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-lg mb-8">
            <h3 class="text-lg font-black text-slate-800 tracking-tight mb-6 flex items-center gap-2">
                <span class="w-1.5 h-6 rounded-full bg-[#4E8D9C]"></span>
                Konfigurasi Analisis
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Pilih Sumber Data -->
                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Sumber Data</label>
                    <select x-model="source" @change="onSourceChange()" class="w-full rounded-xl border-gray-200 shadow-sm focus:ring-[#4E8D9C] focus:border-[#4E8D9C] text-sm py-3">
                        <option value="">— Pilih Sumber —</option>
                        @if($hasCurrentData)
                            <option value="current"><svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg> Data Aktif Saat Ini</option>
                        @endif
                        @foreach($histories as $h)
                            <option value="history_{{ $h->id }}"><svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg> {{ $h->title }} ({{ $h->created_at->format('d M Y') }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Pilih Kriteria -->
                <div>
                    <label class="block text-sm font-bold text-slate-600 mb-2">Kriteria yang Diuji</label>
                    <select x-model="criteriaId" class="w-full rounded-xl border-gray-200 shadow-sm focus:ring-[#4E8D9C] focus:border-[#4E8D9C] text-sm py-3" :disabled="criteriaOptions.length === 0">
                        <option value="">— Pilih Kriteria —</option>
                        <template x-for="c in criteriaOptions" :key="c.id">
                            <option :value="c.id" x-text="c.name"></option>
                        </template>
                    </select>
                </div>

                <!-- Tombol Jalankan -->
                <div class="flex items-end">
                    <button @click="runAnalysis()" :disabled="!source || !criteriaId || loading"
                        class="w-full py-3 px-6 rounded-xl font-bold text-white bg-gradient-to-r from-[#281C59] to-[#4E8D9C] hover:from-[#1f1545] hover:to-[#3d7a89] transition-all duration-300 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <template x-if="loading">
                            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                        <span x-show="loading">Menganalisis...</span>
                        <span x-show="!loading" class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> 
                            Jalankan Analisis
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Hasil Analisis -->
        <template x-if="result">
            <div class="space-y-6">

                <!-- Kartu Stabilitas -->
                <div x-show="result.is_stable"
                    class="bg-gradient-to-r from-emerald-50 to-green-50 rounded-3xl p-8 border-2 border-emerald-200 shadow-lg">
                    <div class="flex items-center gap-5">
                        <div class="w-20 h-20 rounded-2xl bg-emerald-100 flex items-center justify-center text-4xl shadow-inner">
                            <svg class="w-4 h-4 inline-block text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-emerald-800">Sangat Stabil!</h3>
                            <p class="text-emerald-600 mt-1 text-base">
                                Kandidat <strong x-text="result.baseline_winner"></strong> tetap menempati <strong>Peringkat #1</strong>
                                meskipun bobot kriteria <em x-text="result.criteria_name"></em> divariasikan dari 0% hingga 95%.
                            </p>
                        </div>
                    </div>
                </div>

                <div x-show="!result.is_stable"
                    class="bg-gradient-to-r from-orange-50 to-amber-50 rounded-3xl p-8 border-2 border-orange-200 shadow-lg">
                    <div class="flex items-center gap-5">
                        <div class="w-20 h-20 rounded-2xl bg-orange-100 flex items-center justify-center text-4xl shadow-inner">
                            <svg class="w-4 h-4 inline-block text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-orange-800">Pergeseran Terdeteksi!</h3>
                            <p class="text-orange-700 mt-1 text-base">
                                Jika bobot <strong x-text="result.criteria_name"></strong> mencapai
                                <strong x-text="result.shift_info?.threshold_percent + '%'"></strong>,
                                posisi Peringkat #1 akan diambil alih oleh kandidat
                                <strong class="text-red-600" x-text="result.shift_info?.new_winner"></strong>
                                (sebelumnya: <span x-text="result.shift_info?.original_winner"></span>).
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Info Ringkas -->
                <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-slate-50 rounded-2xl">
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sumber Data</div>
                            <div class="text-sm font-black text-slate-700 mt-1" x-text="result.source_name"></div>
                        </div>
                        <div class="text-center p-4 bg-slate-50 rounded-2xl">
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kriteria Diuji</div>
                            <div class="text-sm font-black text-slate-700 mt-1" x-text="result.criteria_name"></div>
                        </div>
                        <div class="text-center p-4 bg-slate-50 rounded-2xl">
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Bobot Asli</div>
                            <div class="text-sm font-black text-slate-700 mt-1" x-text="result.original_weight + '%'"></div>
                        </div>
                        <div class="text-center p-4 bg-slate-50 rounded-2xl">
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pemenang Baseline</div>
                            <div class="text-sm font-black text-emerald-600 mt-1" x-text="result.baseline_winner"></div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Detail Simulasi (Expandable) -->
                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                    <button @click="showDetail = !showDetail"
                        class="w-full p-6 flex items-center justify-between hover:bg-slate-50 transition-colors">
                        <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                            <span class="w-1.5 h-6 rounded-full bg-[#281C59]"></span>
                            Detail Komparasi Perangkingan (10 Langkah)
                        </h3>
                        <svg :class="showDetail ? 'rotate-180' : ''" class="w-5 h-5 text-slate-400 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="showDetail" x-transition class="px-6 pb-6">
                        <div class="overflow-x-auto rounded-2xl border border-gray-100">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gradient-to-r from-[#281C59] to-[#4E8D9C] text-white">
                                        <th class="px-4 py-3 text-left font-bold text-xs uppercase tracking-wider">Langkah</th>
                                        <th class="px-4 py-3 text-left font-bold text-xs uppercase tracking-wider">Bobot</th>
                                        <th class="px-4 py-3 text-left font-bold text-xs uppercase tracking-wider">Pemenang (#1)</th>
                                        <th class="px-4 py-3 text-left font-bold text-xs uppercase tracking-wider">Skor Qi</th>
                                        <th class="px-4 py-3 text-center font-bold text-xs uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(sim, idx) in result.simulation_results" :key="idx">
                                        <tr :class="sim.winner_shifted ? 'bg-orange-50 border-l-4 border-orange-400' : (idx % 2 === 0 ? 'bg-white' : 'bg-slate-50')"
                                            class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-3 font-bold text-slate-600" x-text="'#' + sim.step"></td>
                                            <td class="px-4 py-3 font-mono font-bold text-slate-700" x-text="sim.weight_percent + '%'"></td>
                                            <td class="px-4 py-3 font-bold" :class="sim.winner_shifted ? 'text-orange-700' : 'text-slate-800'" x-text="sim.winner_name"></td>
                                            <td class="px-4 py-3 font-mono text-slate-600" x-text="sim.winner_qi?.toFixed(6) ?? '-'"></td>
                                            <td class="px-4 py-3 text-center">
                                                <span x-show="sim.winner_shifted" class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-orange-100 text-orange-700 text-xs font-bold">
                                                    <svg class="w-4 h-4 inline-block text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg> Bergeser
                                                </span>
                                                <span x-show="!sim.winner_shifted" class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold">
                                                    ✓ Stabil
                                                </span>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </template>

        <!-- Empty State -->
        <template x-if="!result && !loading">
            <div class="bg-white rounded-3xl p-16 border border-gray-100 shadow-sm text-center flex flex-col items-center justify-center mt-4">
                <div class="p-5 bg-slate-100 text-slate-400 rounded-full mb-5 text-4xl">
                    <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <h4 class="text-lg font-bold text-slate-700">Belum Ada Analisis</h4>
                <p class="text-sm text-slate-400 mt-2 max-w-md leading-relaxed">
                    Pilih sumber data dan kriteria yang ingin diuji, lalu klik "Jalankan Analisis" untuk memulai simulasi sensitivitas bobot.
                </p>
            </div>
        </template>

    </div>

    @push('scripts')
    <script>
    function sensitivityApp() {
        return {
            source: '',
            criteriaId: '',
            loading: false,
            result: null,
            showDetail: false,
            criteriaOptions: @json($currentCriteria),

            onSourceChange() {
                this.criteriaId = '';
                this.result = null;

                if (this.source === 'current') {
                    this.criteriaOptions = @json($currentCriteria);
                } else if (this.source.startsWith('history_')) {
                    // Ambil kriteria dari history via inline data
                    const historyId = this.source.replace('history_', '');
                    this.fetchHistoryCriteria(historyId);
                } else {
                    this.criteriaOptions = [];
                }
            },

            async fetchHistoryCriteria(historyId) {
                try {
                    const response = await fetch(`/history/${historyId}`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (response.ok) {
                        const data = await response.json();
                        if (data.result && data.result.criteria) {
                            this.criteriaOptions = data.result.criteria;
                        }
                    }
                } catch (e) {
                    console.error('Error fetching history criteria:', e);
                    // Fallback: tetap gunakan kriteria aktif
                    this.criteriaOptions = @json($currentCriteria);
                }
            },

            async runAnalysis() {
                if (!this.source || !this.criteriaId) return;
                this.loading = true;
                this.result = null;
                this.showDetail = false;

                const isHistory = this.source.startsWith('history_');
                const body = {
                    source: isHistory ? 'history' : 'current',
                    history_id: isHistory ? this.source.replace('history_', '') : null,
                    criteria_id: this.criteriaId,
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                };

                try {
                    const response = await fetch('{{ route("sensitivity.analyze") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': body._token,
                        },
                        body: JSON.stringify(body),
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.result = data;
                    } else {
                        Swal.fire('Error', data.message || 'Terjadi kesalahan saat menganalisis.', 'error');
                    }
                } catch (e) {
                    console.error('Error:', e);
                    Swal.fire('Error', 'Terjadi kesalahan jaringan.', 'error');
                } finally {
                    this.loading = false;
                }
            },
        }
    }
    </script>
    @endpush
</x-app-layout>
