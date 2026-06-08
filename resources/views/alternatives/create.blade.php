<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Alternatif Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('alternatives.store') }}" method="POST">
                        @csrf

                        {{-- Nama Alternatif --}}
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Alternatif
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name') }}"
                                   required
                                   class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                   placeholder="Masukkan nama alternatif">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nilai Kriteria --}}
                        @if ($criteria->count() > 0)
                            <div class="mb-6">
                                <div class="border-b border-gray-200 pb-2 mb-4">
                                    <h3 class="text-lg font-medium text-gray-900">Nilai Kriteria</h3>
                                    <p class="mt-1 text-sm text-gray-500">Masukkan nilai untuk setiap kriteria.</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @foreach ($criteria as $c)
                                        <div>
                                            <label for="score_{{ $c->id }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                {{ $c->name }}
                                                <span class="text-xs text-gray-400">
                                                    ({{ $c->type === 'Benefit' ? 'Benefit' : 'Cost' }})
                                                </span>
                                            </label>
                                            <input type="number"
                                                   name="scores[{{ $c->id }}]"
                                                   id="score_{{ $c->id }}"
                                                   value="{{ old('scores.' . $c->id) }}"
                                                   min="1"
                                                   step="0.0001"
                                                   required
                                                   class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                                   placeholder="1">
                                            @error('scores.' . $c->id)
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="mb-6 rounded-md bg-yellow-50 border border-yellow-200 p-4">
                                <p class="text-sm text-yellow-800">
                                    Tidak ada kriteria yang tersedia. Harap tambahkan kriteria terlebih dahulu.
                                </p>
                            </div>
                        @endif

                        {{-- Tombol Aksi --}}
                        <div class="flex flex-wrap items-center gap-4">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Simpan
                            </button>
                            <a href="{{ route('alternatives.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Batal
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
