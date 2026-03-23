<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Pengajuan (Approved/Rejected/Pending)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <!-- Filters -->
                    <form method="GET" action="{{ route('admin.submissions.index') }}" class="mb-6 flex gap-4 items-center">
                        <select name="status" class="border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Tertunda (Pending)</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Disetujui (Approved)</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                        </select>
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">Filter</button>
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
                                    <th class="py-4 px-6 bg-gray-50 font-bold text-sm text-gray-700 border-b">Tanggal</th>
                                    <th class="py-4 px-6 bg-gray-50 font-bold text-sm text-gray-700 border-b">Member Dituju</th>
                                    <th class="py-4 px-6 bg-gray-50 font-bold text-sm text-gray-700 border-b">Jenis Perubahan</th>
                                    <th class="py-4 px-6 bg-gray-50 font-bold text-sm text-gray-700 border-b">Pengirim</th>
                                    <th class="py-4 px-6 bg-gray-50 font-bold text-sm text-gray-700 border-b">Status</th>
                                    <th class="py-4 px-6 bg-gray-50 font-bold text-sm text-gray-700 border-b">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $sub)
                                <tr class="hover:bg-gray-50 border-b">
                                    <td class="py-4 px-6 text-sm text-gray-600">
                                        {{ $sub->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-800 font-semibold">
                                        {{ optional($sub->targetMember)->full_name ?? 'Tidak diketahui' }}
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-600">
                                        @if($sub->submission_type === 'add')
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-semibold">Tambah Baru</span>
                                        @else
                                            <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs font-semibold">Koreksi Data</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-600">
                                        {{ $sub->submitter_name ?: 'Anonim' }} <br><span class="text-xs text-gray-400">{{ $sub->submitter_phone ?? $sub->submitter_email }}</span>
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($sub->status === 'pending')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold ring-1 ring-yellow-300">Tertunda</span>
                                        @elseif($sub->status === 'approved')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold ring-1 ring-green-300">Disetujui</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold ring-1 ring-red-300">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-sm flex gap-3">
                                        <a href="{{ route('admin.submissions.show', $sub->id) }}" class="text-indigo-600 hover:text-indigo-800 font-medium hover:underline">Tinjau Detail</a>
                                        <form method="POST" action="{{ route('admin.submissions.destroy', $sub->id) }}" onsubmit="return confirm('Daftar pengajuan ini akan dihapus permanen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                @if($submissions->count() === 0)
                                    <tr>
                                        <td colspan="6" class="text-center py-8 text-gray-500">Tidak ada pengajuan yang ditemukan.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $submissions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
