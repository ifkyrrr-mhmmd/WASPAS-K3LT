<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Kriteria Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('criteria.store') }}" method="POST">
                        @csrf

                        {{-- Nama Kriteria --}}
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Kriteria
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   value="{{ old('name') }}"
                                   required
                                   class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                   placeholder="Masukkan nama kriteria">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tipe Kriteria --}}
                        <div class="mb-6">
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                                Tipe Kriteria
                            </label>
                            <select name="type"
                                    id="type"
                                    required
                                    class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">-- Pilih Tipe --</option>
                                <option value="Benefit" {{ old('type') === 'Benefit' ? 'selected' : '' }}>Benefit</option>
                                <option value="Cost" {{ old('type') === 'Cost' ? 'selected' : '' }}>Cost</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Bobot --}}
                        <div class="mb-6">
                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">
                                Bobot
                            </label>
                            <input type="number"
                                   name="weight"
                                   id="weight"
                                   value="{{ old('weight') }}"
                                   step="0.0001"
                                   min="0"
                                   max="1"
                                   required
                                   class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                   placeholder="Contoh: 0.2500">
                            @error('weight')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-center gap-4">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Simpan
                            </button>
                            <a href="{{ route('criteria.index') }}"
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
