<x-app-layout>
    <!-- Stepper state initialized by Alpine.js -->
    <div x-data="waspasCalculator" class="py-12 relative">

        <!-- Loading Overlay -->
        <div x-show="isLoading" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-sm flex flex-col items-center justify-center text-white" x-transition>
            <div class="w-12 h-12 border-4 border-slate-200 border-t-blue-600 rounded-full animate-spin"></div>
            <p class="mt-4 font-medium text-sm tracking-wide animate-pulse">Memproses Data...</p>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Stepper Header -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-800">Evaluasi Keputusan WASPAS - K3LT</h2>
                            <p class="text-sm text-slate-500 mt-1">Modul Evaluasi Kepala Divisi K3LT</p>
                        </div>
                    </div>
                    
                    <!-- Progress Stepper Bullets -->
                    <div class="flex items-center gap-3">
                        <button @click="if (step > 1) step = 1" 
                                class="w-10 h-10 rounded-full font-bold text-sm flex items-center justify-center transition-all duration-300"
                                :class="step === 1 ? 'bg-[#281C59] text-white shadow shadow-indigo-400' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'">
                            1
                        </button>
                        <div class="w-12 h-0.5" :class="step >= 2 ? 'bg-[#281C59]' : 'bg-slate-200'"></div>
                        <button @click="if (step > 2) step = 2" 
                                class="w-10 h-10 rounded-full font-bold text-sm flex items-center justify-center transition-all duration-300"
                                :class="step === 2 ? 'bg-[#281C59] text-white shadow shadow-indigo-400' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                                :disabled="criteria.length === 0 || Math.abs(totalWeight - 1.0) > 0.0001">
                            2
                        </button>
                        <div class="w-12 h-0.5" :class="step >= 3 ? 'bg-[#281C59]' : 'bg-slate-200'"></div>
                        <button class="w-10 h-10 rounded-full font-bold text-sm flex items-center justify-center transition-all duration-300"
                                :class="step === 3 ? 'bg-[#281C59] text-white shadow shadow-indigo-400' : 'bg-slate-100 text-slate-500'"
                                disabled>
                            3
                        </button>
                    </div>
                </div>
            </div>

            <!-- ================= STEP 1: CRITERIA & WEIGHTS ================= -->
            <div x-show="step === 1" x-transition class="space-y-6">
                <!-- Preset Template Card -->
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-200">
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-800">Muat Template Kriteria</h3>
                            <p class="text-sm text-slate-500 mt-1">
                                Gunakan template bawaan untuk mempercepat pengisian data kriteria evaluasi.
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <button @click="loadPreset('ringkas')" class="px-4 py-2 bg-white hover:bg-slate-100 border border-slate-300 text-sm font-medium text-slate-700 rounded-lg transition duration-200 shadow-sm">
                                Template Ringkas (3 Kriteria)
                            </button>
                            <button @click="loadPreset('standar')" class="px-4 py-2 bg-white hover:bg-slate-100 border border-slate-300 text-sm font-medium text-slate-700 rounded-lg transition duration-200 shadow-sm">
                                Template Standar (5 Kriteria)
                            </button>
                            <button @click="loadPreset('lengkap')" class="px-4 py-2 bg-white hover:bg-slate-100 border border-slate-300 text-sm font-medium text-slate-700 rounded-lg transition duration-200 shadow-sm">
                                Template Lengkap (7 Kriteria)
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Weight Indicator Alert -->
                <div class="p-4 rounded-lg border flex items-start gap-3 transition-all duration-300"
                     :class="Math.abs(totalWeight - 1.0) < 0.0001 
                             ? 'bg-blue-50 border-blue-200 text-blue-800' 
                             : 'bg-amber-50 border-amber-200 text-amber-800'">
                    <div class="mt-0.5">
                        <svg x-show="Math.abs(totalWeight - 1.0) < 0.0001" class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <svg x-show="Math.abs(totalWeight - 1.0) >= 0.0001" class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-sm">Total Bobot: <span x-text="totalWeight.toFixed(2)"></span> / 1.00</h4>
                        <p class="text-sm mt-1" :class="Math.abs(totalWeight - 1.0) < 0.0001 ? 'text-blue-700' : 'text-amber-700'">
                            <span x-show="Math.abs(totalWeight - 1.0) < 0.0001">
                                Akumulasi bobot kriteria telah valid (1.00). Anda dapat melanjutkan ke tahap berikutnya.
                            </span>
                            <span x-show="Math.abs(totalWeight - 1.0) >= 0.0001">
                                Total bobot harus tepat bernilai 1.00 sebelum melanjutkan. Harap sesuaikan bobot kriteria Anda.
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Table Criteria Management -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-black text-slate-800">Daftar Parameter Kriteria</h3>
                            <p class="text-xs text-slate-400 mt-1">Ubah atau tambahkan kriteria beserta bobot pentingnya.</p>
                        </div>
                        <button @click="addCriteria()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow shadow-indigo-600/20">
                            + Tambah Kriteria
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Kriteria</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider" style="width: 25%;">Tipe</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider" style="width: 25%;">Bobot (0.00 - 1.00)</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider" style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100">
                                <template x-for="(c, index) in criteria" :key="c.id">
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="text" x-model="c.name" 
                                                   class="w-full bg-slate-50 hover:bg-white focus:bg-white text-sm font-bold text-slate-700 rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <select x-model="c.type" 
                                                    class="w-full bg-slate-50 hover:bg-white focus:bg-white text-sm font-bold text-slate-700 rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                                                <option value="Benefit">Benefit (Keuntungan)</option>
                                                <option value="Cost">Cost (Biaya)</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="number" step="0.01" min="0" max="1" x-model.number="c.weight" 
                                                   class="w-full bg-slate-50 hover:bg-white focus:bg-white text-sm font-bold text-slate-700 rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <button @click="deleteCriteria(index)" class="p-2 text-red-500 hover:bg-red-50 rounded-xl transition duration-200">
                                                <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="criteria.length === 0">
                                    <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-400">
                                        Belum ada kriteria yang dimasukkan. Silakan ketuk "+ Tambah Kriteria" atau muat preset K3LT.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Step 1 Navigation Buttons -->
                <div class="flex justify-end gap-3">
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-sm rounded-2xl transition duration-200">
                        Batal
                    </a>
                    <button @click="saveAndGoToStep2()" 
                            class="px-8 py-3 font-bold text-sm rounded-2xl transition duration-300 shadow-md shadow-indigo-600/10"
                            :class="Math.abs(totalWeight - 1.0) < 0.0001 
                                    ? 'bg-[#281C59] hover:bg-[#1a123d] text-white cursor-pointer hover:shadow-lg hover:scale-102' 
                                    : 'bg-slate-300 text-slate-500 cursor-not-allowed'"
                            :disabled="Math.abs(totalWeight - 1.0) >= 0.0001">
                        Lanjut ke Matriks Keputusan →
                    </button>
                </div>
            </div>

            <!-- ================= STEP 2: DECISION MATRIX ================= -->
            <div x-show="step === 2" x-transition class="space-y-6">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <div>
                            <h3 class="text-lg font-black text-slate-800">Matriks Keputusan Evaluasi</h3>
                            <p class="text-xs text-slate-400 mt-1">Masukkan skor evaluasi (0-100) untuk masing-masing kandidat pada setiap kriteria.</p>
                        </div>
                        <button @click="addAlternative()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow shadow-indigo-600/20">
                            + Tambah Kandidat
                        </button>
                    </div>

                    <!-- Responsive Horizontal Scrollable Table -->
                    <div class="overflow-x-auto border border-slate-100 rounded-2xl">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider" style="width: 25%;">Nama Kandidat</th>
                                    <template x-for="c in criteria" :key="c.id">
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider" :title="c.name">
                                            <span x-text="c.name"></span> <br>
                                            <span class="text-[9px] font-normal text-slate-400 capitalize" x-text="'(' + c.type + ')'"></span>
                                        </th>
                                    </template>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider" style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100">
                                <template x-for="(alt, aIdx) in alternatives" :key="alt.id">
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="text" x-model="alt.name" 
                                                   class="w-full bg-slate-50 hover:bg-white focus:bg-white text-sm font-bold text-slate-700 rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                                        </td>
                                        <template x-for="c in criteria" :key="c.id">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="number" min="0" max="100" x-model.number="alt.scores[c.id]" 
                                                       class="w-24 bg-slate-50 hover:bg-white focus:bg-white text-sm font-bold text-slate-700 rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                                            </td>
                                        </template>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <button @click="deleteAlternative(aIdx)" class="p-2 text-red-500 hover:bg-red-50 rounded-xl transition duration-200">
                                                <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="alternatives.length === 0">
                                    <td :colspan="criteria.length + 2" class="px-6 py-10 text-center text-sm text-slate-400">
                                        Belum ada alternatif yang dimasukkan. Silakan ketuk "+ Tambah Kandidat".
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Step 2 Navigation Buttons -->
                <div class="flex justify-between items-center">
                    <button @click="step = 1" class="px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-sm rounded-2xl transition duration-200">
                        ← Kembali Ke Kriteria
                    </button>
                    <div class="flex gap-3">
                        <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-sm rounded-2xl transition duration-200">
                            Batal
                        </a>
                        <button @click="submitStepper()" 
                                class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg transition duration-200 shadow-sm">
                            Proses Evaluasi WASPAS
                        </button>
                    </div>
                </div>
            </div>

            <!-- ================= STEP 3: RESULTS & SENSITIVITY ================= -->
            <div x-show="step === 3" x-transition class="space-y-6">
                <!-- Result Summary Panel -->
                <div class="bg-blue-50 rounded-xl p-6 border border-blue-200 flex items-center gap-5">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-blue-800 uppercase tracking-wider">Rekomendasi Teratas</h3>
                        <div class="text-xl font-bold text-slate-800 mt-1" x-text="computedRankings[0] ? computedRankings[0].alternative_name : 'N/A'"></div>
                        <p class="text-sm text-slate-600 mt-1">
                            Berdasarkan hasil kalkulasi WASPAS, kandidat ini menempati peringkat pertama dengan skor akhir (Qi): 
                            <strong class="text-slate-800" x-text="computedRankings[0] ? computedRankings[0].qi.toFixed(4) : '0.0000'"></strong>
                        </p>
                    </div>
                </div>

                <!-- Multi-Column Layout: Visual Rankings & Lambda Sliders -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Rankings Medal Cards & Bar Chart (2/3 width on large) -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Rankings & Badges -->
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                            <h3 class="text-lg font-black text-slate-800 mb-4">Peringkat Hasil WASPAS</h3>
                            
                            <div class="space-y-3">
                                <template x-for="(rank, rIdx) in computedRankings" :key="rank.alternative_id">
                                    <div class="flex items-center justify-between p-4 rounded-lg border transition-all duration-200"
                                         :class="rank.rank === 1 ? 'bg-blue-50/50 border-blue-200' : 'bg-white border-slate-200 hover:border-slate-300'">
                                        <div class="flex items-center gap-4">
                                            <div class="w-8 h-8 rounded-md flex items-center justify-center text-sm font-bold shadow-sm"
                                                 :class="rank.rank === 1 ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600'">
                                                <span x-text="rank.rank"></span>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-semibold text-slate-800" x-text="rank.alternative_name"></h4>
                                                <div class="flex items-center gap-3 mt-1 text-xs text-slate-500">
                                                    <span>Q1 (SAW): <span class="text-slate-700 font-medium" x-text="rank.q1.toFixed(4)"></span></span>
                                                    <span class="text-slate-300">|</span>
                                                    <span>Q2 (WP): <span class="text-slate-700 font-medium" x-text="rank.q2.toFixed(4)"></span></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-[10px] font-bold text-slate-400 uppercase">Skor Qi</div>
                                            <div class="text-base font-black text-blue-600" x-text="rank.qi.toFixed(4)"></div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Chart.js Canvas -->
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">
                            <h3 class="text-lg font-black text-slate-800 mb-1">Visualisasi Nilai Preferensi (Qi)</h3>
                            <p class="text-xs text-slate-400 mb-6">Grafik perbandingan skor akhir antar kandidat secara proporsional.</p>
                            
                            <div style="height: 300px;">
                                <canvas id="calcRankChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Parameter Sensitivity & Interactive Adjustments (1/3 width) -->
                    <div class="space-y-6">
                        <!-- Criteria Weights Proportional Sliders -->
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200" x-show="result">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-lg font-black text-slate-800">Uji Sensitivitas Bobot</h3>
                                <button @click="resetWeights()" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-full transition-colors flex items-center gap-1 border border-slate-200 shadow-sm">
                                    <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    Kembalikan Default
                                </button>
                            </div>
                            <p class="text-xs text-slate-400 mb-6">
                                Geser bobot salah satu kriteria, kriteria lainnya akan menyesuaikan secara proporsional agar total berat tetap 1.00.
                            </p>

                            <div class="space-y-4">
                                <template x-for="c in (result ? result.criteria : [])" :key="c.id">
                                    <div class="space-y-1">
                                        <div class="flex justify-between text-xs font-bold text-slate-700">
                                            <span x-text="c.name"></span>
                                            <span class="text-blue-600" x-text="(proportionalWeights[c.id] || 0).toFixed(4)"></span>
                                        </div>
                                        <input type="range" min="0.01" max="0.99" step="0.01"
                                               :value="proportionalWeights[c.id] || c.weight"
                                               @input="adjustWeight(c.id, $event.target.value)"
                                               class="w-full h-2 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-[#4E8D9C]">
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Normalization & Calculation Matrix Tables (Responsive) -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200" x-show="result">
                    <h3 class="text-lg font-black text-slate-800 mb-4">Matriks Ternormalisasi</h3>
                    <div class="overflow-x-auto border border-slate-100 rounded-2xl">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Alternatif</th>
                                    <template x-for="c in (result ? result.criteria : [])" :key="c.id">
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider" :title="c.name">
                                            <span x-text="c.name"></span> <br>
                                            <span class="text-[9px] font-normal text-slate-400 capitalize" x-text="'(' + c.type + ')'"></span>
                                        </th>
                                    </template>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-100">
                                <template x-for="alt in (result ? result.alternatives : [])" :key="alt.id">
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-700" x-text="alt.name"></td>
                                        <template x-for="c in (result ? result.criteria : [])" :key="c.id">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 font-bold"
                                                x-text="(result.normalizedMatrix[alt.id][c.id] || 0).toFixed(4)">
                                            </td>
                                        </template>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Actions: Save and Print -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200 flex flex-col md:flex-row justify-end items-center gap-6">
                    <div class="flex flex-wrap items-center gap-3 w-full md:w-auto justify-end">
                        <!-- Action PDF Print with static locked lambda -->
                        <a href="{{ route('calculation.export-pdf') }}" target="_blank"
                           class="inline-flex items-center gap-2 justify-center px-5 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-medium text-sm rounded-lg transition duration-200 shadow-sm">
                            <svg class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="currentColor"><path d="M8.267 14.68c-.184 0-.308.018-.372.036v1.178c.076.018.171.023.302.023.479 0 .774-.242.774-.651 0-.366-.254-.586-.704-.586zm3.487.012c-.2 0-.33.018-.407.036v2.61c.077.018.201.018.313.018.817.023 1.349-.444 1.349-1.396.006-.83-.479-1.268-1.255-1.268z" /><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM9.498 16.19c-.309.29-.765.42-1.296.42a1.11 1.11 0 0 1-.308-.036v1.426c0 .59-.06.848-.22.99-.13.119-.373.124-.964.124v-1.077c.22 0 .326-.018.373-.065.042-.047.054-.154.054-.42v-4.636c.094-.024.32-.041.644-.041.876 0 1.54.409 1.54 1.487 0 .806-.326 1.428-.823 1.828zm5.132-1.523c0 1.256-.811 1.866-2.024 1.866a2.6 2.6 0 0 1-.592-.059v-1.043c.125.024.296.035.485.035.835 0 1.096-.462 1.096-1.037 0-.58-.332-1.054-1.013-1.054-.142 0-.301.018-.432.042v-1.042c.16-.018.332-.03.527-.03.882 0 1.35.445 1.35 1.156v.166zm3.627-1.12h-1.68v2.443h-1.066v-4.64h3.042v1.013h-1.976v1.184h1.68v1.001zM13 9V3.5L18.5 9H13z" /></svg>
                            Preview PDF
                        </a>

                        <!-- Save History Form -->
                        <form action="{{ route('calculation.save') }}" method="POST" class="flex flex-wrap md:flex-nowrap gap-2 items-center w-full md:w-auto">
                            @csrf
                            <input type="text" name="title" placeholder="Nama Riwayat (Contoh: Angkatan 2026)" required
                                   class="text-sm text-slate-700 border-slate-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm bg-white px-4 py-2.5">
                            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg transition duration-200 shadow-sm">
                                Simpan Riwayat
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            let waspasChart = null;
            Alpine.data('waspasCalculator', () => ({
                step: 1,
                isLoading: false,
                criteria: [],
                alternatives: [],
                lambda: 0.5,
                tempCriteriaId: 0,
                tempAlternativeId: 0,
                result: null,
                proportionalWeights: {},
                computedRankings: [],

                resetCalculator() {
                    Swal.fire({
                        title: 'Konfirmasi Reset',
                        text: "Apakah Anda yakin ingin mereset seluruh perhitungan? Semua data yang belum tersimpan akan hilang.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Reset!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.step = 1;
                            this.criteria = [];
                            this.alternatives = [];
                            this.result = null;
                            if (waspasChart) {
                                waspasChart.destroy();
                                waspasChart = null;
                            }
                            Swal.fire('Direset!', 'Kalkulator telah kembali bersih.', 'success');
                        }
                    });
                },

                init() {
                    // Hydrate initial database data if exists
                    let dbCriteria = @json($criteria);
                    let dbAlternatives = @json($alternatives);
                    let dbResult = @json($result);

                    if (typeof Chart === 'undefined') {
                        const script = document.createElement('script');
                        script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js';
                        document.head.appendChild(script);
                    }

                    if (dbCriteria && dbCriteria.length > 0) {
                        this.criteria = dbCriteria.map(c => ({
                            id: c.id,
                            name: c.name,
                            type: c.type,
                            weight: parseFloat(c.weight)
                        }));
                    }

                    if (dbAlternatives && dbAlternatives.length > 0) {
                        this.alternatives = dbAlternatives.map(alt => ({
                            id: alt.id,
                            name: alt.name,
                            scores: alt.scores || {}
                        }));
                    }

                    if (dbResult) {
                        this.result = dbResult;
                        this.lambda = parseFloat(dbResult.lambda);
                        this.result.criteria.forEach(c => {
                            this.proportionalWeights[c.id] = parseFloat(c.weight);
                        });
                        this.evaluateWaspas();
                        this.step = 3;
                    }
                },

                get totalWeight() {
                    return this.criteria.reduce((sum, c) => sum + parseFloat(c.weight || 0), 0);
                },

                addCriteria() {
                    this.tempCriteriaId--;
                    this.criteria.push({
                        id: this.tempCriteriaId,
                        name: 'Kriteria Baru ' + (this.criteria.length + 1),
                        type: 'Benefit',
                        weight: 0.00
                    });
                },

                deleteCriteria(index) {
                    let cId = this.criteria[index].id;
                    this.criteria.splice(index, 1);
                    this.alternatives.forEach(alt => {
                        delete alt.scores[cId];
                    });
                },

                addAlternative() {
                    this.tempAlternativeId--;
                    let newAlt = {
                        id: this.tempAlternativeId,
                        name: 'Kandidat Baru ' + (this.alternatives.length + 1),
                        scores: {}
                    };
                    this.criteria.forEach(c => {
                        newAlt.scores[c.id] = 0;
                    });
                    this.alternatives.push(newAlt);
                },

                deleteAlternative(index) {
                    this.alternatives.splice(index, 1);
                },

                loadPreset(templateName) {
                    this.isLoading = true;
                    fetch('{{ route("template.load") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ template: templateName })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.criteria = data.criteria.map(c => ({
                                id: c.id,
                                name: c.name,
                                type: c.type,
                                weight: parseFloat(c.weight)
                            }));
                            this.alternatives = data.alternatives.map(alt => ({
                                id: alt.id,
                                name: alt.name,
                                scores: alt.scores
                            }));
                            Swal.fire('Sukses', data.message || 'Preset berhasil dimuat!', 'success');
                        } else {
                            Swal.fire('Error', 'Gagal memuat preset: ' + data.message, 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Error', 'Terjadi kesalahan koneksi.', 'error');
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
                },

                saveAndGoToStep2() {
                    if (this.criteria.length === 0) {
                        Swal.fire('Error', 'Silakan tambahkan kriteria terlebih dahulu!', 'error');
                        return;
                    }
                    if (this.criteria.some(c => parseFloat(c.weight) === 0)) {
                        Swal.fire('Error', 'Bobot kriteria tidak boleh ada yang bernilai 0!', 'error');
                        return;
                    }
                    if (Math.abs(this.totalWeight - 1.0) > 0.0001) {
                        Swal.fire('Error', 'Akumulasi bobot kriteria Anda harus bernilai tepat 1.0 sebelum lanjut!', 'error');
                        return;
                    }
                    
                    this.alternatives.forEach(alt => {
                        this.criteria.forEach(c => {
                            if (alt.scores[c.id] === undefined) {
                                alt.scores[c.id] = 0;
                            }
                        });
                    });

                    if (this.alternatives.length === 0) {
                        this.addAlternative();
                        this.addAlternative();
                    }

                    this.step = 2;
                },

                submitStepper() {
                    if (this.alternatives.length === 0) {
                        Swal.fire('Error', 'Silakan tambahkan minimal 1 alternatif!', 'error');
                        return;
                    }

                    let hasEmptyScore = false;
                    this.alternatives.forEach(alt => {
                        this.criteria.forEach(c => {
                            let score = alt.scores[c.id];
                            if (score === undefined || score === null || score === '' || isNaN(score)) {
                                hasEmptyScore = true;
                            }
                        });
                    });

                    if (hasEmptyScore) {
                        Swal.fire('Error', 'Semua nilai pada Matriks Keputusan harus diisi dengan angka!', 'error');
                        return;
                    }

                    this.isLoading = true;
                    let payload = {
                        criteria: this.criteria,
                        alternatives: this.alternatives
                    };

                    fetch('{{ route("calculation.store-all") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.result = data.result;
                            this.lambda = parseFloat(data.result.lambda);
                            this.proportionalWeights = {};
                            this.result.criteria.forEach(c => {
                                this.proportionalWeights[c.id] = parseFloat(c.weight);
                            });
                            this.evaluateWaspas();
                            this.step = 3;
                            Swal.fire('Sukses', data.message || 'Data kriteria & matriks berhasil disimpan!', 'success');
                        } else {
                            Swal.fire('Error', 'Gagal menyimpan matriks: ' + data.message, 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Error', 'Terjadi kesalahan koneksi.', 'error');
                    })
                    .finally(() => {
                        this.isLoading = false;
                    });
                },

                resetWeights() {
                    this.proportionalWeights = {};
                    this.result.criteria.forEach(c => {
                        this.proportionalWeights[c.id] = parseFloat(c.weight);
                    });
                    this.evaluateWaspas();
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Bobot dikembalikan ke default',
                        showConfirmButton: false,
                        timer: 1500
                    });
                },

                evaluateWaspas() {
                    if (!this.result) return;

                    let rankings = this.result.alternatives.map(alt => {
                        let q1 = 0;
                        let q2 = 1;

                        this.result.criteria.forEach(c => {
                            let norm = this.result.normalizedMatrix[alt.id][c.id] || 0;
                            let w = this.proportionalWeights[c.id] !== undefined ? this.proportionalWeights[c.id] : parseFloat(c.weight);

                            q1 += norm * w;
                            q2 *= Math.pow(norm, w);
                        });

                        let qi = (parseFloat(this.lambda) * q1) + ((1 - parseFloat(this.lambda)) * q2);

                        return {
                            alternative_id: alt.id,
                            alternative_name: alt.name,
                            q1: q1,
                            q2: q2,
                            qi: qi
                        };
                    });

                    rankings.sort((a, b) => b.qi - a.qi);

                    rankings.forEach((r, idx) => {
                        r.rank = idx + 1;
                    });

                    this.computedRankings = rankings;
                    
                    this.$nextTick(() => {
                        this.updateChart();
                    });
                },
                
                updateChart() {
                    if (!this.result) return;
                    const labels = this.computedRankings.map(r => r.alternative_name);
                    const data = this.computedRankings.map(r => r.qi.toFixed(4));
                    const bgColors = this.computedRankings.map((r, i) => {
                        if (r.rank === 1) return 'rgba(251, 191, 36, 0.85)';
                        if (r.rank === 2) return 'rgba(148, 163, 184, 0.75)';
                        if (r.rank === 3) return 'rgba(217, 119, 6, 0.7)';
                        return 'rgba(99, 102, 241, 0.6)';
                    });
                    
                    if (waspasChart) {
                        waspasChart.data.labels = labels;
                        waspasChart.data.datasets[0].data = data;
                        waspasChart.data.datasets[0].backgroundColor = bgColors;
                        waspasChart.update();
                    } else {
                        const ctx = document.getElementById('calcRankChart');
                        if (ctx && typeof Chart !== 'undefined') {
                            waspasChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Skor Qi',
                                        data: data,
                                        backgroundColor: bgColors,
                                        borderRadius: 8,
                                        barPercentage: 0.7,
                                    }]
                                },
                                options: {
                                    indexAxis: 'y',
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: { display: false }
                                    },
                                    scales: {
                                        x: { beginAtZero: true }
                                    }
                                }
                            });
                        } else if (ctx) {
                            setTimeout(() => this.updateChart(), 100);
                        }
                    }
                },

                adjustWeight(cId, newValue) {
                    let targetCriteria = this.result.criteria.find(c => c.id === cId);
                    let oldVal = this.proportionalWeights[cId] !== undefined ? this.proportionalWeights[cId] : parseFloat(targetCriteria.weight);
                    let newVal = parseFloat(newValue);

                    this.proportionalWeights[cId] = newVal;

                    let otherCriteria = this.result.criteria.filter(c => c.id !== cId);
                    let otherSum = otherCriteria.reduce((sum, c) => {
                        let w = this.proportionalWeights[c.id] !== undefined ? this.proportionalWeights[c.id] : parseFloat(c.weight);
                        return sum + w;
                    }, 0);

                    let remaining = 1.0 - newVal;

                    if (otherSum > 0) {
                        otherCriteria.forEach(c => {
                            let currentW = this.proportionalWeights[c.id] !== undefined ? this.proportionalWeights[c.id] : parseFloat(c.weight);
                            this.proportionalWeights[c.id] = parseFloat((currentW * (remaining / otherSum)).toFixed(4));
                        });
                    } else {
                        let equalShare = remaining / otherCriteria.length;
                        otherCriteria.forEach(c => {
                            this.proportionalWeights[c.id] = parseFloat(equalShare.toFixed(4));
                        });
                    }

                    this.evaluateWaspas();
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>
