<x-app-layout>
    <div class="p-6 max-w-2xl mx-auto">
        <h1 class="text-xl font-medium mb-6">Status Toko Hari Ini</h1>

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

        {{-- Status toko saat ini --}}
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <p class="text-sm text-gray-500 mb-1">Status Toko</p>
            @if ($storeDay->status === 'belum_buka')
                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded text-sm">Belum Buka</span>
            @elseif ($storeDay->status === 'buka')
                <span class="px-3 py-1 bg-green-100 text-green-700 rounded text-sm">Buka</span>
            @elseif ($storeDay->status === 'tutup')
                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded text-sm">Tutup Sementara</span>
            @else
                <span class="px-3 py-1 bg-red-100 text-red-700 rounded text-sm">Closing Final</span>
            @endif
        </div>

        {{-- Panel khusus barber --}}
        @if (auth()->user()->hasRole('barber'))
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <p class="text-sm text-gray-500 mb-3">Status Kerja Kamu Hari Ini</p>

                @if (! $myStatus)
                    <form action="{{ route('store-day.activate') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md text-sm">
                            Aktifkan Status (Mulai Kerja)
                        </button>
                    </form>
                @elseif ($myStatus->status === 'aktif')
                    <p class="text-sm text-green-700 mb-3">Kamu sedang aktif sejak {{ $myStatus->activated_at->format('H:i') }}.</p>
                    <form action="{{ route('store-day.deactivate') }}" method="POST"
                          onsubmit="return confirm('Yakin mau menutup status kerja hari ini?');">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm">
                            Tutup Status (Selesai Kerja)
                        </button>
                    </form>
                @else
                    <p class="text-sm text-gray-500">Kamu sudah selesai kerja hari ini ({{ $myStatus->deactivated_at->format('H:i') }}).</p>
                @endif
            </div>
        @endif

        {{-- Panel khusus kasir --}}
        @if (auth()->user()->hasRole('kasir'))
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <p class="text-sm text-gray-500 mb-3">Aksi Kasir</p>

                @if ($storeDay->status === 'belum_buka')
                    <form action="{{ route('store-day.open') }}" method="POST">
                        @csrf
                        <button type="submit"
                                @disabled(! $adaBarberAktif)
                                class="px-4 py-2 rounded-md text-sm {{ $adaBarberAktif ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-400 cursor-not-allowed' }}">
                            Konfirmasi Buka Toko
                        </button>
                    </form>
                    @unless ($adaBarberAktif)
                        <p class="text-xs text-gray-500 mt-2">Menunggu minimal 1 barber aktifkan status.</p>
                    @endunless
                @elseif ($storeDay->status === 'buka')
                    @php
                        $barberMasihAktif = $barbers->flatMap->barberDailyStatuses->where('status', 'aktif')->count();
                    @endphp
                    <form action="{{ route('store-day.close') }}" method="POST">
                        @csrf
                        <button type="submit"
                                @disabled($barberMasihAktif > 0)
                                class="px-4 py-2 rounded-md text-sm {{ $barberMasihAktif === 0 ? 'bg-yellow-600 text-white' : 'bg-gray-200 text-gray-400 cursor-not-allowed' }}">
                            Tutup Toko (Sementara)
                        </button>
                    </form>
                    @if ($barberMasihAktif > 0)
                        <p class="text-xs text-gray-500 mt-2">Masih ada {{ $barberMasihAktif }} barber yang belum menutup status.</p>
                    @endif
                @elseif ($storeDay->status === 'tutup')
                    <div class="flex flex-wrap gap-3">
                        <form action="{{ route('store-day.reopen') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm">
                                Buka Lagi
                            </button>
                        </form>
                        <form action="{{ route('store-day.finalize') }}" method="POST"
                              onsubmit="return confirm('Yakin mau finalisasi closing? Aksi ini TIDAK BISA dibatalkan, dan data hari ini akan terkunci.');">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md text-sm">
                                Finalisasi Closing
                            </button>
                        </form>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        Toko sedang tutup sementara. Klik "Buka Lagi" kalau masih ada pelanggan, atau "Finalisasi Closing" kalau sudah yakin selesai.
                    </p>
                @else
                    <p class="text-sm text-gray-500">Closing harian sudah difinalisasi. Tidak ada aksi lagi untuk hari ini.</p>
                @endif
            </div>
        @endif

        {{-- Daftar semua barber & status mereka hari ini --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <p class="text-sm text-gray-500 p-4 pb-0">Status Semua Barber</p>
            <table class="w-full text-sm text-left mt-2">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-2">Nama</th>
                        <th class="px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($barbers as $barber)
                        @php $status = $barber->barberDailyStatuses->first(); @endphp
                        <tr>
                            <td class="px-4 py-2">{{ $barber->name }}</td>
                            <td class="px-4 py-2">
                                @if (! $status)
                                    <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded text-xs">Belum aktif</span>
                                @elseif ($status->status === 'aktif')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Aktif</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">Selesai</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="px-4 py-4 text-center text-gray-500">Belum ada data barber.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>