<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Anggota') }}
            </h2>
            <a href="{{ route('admin.members.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium">Tambah Anggota</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Search -->
                    <form method="GET" action="{{ route('admin.members.index') }}" class="mb-6 flex gap-2">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau panggilan..." class="border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 w-full sm:w-1/3">
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Cari</button>
                    </form>

                    <!-- Flash messages -->
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="py-4 px-6 bg-gray-50 font-bold text-sm text-gray-700 border-b">Foto</th>
                                    <th class="py-4 px-6 bg-gray-50 font-bold text-sm text-gray-700 border-b">Nama Lengkap</th>
                                    <th class="py-4 px-6 bg-gray-50 font-bold text-sm text-gray-700 border-b">Orang Tua</th>
                                    <th class="py-4 px-6 bg-gray-50 font-bold text-sm text-gray-700 border-b">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($members as $m)
                                <tr class="hover:bg-gray-50 border-b">
                                    <td class="py-4 px-6">
                                        @if($m->photo_path)
                                            <img src="{{ asset('storage/' . $m->photo_path) }}" class="w-10 h-10 rounded-full object-cover">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-800 font-bold">
                                                {{ substr($m->full_name, 0, 1) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-800 font-semibold">
                                        {{ $m->full_name }} <br><span class="text-xs text-gray-500 font-normal">{{ $m->nickname }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-600">
                                        @if($m->father || $m->mother)
                                            {{ optional($m->father)->full_name ?? '-' }} &amp; {{ optional($m->mother)->full_name ?? '-' }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-sm flex gap-3">
                                        <a href="{{ route('admin.members.edit', $m->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline">Edit</a>
                                        <form method="POST" action="{{ route('admin.members.destroy', $m->id) }}" onsubmit="return confirm('Yakin ingin menghapus anggota ini beserta logikanya?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                @if($members->count() === 0)
                                    <tr>
                                        <td colspan="4" class="text-center py-6 text-gray-500">Data anggota tidak ditemukan.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $members->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
