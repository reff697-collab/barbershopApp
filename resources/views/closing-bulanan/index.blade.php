<x-app-layout>
    <div class="p-6 max-w-3xl mx-auto">
        <h1 class="text-xl font-medium mb-6">Closing Bulanan</h1>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Panel bulan berjalan --}}
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <p class="text-sm font-medium mb-3">Bulan Berjalan</p>

            @if ($sudahClosingBulanIni)
                <p class="text-sm text-gray-500">Closing bulan ini sudah pernah dibuat. Lihat di riwayat di bawah.</p>
            @else
                <p class="text-sm text-gray-500 mb-2">
                    Ada {{ $closingHarianBulanIni->count() }} closing harian bulan ini yang siap direkap.
                </p>
                @if ($closingHarianBulanIni->count() > 0)
                    <div class="text-sm text-gray-600 mb-3 space-y-1">
                        <div class="flex justify-between">
                            <span>Total omzet (preview)</span>
                            <span>Rp {{ number_format($closingHarianBulanIni->sum('total_omzet'), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Total laba bersih (preview)</span>
                            <span>Rp {{ number_format($closingHarianBulanIni->sum('laba_bersih'), 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endif
                <form action="{{ route('closing-bulanan.store') }}" method="POST"
                      onsubmit="return confirm('Yakin mau tutup bulan ini? Aksi ini tidak bisa dibatalkan.');">
                    @csrf
                    <button type="submit"
                            @disabled($closingHarianBulanIni->count() === 0)
                            class="px-4 py-2 rounded-md text-sm {{ $closingHarianBulanIni->count() > 0 ? 'bg-gray-800 text-white' : 'bg-gray-200 text-gray-400 cursor-not-allowed' }}">
                        Tutup Bulan Ini
                    </button>
                </form>
            @endif
        </div>

        {{-- Riwayat closing bulanan --}}
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <p class="text-sm font-medium p-4 pb-0">Riwayat Closing Bulanan</p>
            <table class="w-full text-sm text-left mt-2">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-2">Bulan</th>
                        <th class="px-4 py-2">Hari Closing</th>
                        <th class="px-4 py-2">Total Omzet</th>
                        <th class="px-4 py-2">Laba Bersih</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($closings as $closing)
                        <tr>
                            <td class="px-4 py-2">{{ $closing->nama_bulan }}</td>
                            <td class="px-4 py-2">{{ $closing->jumlah_hari_closing }} hari</td>
                            <td class="px-4 py-2">Rp {{ number_format($closing->total_omzet, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 font-medium">Rp {{ number_format($closing->laba_bersih, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-gray-500">Belum ada closing bulanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>resources/views/dashboard/owner.blade.php