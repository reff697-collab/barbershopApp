<x-app-layout>
    <div class="w-full max-w-3xl mx-auto px-4 py-6 sm:px-6 sm:py-8">

        <h1 class="mb-6 text-xl font-medium text-gray-800">
            Kas Keluar Harian
        </h1>

        @if (session('success'))
            <div class="mb-6 rounded-xl bg-green-100 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Ringkasan kas --}}
        <div class="mb-4 rounded-2xl bg-gradient-to-br from-coral-400 to-coral-600 p-5 sm:p-6">
            <p class="mb-1 text-xs text-coral-50">
                Kas Seharusnya di Laci
            </p>

            <p class="text-2xl font-semibold text-white sm:text-3xl">
                Rp {{ number_format($kasSeharusnya, 0, ',', '.') }}
            </p>
        </div>

        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="rounded-2xl border border-gray-100 bg-white p-5">
                <p class="mb-1 text-xs text-gray-400">
                    Omzet Tunai
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    Rp {{ number_format($omzetTunai, 0, ',', '.') }}
                </p>
            </div>

            <div class="rounded-2xl border border-gray-100 bg-white p-5">
                <p class="mb-1 text-xs text-gray-400">
                    Kas Keluar
                </p>

                <p class="text-lg font-semibold text-gray-800">
                    Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Form catat kas keluar --}}
        <div class="mb-6 rounded-2xl border border-gray-100 bg-white p-5 sm:p-6">
            <p class="mb-4 text-sm font-medium text-gray-700">
                Catat Kas Keluar
            </p>

            <form
                action="{{ route('kas-keluar.store') }}"
                method="POST"
                class="space-y-4"
            >
                @csrf

                <div>
                    <label class="mb-2 block text-xs text-gray-500">
                        Nominal
                    </label>

                    <x-currency-input
                        name="nominal"
                        placeholder="Contoh: 20.000"
                        required
                    />

                    @error('nominal')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-xs text-gray-500">
                        Keterangan
                    </label>

                    <input
                        type="text"
                        name="keterangan"
                        value="{{ old('keterangan') }}"
                        placeholder="Misal: diambil Kak Ary untuk beli bensin"
                        class="w-full rounded-xl border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-coral-400 focus:ring-coral-400"
                    >

                    @error('keterangan')
                        <p class="mt-1 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="pt-1">
                    <button
                        type="submit"
                        class="w-full rounded-xl bg-gradient-to-r from-coral-400 to-coral-500 px-4 py-2.5 text-sm font-medium text-white sm:w-auto"
                    >
                        Catat Kas Keluar
                    </button>
                </div>
            </form>
        </div>

        {{-- Riwayat kas keluar hari ini --}}
        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white">
            <div class="px-5 pb-3 pt-5 sm:px-6">
                <p class="text-sm font-medium text-gray-700">
                    Riwayat Hari Ini
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[650px] text-left text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="whitespace-nowrap px-5 py-3 font-medium">
                                Waktu
                            </th>

                            <th class="whitespace-nowrap px-5 py-3 font-medium">
                                Nominal
                            </th>

                            <th class="px-5 py-3 font-medium">
                                Keterangan
                            </th>

                            <th class="whitespace-nowrap px-5 py-3 font-medium">
                                Dicatat Oleh
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($kasKeluarList as $kk)
                            <tr class="text-gray-700">
                                <td class="whitespace-nowrap px-5 py-3">
                                    {{ $kk->created_at->format('H:i') }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-3 font-medium">
                                    Rp {{ number_format($kk->nominal, 0, ',', '.') }}
                                </td>

                                <td class="px-5 py-3">
                                    {{ $kk->keterangan }}
                                </td>

                                <td class="whitespace-nowrap px-5 py-3">
                                    {{ $kk->inputBy->name ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td
                                    colspan="4"
                                    class="px-5 py-8 text-center text-gray-500"
                                >
                                    Belum ada kas keluar hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>