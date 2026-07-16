<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">
            Daftar Layanan
        </h1>
    </x-slot>

    <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8 pb-5 sm:pb-8">

        {{-- Informasi halaman --}}
        <div class="h-8 flex items-center justify-end">
            <p class="text-xs sm:text-sm text-gray-400">
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
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

        {{-- Tombol Tambah Layanan --}}
        <div class="mb-6 flex justify-end">
            <a href="{{ route('services.create') }}"
               class="rounded-xl bg-gradient-to-r from-coral-400 to-coral-500 px-4 py-2.5 text-sm font-medium text-white hover:from-coral-500 hover:to-coral-600 shadow-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Layanan
            </a>
        </div>

        {{-- Tabel Daftar Layanan --}}
        <div class="mb-6">
            <div class="overflow-hidden rounded-xl sm:rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[520px] text-left text-sm">
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
                                    <td class="px-4 py-3 sm:px-6 font-medium text-gray-800">
                                        {{ $service->nama }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-gray-600">
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
                                    <td class="px-4 py-3 sm:px-6 text-right">
                                        <div class="flex items-center justify-end gap-3">
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
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center">
                                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-100 text-gray-400 mx-auto mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>

                                        <p class="text-sm font-medium text-gray-600">
                                            Belum ada data layanan
                                        </p>
                                        <p class="text-xs sm:text-sm text-gray-400 mt-1">
                                            Silakan tambahkan layanan baru menggunakan tombol di atas.
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