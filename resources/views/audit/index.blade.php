<x-app-layout>
    <!-- Header Section -->
    <div class="bg-gradient-to-br from-[#281C59] via-[#2f2066] to-[#4E8D9C] rounded-b-[2.5rem] shadow-xl pb-20 pt-10 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-white/10 via-transparent to-transparent opacity-50 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 relative z-10">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">
                <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg> Jejak Audit (Audit Trail)
            </h1>
            <p class="text-white/80 font-medium text-sm sm:text-base mt-2">
                Catatan aktivitas administratif untuk akuntabilitas penggunaan aplikasi
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20 pb-16">

        @if($logs->isEmpty())
            <div class="bg-white rounded-3xl p-16 border border-gray-100 shadow-sm text-center flex flex-col items-center justify-center">
                <div class="p-5 bg-slate-100 text-slate-400 rounded-full mb-5 text-4xl"><svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg></div>
                <h4 class="text-lg font-bold text-slate-700">Belum Ada Aktivitas</h4>
                <p class="text-sm text-slate-400 mt-2 max-w-md leading-relaxed">
                    Semua tindakan yang berdampak pada data (create, update, delete, calculate, export) akan tercatat di sini.
                </p>
            </div>
        @else
            <div class="bg-white rounded-3xl border border-gray-100 shadow-lg overflow-hidden">
                <!-- Table Header -->
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                        <span class="w-1.5 h-6 rounded-full bg-[#4E8D9C]"></span>
                        Riwayat Aktivitas
                        <span class="text-sm font-medium text-slate-400 ml-2">({{ $logs->total() }} entri)</span>
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-gray-100">
                                <th class="px-6 py-4 text-left font-bold text-xs text-slate-500 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-4 text-left font-bold text-xs text-slate-500 uppercase tracking-wider">Aksi</th>
                                <th class="px-6 py-4 text-left font-bold text-xs text-slate-500 uppercase tracking-wider">Deskripsi</th>
                                <th class="px-6 py-4 text-center font-bold text-xs text-slate-500 uppercase tracking-wider">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr class="border-b border-gray-50 hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-xs font-bold text-slate-700">{{ $log->created_at->format('d M Y') }}</div>
                                        <div class="text-xs text-slate-400">{{ $log->created_at->format('H:i:s') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $actionColors = [
                                                'create' => 'bg-emerald-100 text-emerald-700',
                                                'update' => 'bg-blue-100 text-blue-700',
                                                'delete' => 'bg-red-100 text-red-700',
                                                'calculate' => 'bg-indigo-100 text-indigo-700',
                                                'export' => 'bg-amber-100 text-amber-700',
                                                'login' => 'bg-cyan-100 text-cyan-700',
                                                'load_template' => 'bg-purple-100 text-purple-700',
                                                'save_calculation_state' => 'bg-teal-100 text-teal-700',
                                                'sensitivity_analysis' => 'bg-orange-100 text-orange-700',
                                            ];
                                            $actionIcons = [
                                                'create' => '➕',
                                                'update' => '<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>',
                                                'delete' => '<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>',
                                                'calculate' => '<svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>',
                                                'export' => '<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>',
                                                'login' => '<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>',
                                                'load_template' => '<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>',
                                                'save_calculation_state' => '<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>',
                                                'sensitivity_analysis' => '<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>',
                                            ];
                                            $colorClass = $actionColors[$log->action] ?? 'bg-gray-100 text-gray-700';
                                            $icon = $actionIcons[$log->action] ?? '<svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>';
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold {{ $colorClass }}">
                                            {!! $icon !!} {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-slate-700 max-w-md truncate" title="{{ $log->description }}">
                                            {{ $log->description }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($log->details)
                                            <button x-data="{ show: false }" @click="show = !show"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                                                <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg> Lihat
                                            </button>
                                        @else
                                            <span class="text-xs text-slate-300">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-6 border-t border-gray-100 bg-slate-50/50">
                    {{ $logs->links() }}
                </div>
            </div>
        @endif

    </div>
</x-app-layout>
