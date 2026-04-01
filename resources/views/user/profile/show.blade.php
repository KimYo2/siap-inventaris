@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 shadow-sm p-6 sm:p-8 transition-colors">

            <div class="mb-6">
                <h1 class="text-xl font-bold text-slate-800 dark:text-white">Profil Saya</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Kelola foto profil Anda.</p>
            </div>

            @if(session('success'))
                <div class="mb-4 px-4 py-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-sm text-green-700 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Info --}}
            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-200 dark:border-slate-700">
                <img src="{{ $user->avatar_url }}"
                     alt="{{ $user->name }}"
                     class="w-24 h-24 rounded-full object-cover ring-4 ring-slate-200 dark:ring-slate-700"
                     id="avatar-preview">
                <div>
                    <p class="text-lg font-semibold text-slate-800 dark:text-white">{{ $user->name }}</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500 font-mono mt-1">NIP: {{ $user->nip }}</p>
                </div>
            </div>

            {{-- Upload Form --}}
            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="foto" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">
                        Upload Foto Baru
                        <span class="text-xs text-slate-400 ml-1">(JPG, PNG, WebP. Maks 2MB)</span>
                    </label>
                    <input type="file" name="foto" id="foto" accept="image/*"
                           class="block w-full text-sm text-slate-500
                                  file:mr-4 file:py-2 file:px-4 file:rounded-lg
                                  file:border-0 file:text-sm file:font-medium
                                  file:bg-blue-50 file:text-blue-700
                                  hover:file:bg-blue-100
                                  dark:file:bg-blue-900/30 dark:file:text-blue-300">
                    @error('foto')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                @if($user->foto_path)
                <div>
                    <label class="flex items-center gap-2 text-sm text-red-500 cursor-pointer">
                        <input type="checkbox" name="hapus_foto" value="1">
                        Hapus foto profil
                    </label>
                </div>
                @endif

                <div class="pt-2">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white px-6 py-2.5 rounded-lg font-medium shadow-sm transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>

    </div>

    <script>
    document.getElementById('foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('avatar-preview').src = ev.target.result;
        };
        reader.readAsDataURL(file);
    });
    </script>
@endsection
