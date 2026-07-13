<x-app-layout>
    <div class="p-6 max-w-3xl mx-auto">
        <h1 class="text-xl font-medium mb-6">Riwayat Closing Harian</h1>

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Omzet</th>
                        <th class="px-4 py-3">Komisi Barber</th>
                        <th class="px-4 py-3">Laba Bersih</th>
                        <th class="px-4 py-3 text-right">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($closings as $closing)
                        <tr>
                            <td class="px-4 py-3">{{ $closing->storeDay->tanggal->format('d M Y') }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($closing->total_omzet, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($closing->total_komisi_barber, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 font-medium">Rp {{ number_format($closing->laba_bersih, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('closing.show', $closing) }}" class="text-blue-600 hover:underline">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                Belum ada closing harian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $closings->links() }}
        </div>
    </div>
</x-app-layout>