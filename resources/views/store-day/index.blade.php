<x-app-layout>

    <x-slot name="header">

        <h1 class="text-xl font-semibold text-gray-800">

            Status Toko Hari Ini

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



        {{-- Kartu status toko --}}

        <div class="mb-6">

            <div class="rounded-xl sm:rounded-2xl border border-gray-100 bg-white p-4 sm:p-5 shadow-sm">

                <div class="flex items-start justify-between gap-3">

                    <div class="min-w-0">

                        <p class="text-[11px] sm:text-xs text-gray-400 mb-1">

                            Status Toko

                        </p>



                        <p class="text-lg sm:text-xl font-semibold text-gray-800 leading-tight">

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



                        <p class="text-xs sm:text-sm text-gray-400 mt-1">

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



                    <div class="shrink-0 flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 rounded-xl

                        @if ($storeDay->status === 'buka') bg-green-50 text-green-500

                        @elseif ($storeDay->status === 'tutup') bg-yellow-50 text-yellow-500

                        @elseif ($storeDay->status === 'selesai') bg-red-50 text-red-500

                        @else bg-gray-100 text-gray-500 @endif">

                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M5 10v9h14v-9M4 10l2-6h12l2 6M9 19v-5h6v5"/>

                        </svg>

                    </div>

                </div>

            </div>

        </div>



        {{-- Panel khusus barber --}}

        @if (auth()->user()->hasRole('barber'))

            <div class="mb-6">



                <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm">

                    @if (!$myStatus)

                    <div class="flex items-center justify-between gap-3 mb-3">

                    <div>

                        <h3 class="text-sm sm:text-base font-semibold text-gray-800">

                            Status Kerja Kamu

                        </h3>

                        <p class="text-xs sm:text-sm text-gray-400 mt-0.5">

                            Aktifkan status sebelum mulai melayani pelanggan.

                        </p>

                    </div>



                    <div class="shrink-0 flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-coral-50 text-coral-500">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 12a4 4 0 100-8 4 4 0 000 8zm-7 8a7 7 0 0114 0"/>

                        </svg>

                    </div>

                </div>

                        <div class="mb-4">

                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-[10px] sm:text-xs font-medium text-gray-500">

                                Belum Aktif

                            </span>

                            <p class="text-xs sm:text-sm text-gray-400 mt-2">

                                Status kerja kamu belum diaktifkan hari ini.

                            </p>

                        </div>



                        <form action="{{ route('store-day.activate') }}" method="POST">

                            @csrf

                            <button type="submit" class="w-full sm:w-auto rounded-xl bg-gradient-to-r from-coral-400 to-coral-500 px-4 py-2.5 text-sm font-medium text-white hover:from-coral-500 hover:to-coral-600">

                                Aktifkan Status

                            </button>

                        </form>

                    @elseif ($myStatus->status === 'aktif')

                        <div class="mb-4">

                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-[10px] sm:text-xs font-medium text-green-600">

                                Aktif

                            </span>

                            <p class="text-xs sm:text-sm text-gray-500 mt-2">

                                Kamu sedang aktif sejak

                                <span class="font-medium text-gray-700">

                                    {{ $myStatus->activated_at->format('H:i') }}

                                </span>.

                            </p>

                        </div>



                        <form action="{{ route('store-day.deactivate') }}" method="POST" onsubmit="return confirm('Yakin mau menutup status kerja hari ini?');">

                            @csrf

                            <button type="submit" class="w-full sm:w-auto rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-100">

                                Tutup Status

                            </button>

                        </form>

                    @else

                        <div>

                            <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-[10px] sm:text-xs font-medium text-blue-600">

                                Selesai

                            </span>

                            <p class="text-xs sm:text-sm text-gray-500 mt-2">

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



        {{-- Panel khusus kasir --}}

        @if (auth()->user()->hasRole('kasir'))

            <div class="mb-6">

                <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-100 p-4 sm:p-5 shadow-sm">

                    <div class="flex items-center justify-between gap-3 mb-3">

                        <div>

                            <h3 class="text-sm sm:text-base font-semibold text-gray-800">

                                Aksi Kasir

                            </h3>

                            <p class="text-xs sm:text-sm text-gray-400 mt-0.5">

                                Atur operasional buka, tutup, dan closing toko.

                            </p>

                        </div>



                        <div class="shrink-0 flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-coral-50 text-coral-500">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v6l4 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>

                            </svg>

                        </div>

                    </div>



                    @if ($storeDay->status === 'belum_buka')

                        <form action="{{ route('store-day.open') }}" method="POST">

                            @csrf

                            <button type="submit" @disabled(!$adaBarberAktif) class="w-full sm:w-auto rounded-xl px-4 py-2.5 text-sm font-medium

                                {{ $adaBarberAktif

                                    ? 'bg-gradient-to-r from-coral-400 to-coral-500 text-white hover:from-coral-500 hover:to-coral-600'

                                    : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">

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

                            <button type="submit" @disabled($barberMasihAktif > 0) class="w-full sm:w-auto rounded-xl px-4 py-2.5 text-sm font-medium

                                {{ $barberMasihAktif === 0

                                    ? 'bg-yellow-500 text-white hover:bg-yellow-600'

                                    : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">

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

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

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

                            <p class="text-xs text-blue-600 mt-1">

                                Tidak ada aksi tambahan yang dapat dilakukan hari ini.

                            </p>

                        </div>

                    @endif

                </div>

            </div>

        @endif



        {{-- Status semua barber --}}

        <div class="mb-6">

            <div class="overflow-hidden rounded-xl sm:rounded-2xl border border-gray-100 bg-white shadow-sm">

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

                                            <div class="shrink-0 flex items-center justify-center w-9 h-9 rounded-xl bg-coral-50 text-coral-500 text-sm font-semibold">

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

                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-[10px] sm:text-xs font-medium text-gray-500">

                                                Belum Aktif

                                            </span>

                                        @elseif ($status->status === 'aktif')

                                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-[10px] sm:text-xs font-medium text-green-600">

                                                Aktif

                                            </span>

                                        @else

                                            <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-[10px] sm:text-xs font-medium text-blue-600">

                                                Selesai

                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="2" class="px-5 py-10 text-center">

                                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-100 text-gray-400 mx-auto mb-3">

                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a4 4 0 00-4-4h-1m-4 6H3v-2a4 4 0 014-4h2m4 6v-2a4 4 0 00-4-4m4 6h4m-8-9a4 4 0 100-8 4 4 0 000 8zm8 0a4 4 0 100-8 4 4 0 000 8z"/>

                                            </svg>

                                        </div>



                                        <p class="text-sm font-medium text-gray-600">

                                            Belum ada data barber

                                        </p>

                                        <p class="text-xs sm:text-sm text-gray-400 mt-1">

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



    </div>

</x-app-layout> 

