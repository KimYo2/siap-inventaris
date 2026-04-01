@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div
            class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm p-6 sm:p-8 transition-colors">

            <div class="mb-6">
                <h1 class="text-xl font-bold text-slate-800 dark:text-white">Edit User</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $user->nama }} ({{ $user->nip }})</p>
            </div>

            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="nip"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">NIP</label>
                    <input type="text" name="nip" id="nip" value="{{ old('nip', $user->nip) }}" required maxlength="18"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                    @error('nip')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nama"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Nama
                        Lengkap</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $user->nama) }}" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role"
                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Role</label>
                    <select name="role" id="role" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User (Pegawai)
                        </option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div
                    class="bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-300 px-4 py-3 rounded-lg text-sm flex items-start gap-2 border border-amber-100 dark:border-amber-800">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>Kosongkan password jika tidak ingin mengubahnya.</p>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="password"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Password
                            Baru</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition"
                            placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">Konfirmasi
                            Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-900 dark:text-white transition">
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.users.index') }}"
                        class="px-5 py-2.5 text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 rounded-lg font-medium transition">Batal</a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-8 py-2.5 rounded-lg font-medium shadow-sm transition">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>

    </div>
@endsection
