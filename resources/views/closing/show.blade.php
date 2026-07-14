<x-app-layout>
    <div class="p-6 max-w-2xl mx-auto">
        <a href="{{ route('closing.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali</a>

        <h1 class="text-xl font-medium mt-2 mb-6">
            Closing Harian — {{ $closing->storeDay->tanggal->format('d M Y') }}
        </h1>

        <div class="bg-white rounded-lg shadow p-5 mb-6 space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Omzet Layanan</span>
                <span>Rp {{ number_format($closing->total_omzet_layanan, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Omzet Produk</span>
                <span>Rp {{ number_format($closing->total_omzet_produk, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between border-t pt-2">
                <span class="text-gray-500">Total Omzet</span>
                <span class="font-medium">Rp {{ number_format($closing->total_omzet, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Total Komisi Barber</span>
                <span>Rp {{ number_format($closing->total_komisi_barber, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Pengeluaran Operasional</span>
                <span>Rp {{ number_format($closing->total_pengeluaran, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Kas Keluar Harian</span>
                <span>Rp {{ number_format($closing->total_kas_keluar, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between border-t pt-2">
                <span class="font-medium">Laba Bersih</span>
                <span class="font-semibold">Rp {{ number_format($closing->laba_bersih, 0, ',', '.') }}</span>
            </div>
            <p class="text-xs text-gray-400 pt-2">
                Ditutup oleh {{ $closing->closedBy->name ?? '-' }} pada {{ $closing->closed_at->format('d/m/Y H:i') }}
            </p>
        </div>

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <p class="text-sm font-medium p-4 pb-0">Rincian Komisi per Barber</p>
            <table class="w-full text-sm text-left mt-2">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-2">Barber</th>
                        <th class="px-4 py-2">Komisi Kotor</th>
                        <th class="px-4 py-2">Potongan Cicilan</th>
                        <th class="px-4 py-2">Komisi Bersih</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($closing->barberDetails as $detail)
                        <tr>
                            <td class="px-4 py-2">{{ $detail->barber->name }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($detail->komisi_kotor, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($detail->potongan_cicilan, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 font-medium">Rp {{ number_format($detail->komisi_bersih, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-gray-500">Tidak ada barber yang bertransaksi hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>