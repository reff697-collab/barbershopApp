<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-medium">Pengeluaran Operasional</h1>
            <a href="{{ route('expenses.create') }}"
               class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                + Catat Pengeluaran
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-gray-50 rounded-lg p-4 mb-6 flex items-center justify-between">
            <span class="text-sm font-medium">Total Pengeluaran Bulan Ini</span>
            <span class="text-lg font-semibold">Rp {{ number_format($totalBulanIni, 0, ',', '.') }}</span>
        </div>

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Nominal</th>
                        <th class="px-4 py-3">Input Oleh</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($expenses as $expense)
                        <tr>
                            <td class="px-4 py-3">{{ $expense->tanggal->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $expense->nama }}</td>
                            <td class="px-4 py-3">
                                @if ($expense->kategori === 'rutin')
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">Rutin</span>
                                @else
                                    <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded text-xs">Insidental</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">Rp {{ number_format($expense->nominal, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">{{ $expense->inputBy->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="{{ route('expenses.edit', $expense) }}"
                                   class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('expenses.destroy', $expense) }}" method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Yakin mau hapus pengeluaran ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                Belum ada pengeluaran tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $expenses->links() }}
        </div>
    </div>
</x-app-layout>