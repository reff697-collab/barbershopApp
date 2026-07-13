<x-app-layout>
    <div class="p-6 max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-medium">Daftar Layanan</h1>
            <a href="{{ route('services.create') }}"
               class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                + Tambah Layanan
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Harga</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($services as $service)
                        <tr>
                            <td class="px-4 py-3">{{ $service->nama }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($service->harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                @if ($service->is_active)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Aktif</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <a href="{{ route('services.edit', $service) }}"
                                   class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('services.destroy', $service) }}" method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Yakin mau hapus layanan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                Belum ada layanan. Klik "Tambah Layanan" untuk mulai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $services->links() }}
        </div>
    </div>
</x-app-layout>