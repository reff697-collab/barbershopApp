<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-800">
                Pengeluaran Operasional
            </h1>
        </div>
    </x-slot>

    <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8 pb-5 sm:pb-8">

        {{-- Section Kontrol Atas (Tanggal & Tombol Sejajar di Desktop) --}}
        <div class="mb-6 flex flex-col gap-4">
            
            {{-- Baris 1: Tanggal (Rata kanan di desktop, rapi di HP) --}}
            <div class="flex justify-end items-center h-6">
                <p class="text-xs sm:text-sm text-gray-400">
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>

            {{-- Baris 2: Tombol Aksi (Rapat ke kanan di HP & Desktop) --}}
            <div class="flex justify-end">
                <a href="{{ route('expenses.create') }}"
                   class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-coral-400 to-coral-500 px-4 py-2.5 text-sm font-medium text-white hover:from-coral-500 hover:to-coral-600 shadow-sm transition-all whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Catat Pengeluaran</span>
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

        {{-- Kartu Ringkasan Total Pengeluaran --}}
        <div class="mb-6">
            <div class="rounded-xl sm:rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-[11px] sm:text-xs text-gray-400 mb-1">
                            Total Pengeluaran Bulan Ini
                        </p>
                        <p class="text-lg sm:text-xl font-semibold text-gray-800 leading-tight">
                            Rp {{ number_format($totalBulanIni, 0, ',', '.') }}
                        </p>
                        <p class="text-xs sm:text-sm text-gray-400 mt-1">
                            Akumulasi seluruh pengeluaran operasional bulan berjalan.
                        </p>
                    </div>

                    <div class="shrink-0 flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-red-50 text-red-500">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Riwayat Pengeluaran --}}
        <div class="mb-6">
            <div class="overflow-hidden rounded-xl sm:rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="p-4 sm:p-5 border-b border-gray-50">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-800">
                        Daftar Pengeluaran
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-400 mt-0.5">
                        Riwayat pengeluaran kas operasional barbershop secara berkala.
                    </p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[650px] text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-medium sm:px-6">Tanggal</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Nama</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Kategori</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Nominal</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Input Oleh</th>
                                <th class="px-4 py-3 font-medium sm:px-6 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($expenses as $expense)
                                <tr class="text-gray-700 hover:bg-gray-50/70">
                                    <td class="px-4 py-3 sm:px-6 text-gray-500">
                                        {{ $expense->tanggal->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 font-medium text-gray-800">
                                        {{ $expense->nama }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6">
                                        @if ($expense->kategori === 'rutin')
                                            <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-[10px] sm:text-xs font-medium text-blue-600">
                                                Rutin
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-orange-50 px-2.5 py-1 text-[10px] sm:text-xs font-medium text-orange-600">
                                                Insidental
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 font-semibold text-gray-800">
                                        Rp {{ number_format($expense->nominal, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-gray-600">
                                        {{ $expense->inputBy->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-right space-x-3">
                                        <a href="{{ route('expenses.edit', $expense) }}"
                                           class="text-xs sm:text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                                            Edit
                                        </a>
                                        <form action="{{ route('expenses.destroy', $expense) }}" method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Yakin mau hapus pengeluaran ini?');">
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
                                    <td colspan="6" class="px-5 py-10 text-center">
                                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-100 text-gray-400 mx-auto mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-600">
                                            Belum ada pengeluaran tercatat
                                        </p>
                                        <p class="text-xs sm:text-sm text-gray-400 mt-1">
                                            Data pengeluaran operasional akan ditampilkan pada bagian ini.
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
            {{ $expenses->links() }}
        </div>

    </div>
</x-app-layout>