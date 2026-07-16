<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-800">
                Daftar Layanan
            </h1>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8 pb-5 sm:pb-8">

        {{-- Section Kontrol Atas (Tanggal & Tombol Tambah Layanan) --}}
        <div class="mb-6 flex flex-col gap-4">
            {{-- Baris 1: Tanggal (Rata kanan) --}}
            <div class="flex justify-end items-center h-6">
                <p class="text-xs sm:text-sm text-gray-400">
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>

            {{-- Baris 2: Tombol Tambah (Kompak & Rapat Kanan) --}}
            <div class="flex justify-end">
                <a href="{{ route('services.create') }}"
                   class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-coral-400 to-coral-500 px-4 py-2.5 text-sm font-medium text-white hover:from-coral-500 hover:to-coral-600 shadow-sm transition-all whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Layanan</span>
                </a>
            </div>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="mb-5 sm:mb-6 rounded-xl border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 sm:mb-6 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{-- Tabel Daftar Layanan --}}
        <div class="mb-6">
            <div class="overflow-hidden rounded-xl sm:rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="p-4 sm:p-5 border-b border-gray-50">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-800">
                        Pilihan Jasa & Layanan
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-400 mt-0.5">
                        Kelola katalog layanan barbershop beserta harga dan status aktifnya.
                    </p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[550px] text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-medium sm:px-6">Nama Layanan</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Harga</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Status</th>
                                <th class="px-4 py-3 font-medium sm:px-6 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($services as $service)
                                <tr class="text-gray-700 hover:bg-gray-50/70">
                                    <td class="px-4 py-3 sm:px-6 font-semibold text-gray-800">
                                        {{ $service->nama }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 font-medium text-gray-700">
                                        Rp {{ number_format($service->harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6">
                                        @if ($service->is_active)
                                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-[10px] sm:text-xs font-medium text-green-600">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-[10px] sm:text-xs font-medium text-gray-500">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-right space-x-3">
                                        <a href="{{ route('services.edit', $service) }}"
                                           class="text-xs sm:text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                                            Edit
                                        </a>
                                        <form action="{{ route('services.destroy', $service) }}" method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Yakin mau hapus layanan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-xs sm:text-sm font-medium text-red-600 hover:text-red-800 transition-colors">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center">
                                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-100 text-gray-400 mx-auto mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14.121 14.121L19 19m-7-7h7m-7-7h7M6 10a4 4 0 11-8 0 4 4 0 018 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-600">
                                            Belum ada layanan terdaftar
                                        </p>
                                        <p class="text-xs sm:text-sm text-gray-400 mt-1">
                                            Silakan tambahkan layanan baru untuk ditampilkan di bagian ini.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $services->links() }}
        </div>

    </div>
</x-app-layout>