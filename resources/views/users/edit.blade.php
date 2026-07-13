<x-app-layout>
    <div class="p-6 max-w-lg mx-auto">
        <h1 class="text-xl font-medium mb-6">Edit User</h1>

        <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-1">Nama</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="w-full border rounded-md px-3 py-2 text-sm">
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       class="w-full border rounded-md px-3 py-2 text-sm">
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Password Baru</label>
                <input type="password" name="password"
                       class="w-full border rounded-md px-3 py-2 text-sm">
                <p class="text-xs text-gray-500 mt-1">Kosongkan kalau tidak ingin mengubah password.</p>
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Role</label>
                <select name="role" class="w-full border rounded-md px-3 py-2 text-sm">
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" @selected(old('role', $currentRole) === $role)>{{ $role }}</option>
                    @endforeach
                </select>
                @error('role')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md text-sm">
                    Simpan Perubahan
                </button>
                <a href="{{ route('users.index') }}" class="text-sm text-gray-600">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>