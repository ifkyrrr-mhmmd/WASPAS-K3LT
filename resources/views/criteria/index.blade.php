<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Kriteria') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Tombol Tambah --}}
            <div class="mb-6">
                <a href="{{ route('criteria.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Tambah Kriteria
                </a>
            </div>

            {{-- Flash Message --}}
            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Preset Template K3LT --}}
            <div class="bg-gradient-to-br from-white to-slate-50 border border-gray-100 rounded-3xl p-6 shadow-sm mb-6">
                <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                    <div class="flex items-start gap-4">
                        <div class="p-3 bg-[#4E8D9C]/10 text-[#4E8D9C] rounded-2xl flex-shrink-0 text-2xl">
                            <svg class="w-4 h-4 inline-block text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-800">Preset Template K3LT Instan</h3>
                            <p class="text-xs text-slate-500 mt-1 max-w-2xl leading-relaxed">
                                Muat data demonstrasi K3LT secara instan! Memilih salah satu preset di bawah ini akan secara otomatis mengisi Kriteria, Bobot, dan data Alternatif/Skor fiktif secara lengkap untuk mempercepat uji coba perhitungan WASPAS.
                            </p>
                        </div>
                    </div>
                    
                    <form action="{{ route('template.load') }}" method="POST" class="flex flex-wrap gap-2.5 w-full lg:w-auto">
                        @csrf
                        <button type="submit" name="template" value="ringkas"
                                onclick="return confirm('Memuat template akan menghapus semua Kriteria dan Alternatif lama Anda. Yakin?')"
                                class="flex-1 lg:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-100 text-xs font-bold rounded-xl transition duration-200">
                            ⚡ Template Ringkas (3C, 3A)
                        </button>
                        <button type="submit" name="template" value="standar"
                                onclick="return confirm('Memuat template akan menghapus semua Kriteria dan Alternatif lama Anda. Yakin?')"
                                class="flex-1 lg:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-100 text-xs font-bold rounded-xl transition duration-200">
                            <svg class="w-4 h-4 inline-block text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg> Template Standar (5C, 5A)
                        </button>
                        <button type="submit" name="template" value="lengkap"
                                onclick="return confirm('Memuat template akan menghapus semua Kriteria dan Alternatif lama Anda. Yakin?')"
                                class="flex-1 lg:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-100 text-xs font-bold rounded-xl transition duration-200">
                            <svg class="w-4 h-4 inline-block text-orange-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path></svg> Template Lengkap (7C, 6A)
                        </button>
                    </form>
                </div>
            </div>

            {{-- Tabel Kriteria --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($criteria->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama Kriteria
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tipe
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Bobot
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($criteria as $index => $c)
                                        <tr class="even:bg-gray-50 hover:bg-gray-100 transition duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ $index + 1 }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $c->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if ($c->type === 'Benefit')
                                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                                        Benefit
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                                        Cost
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                                {{ number_format($c->weight, 4) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('criteria.edit', $c) }}"
                                                       class="inline-flex items-center px-3 py-1.5 bg-amber-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-600 focus:bg-amber-600 active:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('criteria.destroy', $c) }}" method="POST" class="inline"
                                                          onsubmit="return confirm('Yakin ingin menghapus kriteria ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Belum ada data kriteria.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
