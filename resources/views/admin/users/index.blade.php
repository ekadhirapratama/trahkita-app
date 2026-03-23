<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Akun Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Formulir Tambah Admin -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Tambahkan Akun Admin Baru</h3>
                    
                    @if($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <ul class="list-disc pl-5 text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('new_admin'))
                        <div class="mb-6 p-5 border-2 border-green-500 bg-green-50 rounded-lg">
                            <h4 class="font-bold text-green-800 mb-2">Akun Berhasil Dibuat!</h4>
                            <p class="text-sm text-green-700 mb-4">Silakan salin password dan bagikan ke pengelola akun baru ini. Password ini <strong>hanya muncul satu kali</strong> dan tidak dapat dilihat lagi.</p>
                            <div class="bg-white p-4 border border-green-200 rounded grid grid-cols-[100px_1fr] gap-2 mb-4 font-mono text-sm">
                                <span class="font-bold text-gray-600">Username:</span>
                                <span>{{ session('new_admin')['username'] }}</span>
                                <span class="font-bold text-gray-600">Password:</span>
                                <span class="text-red-600 font-bold" id="new-password">{{ session('new_admin')['password'] }}</span>
                            </div>
                            <button onclick="navigator.clipboard.writeText('Username: {{ session('new_admin')['username'] }}\nPassword: {{ session('new_admin')['password'] }}'); alert('Tersalin ke clipboard!');" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded text-sm font-semibold shadow-sm flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">content_copy</span> Salin Kredensial Akses
                            </button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.users.store') }}" class="flex gap-4 items-end">
                        @csrf
                        <div class="w-full max-w-sm">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Username <span class="text-red-500">*</span></label>
                            <input type="text" name="name" placeholder="Misal: hendra_admin" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200" required>
                        </div>
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md shadow-sm">
                            Buat Akun
                        </button>
                    </form>
                    <p class="text-xs text-gray-500 mt-3">*Sistem akan menghasilkan kata sandi tingkat tinggi secara otomatis untuk setiap akun admin baru secara acak.</p>
                </div>
            </div>

            <!-- Daftar Admin -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Daftar Akun Pengelola Admin</h3>

                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="py-3 px-6 bg-gray-50 font-bold text-sm text-gray-700 border-b">ID</th>
                                    <th class="py-3 px-6 bg-gray-50 font-bold text-sm text-gray-700 border-b">Username</th>
                                    <th class="py-3 px-6 bg-gray-50 font-bold text-sm text-gray-700 border-b">Dibuat Sejak</th>
                                    <th class="py-3 px-6 bg-gray-50 font-bold text-sm text-gray-700 border-b">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr class="hover:bg-gray-50 border-b">
                                    <td class="py-3 px-6 text-sm text-gray-600">{{ $user->id }}</td>
                                    <td class="py-3 px-6 text-sm text-gray-800 font-semibold">{{ $user->name }} @if(Auth::id() === $user->id) <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded ml-2">Anda</span> @endif</td>
                                    <td class="py-3 px-6 text-sm text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                                    <td class="py-3 px-6 text-sm flex gap-3">
                                        @if(Auth::id() !== $user->id)
                                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Yakin ingin menghapus akses admin untuk {{ $user->name }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 hover:underline">Revoke Akses</button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 cursor-not-allowed">Hapus Tertutup</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
