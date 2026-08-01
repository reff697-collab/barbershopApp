<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">
            Status Toko Hari Ini
        </h1>
    </x-slot>

    <div class="mx-auto max-w-6xl px-3 pb-5 sm:px-6 sm:pb-8 lg:px-8">

        {{-- Informasi Tanggal Halaman --}}
        <div class="flex h-8 items-center justify-end">
            <p class="text-xs text-gray-400 sm:text-sm">
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>

        {{-- Flash Messages / Notifikasi --}}
        @if (session('success'))
            <div class="mb-5 rounded-xl border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-700 sm:mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700 sm:mb-6">
                {{ session('error') }}
            </div>
        @endif

        {{-- Kartu Status Toko Utama --}}
        <div class="mb-6">
            <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm sm:rounded-2xl sm:p-5">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="mb-1 text-[11px] text-gray-400 sm:text-xs">
                            Status Toko
                        </p>

                        <p class="text-lg font-semibold leading-tight text-gray-800 sm:text-xl">
                            @if ($storeDay->status === 'belum_buka')
                                Belum Buka
                            @elseif ($storeDay->status === 'buka')
                                Buka
                            @elseif ($storeDay->status === 'tutup')
                                Tutup Sementara
                            @else
                                Closing Final
                            @endif
                        </p>

                        <p class="mt-1 text-xs text-gray-400 sm:text-sm">
                            @if ($storeDay->status === 'belum_buka')
                                Toko belum memulai operasional hari ini.
                            @elseif ($storeDay->status === 'buka')
                                Toko sedang beroperasi dan menerima pelanggan.
                            @elseif ($storeDay->status === 'tutup')
                                Toko sedang ditutup sementara.
                            @else
                                Operasional hari ini sudah selesai dan dikunci.
                            @endif
                        </p>
                    </div>

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl sm:h-12 sm:w-12
                        @if ($storeDay->status === 'buka') bg-green-50 text-green-500
                        @elseif ($storeDay->status === 'tutup') bg-yellow-50 text-yellow-500
                        @elseif ($storeDay->status === 'selesai') bg-red-50 text-red-500
                        @else bg-gray-100 text-gray-500 @endif">
                        <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M5 10v9h14v-9M4 10l2-6h12l2 6M9 19v-5h6v5"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel Khusus Barber --}}
        @if (auth()->user()->hasRole('barber'))
            <div class="mb-6">
                <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm sm:rounded-2xl sm:p-5">
                    @if (!$myStatus)
                        <div class="mb-3 flex items-center justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800 sm:text-base">
                                    Status Kerja Kamu
                                </h3>
                                <p class="mt-0.5 text-xs text-gray-400 sm:text-sm">
                                    Aktifkan status sebelum mulai melayani pelanggan.
                                </p>
                            </div>

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-coral-50 text-coral-500 sm:h-10 sm:w-10">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 12a4 4 0 100-8 4 4 0 000 8zm-7 8a7 7 0 0114 0"/>
                                </svg>
                            </div>
                        </div>

                        <div class="mb-4">
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-[10px] font-medium text-gray-500 sm:text-xs">
                                Belum Aktif
                            </span>
                            <p class="mt-2 text-xs text-gray-400 sm:text-sm">
                                Status kerja kamu belum diaktifkan hari ini.
                            </p>
                        </div>

                        <form action="{{ route('store-day.activate') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full rounded-xl bg-gray-800 px-4 py-2.5 text-sm font-medium text-white sm:w-auto">
                                Aktifkan Status
                            </button>
                        </form>

                    @elseif ($myStatus->status === 'aktif')
                        <div class="mb-4">
                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-[10px] font-medium text-green-600 sm:text-xs">
                                Aktif
                            </span>
                            <p class="mt-2 text-xs text-gray-500 sm:text-sm">
                                Kamu sedang aktif sejak
                                <span class="font-medium text-gray-700">
                                    {{ $myStatus->activated_at->format('H:i') }}
                                </span>.
                            </p>
                        </div>

                        <form action="{{ route('store-day.deactivate') }}" method="POST" onsubmit="return confirm('Yakin mau menutup status kerja hari ini?');">
                            @csrf
                            <button type="submit" class="w-full rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-100 sm:w-auto">
                                Tutup Status
                            </button>
                        </form>

                    @else
                        <div>
                            <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-medium text-blue-600 sm:text-xs">
                                Selesai
                            </span>
                            <p class="mt-2 text-xs text-gray-500 sm:text-sm">
                                Kamu sudah selesai bekerja hari ini pada pukul
                                <span class="font-medium text-gray-700">
                                    {{ $myStatus->deactivated_at->format('H:i') }}
                                </span>.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Panel Khusus Kasir --}}
        @if (auth()->user()->hasRole('kasir'))
            <div class="mb-6">
                <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-sm sm:rounded-2xl sm:p-5">
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-800 sm:text-base">
                                Aksi Kasir
                            </h3>
                            <p class="mt-0.5 text-xs text-gray-400 sm:text-sm">
                                Atur operasional buka, tutup, dan closing toko.
                            </p>
                        </div>

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-coral-50 text-coral-500 sm:h-10 sm:w-10">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6l4 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>

                    @if ($storeDay->status === 'belum_buka')
                        <form action="{{ route('store-day.open') }}" method="POST">
                            @csrf
                            <button type="submit" @disabled(!$adaBarberAktif) class="w-full rounded-xl px-4 py-2.5 text-sm font-medium sm:w-auto {{ $adaBarberAktif ? 'bg-gradient-to-r from-coral-400 to-coral-500 text-white hover:from-coral-500 hover:to-coral-600' : 'cursor-not-allowed bg-gray-100 text-gray-400' }}">
                                Konfirmasi Buka Toko
                            </button>
                        </form>

                        @unless ($adaBarberAktif)
                            <div class="mt-3 rounded-xl border border-yellow-100 bg-yellow-50 px-3 py-2.5">
                                <p class="text-xs text-yellow-700">
                                    Menunggu minimal satu barber mengaktifkan status.
                                </p>
                            </div>
                        @endunless

                    @elseif ($storeDay->status === 'buka')
                        @php
                            $barberMasihAktif = $barbers->flatMap(fn($barber) => $barber->barberDailyStatuses)
                                ->where('status', 'aktif')
                                ->count();
                        @endphp

                        <form action="{{ route('store-day.close') }}" method="POST">
                            @csrf
                            <button type="submit" @disabled($barberMasihAktif > 0) class="w-full rounded-xl px-4 py-2.5 text-sm font-medium sm:w-auto {{ $barberMasihAktif === 0 ? 'bg-yellow-500 text-white hover:bg-yellow-600' : 'cursor-not-allowed bg-gray-100 text-gray-400' }}">
                                Tutup Toko Sementara
                            </button>
                        </form>

                        @if ($barberMasihAktif > 0)
                            <div class="mt-3 rounded-xl border border-yellow-100 bg-yellow-50 px-3 py-2.5">
                                <p class="text-xs text-yellow-700">
                                    Masih ada {{ $barberMasihAktif }} barber yang belum menutup status.
                                </p>
                            </div>
                        @endif

                    @elseif ($storeDay->status === 'tutup')
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <form action="{{ route('store-day.reopen') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    Buka Lagi
                                </button>
                            </form>

                            <form action="{{ route('store-day.finalize') }}" method="POST" onsubmit="return confirm('Yakin mau finalisasi closing? Aksi ini TIDAK BISA dibatalkan, dan data hari ini akan terkunci.');">
                                @csrf
                                <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-coral-400 to-coral-500 px-4 py-2.5 text-sm font-medium text-white hover:from-coral-500 hover:to-coral-600">
                                    Finalisasi Closing
                                </button>
                            </form>
                        </div>

                        <div class="mt-3 rounded-xl border border-gray-100 bg-gray-50 px-3 py-2.5">
                            <p class="text-xs leading-relaxed text-gray-500">
                                Toko sedang tutup sementara. Pilih “Buka Lagi” jika masih ada pelanggan, atau finalisasi closing jika operasional hari ini benar-benar selesai.
                            </p>
                        </div>

                    @else
                        <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-3">
                            <p class="text-sm font-medium text-blue-700">
                                Closing harian sudah difinalisasi.
                            </p>
                            <p class="mt-1 text-xs text-blue-600">
                                Tidak ada aksi tambahan yang dapat dilakukan hari ini.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Tabel Status Semua Barber --}}
        <div class="mb-6">
            <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm sm:rounded-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[520px] text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-medium sm:px-6">Nama</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($barbers as $barber)
                                @php
                                    $status = $barber->barberDailyStatuses->first();
                                @endphp
                                <tr class="text-gray-700 hover:bg-gray-50/70">
                                    <td class="px-4 py-3 sm:px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-coral-50 text-sm font-semibold text-coral-500">
                                                {{ strtoupper(substr($barber->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800">
                                                    {{ $barber->name }}
                                                </p>
                                                <p class="text-xs text-gray-400">
                                                    Barber
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 sm:px-6">
                                        @if (!$status)
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-[10px] font-medium text-gray-500 sm:text-xs">
                                                Belum Aktif
                                            </span>
                                        @elseif ($status->status === 'aktif')
                                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-[10px] font-medium text-green-600 sm:text-xs">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-medium text-blue-600 sm:text-xs">
                                                Selesai
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-5 py-10 text-center">
                                        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-400">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6H3v-2a4 4 0 014-4h2m4 6v-2a4 4 0 00-4-4m4 6h4m-8-9a4 4 0 100-8 4 4 0 000 8zm8 0a4 4 0 100-8 4 4 0 000 8z"/>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-600">
                                            Belum ada data barber
                                        </p>
                                        <p class="mt-1 text-xs text-gray-400 sm:text-sm">
                                            Data barber akan ditampilkan pada bagian ini.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Rekap Closing Harian --}}
@if ($closingHarian)
    <div class="mt-6 overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm sm:rounded-2xl">

        {{-- Header --}}
        <div class="border-b border-gray-50 p-4 sm:p-5">
            <h3 class="text-sm font-semibold text-gray-800 sm:text-base">
                Rekap Closing Hari Ini
            </h3>
            <p class="mt-0.5 text-xs text-gray-400 sm:text-sm">
                Ringkasan omzet, pengeluaran, dan komisi barber setelah closing final.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 p-4 sm:p-5 lg:grid-cols-3">

            {{-- Ringkasan Kas --}}
            <div class="lg:col-span-1">
                <p class="mb-3 text-xs font-medium uppercase tracking-wider text-gray-400">
                    Ringkasan Kas
                </p>

                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-gray-500">Omzet Layanan</span>
                        <span class="font-medium text-gray-700">
                            Rp {{ number_format($closingHarian->total_omzet_layanan, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <span class="text-gray-500">Omzet Produk</span>
                        <span class="font-medium text-gray-700">
                            Rp {{ number_format($closingHarian->total_omzet_produk, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="border-t border-gray-100 pt-3">
                        <div class="flex items-center justify-between gap-4">
                            <span class="font-semibold text-gray-700">Total Omzet</span>
                            <span class="font-semibold text-gray-800">
                                Rp {{ number_format($closingHarian->total_omzet, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="mt-2 space-y-2 pl-3">
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-xs text-gray-400">— Tunai</span>
                                <span class="text-xs font-medium text-gray-500">
                                    Rp {{ number_format($closingHarian->total_omzet_tunai, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <span class="text-xs text-gray-400">— QRIS</span>
                                <span class="text-xs font-medium text-gray-500">
                                    Rp {{ number_format($closingHarian->total_omzet_qris, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <span class="text-gray-500">Komisi Barber</span>
                        <span class="font-medium text-gray-700">
                            Rp {{ number_format($closingHarian->total_komisi_barber, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <span class="text-gray-500">Kas Keluar Harian</span>
                        <span class="font-medium text-gray-700">
                            Rp {{ number_format($closingHarian->total_kas_keluar, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="-mx-4 flex items-center justify-between gap-4 border-t border-gray-100 bg-gray-50 px-4 pb-1 pt-3 sm:-mx-5 sm:px-5">
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-700">
                            Laba Bersih
                        </span>
                        <span class="text-base font-bold text-coral-500">
                            Rp {{ number_format($closingHarian->laba_bersih, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Rincian Komisi --}}
            <div class="lg:col-span-2">
                <p class="mb-3 text-xs font-medium uppercase tracking-wider text-gray-400">
                    Rincian Komisi per Barber
                </p>

                <div class="overflow-hidden rounded-xl border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[600px] text-left text-sm">
                            <thead class="bg-gray-50 text-gray-500">
                                <tr>
                                    <th class="px-4 py-3 font-medium">Barber</th>
                                    <th class="px-4 py-3 font-medium">Total Layanan</th>
                                    <th class="px-4 py-3 font-medium">Komisi Kotor</th>
                                    <th class="px-4 py-3 font-medium">Potongan</th>
                                    <th class="px-4 py-3 text-right font-medium">Komisi Bersih</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">
                                @forelse ($closingHarian->barberDetails as $detail)
                                    <tr class="text-gray-700 hover:bg-gray-50/70">
                                        <td class="px-4 py-3 font-semibold text-gray-800">
                                            {{ $detail->barber->name }}
                                        </td>

                                        <td class="px-4 py-3 text-gray-600">
                                            Rp {{ number_format($detail->total_layanan, 0, ',', '.') }}
                                        </td>

                                        <td class="px-4 py-3 text-gray-600">
                                            Rp {{ number_format($detail->komisi_kotor, 0, ',', '.') }}
                                        </td>

                                        <td class="px-4 py-3 font-medium text-red-500">
                                            @if ($detail->potongan_cicilan > 0)
                                                -Rp {{ number_format($detail->potongan_cicilan, 0, ',', '.') }}
                                            @else
                                                Rp 0
                                            @endif
                                        </td>

                                        <td class="px-4 py-3 text-right font-semibold text-gray-800">
                                            Rp {{ number_format($detail->komisi_bersih, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-10 text-center">
                                            <p class="text-sm font-medium text-gray-600">
                                                Tidak ada rincian komisi barber
                                            </p>
                                            <p class="mt-1 text-xs text-gray-400">
                                                Data komisi akan tampil setelah terdapat transaksi barber.
                                            </p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endif

    </div>
</x-app-layout>