<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">
            Manajemen User
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

        {{-- Tombol Tambah User --}}
        <div class="mb-6 flex justify-end">
            <a href="{{ route('users.create') }}"
               class="rounded-xl bg-gradient-to-r from-coral-400 to-coral-500 px-4 py-2.5 text-sm font-medium text-white hover:from-coral-500 hover:to-coral-600 shadow-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah User
            </a>
        </div>

        {{-- Tabel Manajemen User --}}
        <div class="mb-6">
            <div class="overflow-hidden rounded-xl sm:rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[640px] text-left text-sm">
                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                <th class="px-4 py-3 font-medium sm:px-6">Nama</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Email</th>
                                <th class="px-4 py-3 font-medium sm:px-6">Role</th>
                                <th class="px-4 py-3 font-medium sm:px-6 text-right">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse ($users as $user)
                                <tr class="text-gray-700 hover:bg-gray-50/70">
                                    <td class="px-4 py-3 sm:px-6 font-medium text-gray-800">
                                        {{ $user->name }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-gray-600">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-4 py-3 sm:px-6">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse ($user->roles as $role)
                                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-[10px] sm:text-xs font-medium text-gray-700">
                                                    {{ $role->name }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-gray-400 italic">
                                                    Tanpa role
                                                </span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 sm:px-6 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('users.edit', $user) }}"
                                               class="text-xs sm:text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                                                Edit
                                            </a>
                                            <form action="{{ route('users.destroy', $user) }}" method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('Yakin mau hapus user ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-xs sm:text-sm font-medium text-red-600 hover:text-red-800 transition-colors">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center">
                                        <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-gray-100 text-gray-400 mx-auto mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                            </svg>
                                        </div>

                                        <p class="text-sm font-medium text-gray-600">
                                            Belum ada data user
                                        </p>
                                        <p class="text-xs sm:text-sm text-gray-400 mt-1">
                                            Silakan tambahkan user baru menggunakan tombol di atas.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $users->links() }}
        </div>

    </div>
</x-app-layout>