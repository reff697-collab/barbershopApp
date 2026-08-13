<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">Rekap Absensi</h1>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filter mode + periode --}}
        <form action="{{ route('absensi.index') }}" method="GET" class="flex flex-wrap items-center gap-2 mb-6">
            <div class="flex gap-2">
                <a href="{{ route('absensi.index', ['mode' => 'bulan', 'bulan' => $bulan, 'tahun' => $tahun]) }}"
                   class="px-4 py-2 rounded-xl text-sm font-medium {{ $mode === 'bulan' ? 'bg-gradient-to-r from-coral-400 to-coral-500 text-white' : 'bg-white border border-gray-200 text-gray-600' }}">
                    Per Bulan
                </a>
                <a href="{{ route('absensi.index', ['mode' => 'tahun', 'tahun' => $tahun]) }}"
                   class="px-4 py-2 rounded-xl text-sm font-medium {{ $mode === 'tahun' ? 'bg-gradient-to-r from-coral-400 to-coral-500 text-white' : 'bg-white border border-gray-200 text-gray-600' }}">
                    Per Tahun
                </a>
            </div>

            <input type="hidden" name="mode" value="{{ $mode }}">

            @if ($mode === 'bulan')
                <select name="bulan" class="px-3 py-2 rounded-xl text-sm border border-gray-200">
                    @foreach (['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $namaBulan)
                        <option value="{{ $i + 1 }}" @selected($bulan == $i + 1)>{{ $namaBulan }}</option>
                    @endforeach
                </select>
            @endif

            <select name="tahun" class="px-3 py-2 rounded-xl text-sm border border-gray-200">
                @for ($y = now()->year - 1; $y <= now()->year + 1; $y++)
                    <option value="{{ $y }}" @selected($tahun == $y)>{{ $y }}</option>
                @endfor
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl text-sm font-medium bg-white border border-gray-200 text-gray-600">
                Tampilkan
            </button>
        </form>

        {{-- Rekap Kehadiran (Barber + Kasir digabung) --}}
        <div class="mb-6">
            <div class="overflow-hidden rounded-xl sm:rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="p-4 sm:p-5 border-b border-gray-50">
                    <h3 class="text-sm sm:text-base font-semibold text-gray-800">Rekap Kehadiran — {{ $labelPeriode }}</h3>
                    <p class="text-xs sm:text-sm text-gray-400 mt-0.5">Barber otomatis dari status kerja, Kasir dicatat manual Owner.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[480px] text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-medium sm:px-6">Nama</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Role</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Total Hadir</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Total Libur</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($barbers as $barber)
                                <tr class="text-gray-700 hover:bg-gray-50/70">
                                    <td class="px-4 py-3 sm:px-6 font-medium text-gray-800">{{ $barber['nama'] }}</td>
                                    <td class="px-4 py-3 sm:px-6">
                                        <span class="px-2 py-1 bg-coral-50 text-coral-600 rounded text-xs">Barber</span>
                                    </td>
                                    <td class="px-4 py-3 sm:px-6">{{ $barber['total_hadir'] }} hari</td>
                                    <td class="px-4 py-3 sm:px-6">{{ $barber['total_libur'] }} hari</td>
                                </tr>
                            @empty
                            @endforelse

                            @forelse ($kasirs as $kasir)
                                <tr class="text-gray-700 hover:bg-gray-50/70">
                                    <td class="px-4 py-3 sm:px-6 font-medium text-gray-800">{{ $kasir['nama'] }}</td>
                                    <td class="px-4 py-3 sm:px-6">
                                        <span class="px-2 py-1 bg-blue-50 text-blue-600 rounded text-xs">Kasir</span>
                                    </td>
                                    <td class="px-4 py-3 sm:px-6">{{ $kasir['total_hadir'] }} hari</td>
                                    <td class="px-4 py-3 sm:px-6">{{ $kasir['total_libur'] }} hari</td>
                                </tr>
                            @empty
                            @endforelse

                            @if ($barbers->isEmpty() && $kasirs->isEmpty())
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center">
                                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-100 text-gray-400 mx-auto mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-600">Belum ada data kehadiran</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Form input absensi kasir per tanggal --}}
        <div class="rounded-xl sm:rounded-2xl border border-gray-100 bg-white shadow-sm p-4 sm:p-5">
            <p class="text-sm font-medium text-gray-700 mb-3">Catat Kehadiran Kasir</p>

            <form action="{{ route('absensi.index') }}" method="GET" class="flex items-center gap-2 mb-4">
                <input type="hidden" name="mode" value="{{ $mode }}">
                <label class="text-xs text-gray-500">Pilih tanggal:</label>
                <input type="date" name="tanggal_input" value="{{ $tanggalInput }}"
                       onchange="this.form.submit()"
                       class="px-3 py-2 rounded-xl text-sm border border-gray-200">
            </form>

            <form action="{{ route('absensi.store') }}" method="POST" class="space-y-2">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $tanggalInput }}">

                @foreach ($kasirs as $kasir)
                    <label class="flex items-center gap-2 py-1">
                        <input type="checkbox" name="hadir[]" value="{{ $kasir['id'] }}"
                               @checked($absensiHariItu->get($kasir['id']) ?? false)>
                        <span class="text-sm">{{ $kasir['nama'] }}</span>
                    </label>
                @endforeach

                <button type="submit" class="mt-3 px-4 py-2 rounded-xl text-sm font-medium bg-gradient-to-r from-coral-400 to-coral-500 text-white">
                    Simpan Kehadiran {{ \Carbon\Carbon::parse($tanggalInput)->format('d/m/Y') }}
                </button>
            </form>
        </div>
    </div>
</x-app-layout>