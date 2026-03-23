<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tinjauan Detail Pengajuan') }} #{{ $submission->id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Submitters & Status Area -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <!-- Sender info -->
                        <div>
                            <h3 class="text-lg font-bold text-gray-800 mb-2">Informasi Pengirim</h3>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li><strong>Nama:</strong> {{ $submission->submitter_name ?: 'Anonim' }}</li>
                                <li><strong>Email:</strong> {{ $submission->submitter_email ?? '-' }}</li>
                                <li><strong>Telepon:</strong> {{ $submission->submitter_phone ?? '-' }}</li>
                                <li><strong>Waktu Submit:</strong> {{ $submission->created_at->format('d M Y, H:i') }}</li>
                            </ul>
                        </div>

                        <!-- Status Badge -->
                        <div class="text-right">
                            <div class="mb-2">
                                @if($submission->status === 'pending')
                                    <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg text-sm font-bold ring-1 ring-yellow-300">Tertunda (Pending)</span>
                                @elseif($submission->status === 'approved')
                                    <span class="px-4 py-2 bg-green-100 text-green-800 rounded-lg text-sm font-bold ring-1 ring-green-300">Disetujui (Approved)</span>
                                @else
                                    <span class="px-4 py-2 bg-red-100 text-red-800 rounded-lg text-sm font-bold ring-1 ring-red-300">Ditolak (Rejected)</span>
                                @endif
                            </div>
                            @if($submission->reviewer)
                                <p class="text-xs text-gray-500">Ditinjau oleh: {{ $submission->reviewer->name }}</p>
                                <p class="text-xs text-gray-400">{{ $submission->updated_at->format('d/m/Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Suggestion Detail Area -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Detail Perubahan yang Diusulkan</h3>

                    <div class="mb-5">
                        <span class="text-sm font-bold text-gray-600 block mb-1">Target Anggota (Profil Induk)</span>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-gray-400 text-sm">person</span>
                            <span class="font-medium text-gray-800">{{ optional($submission->targetMember)->full_name ?? 'Profil Tidak Diketahui/Dihapus' }}</span>
                        </div>
                    </div>

                    <div class="mb-5">
                        <span class="text-sm font-bold text-gray-600 block mb-1">Jenis Perubahan</span>
                        @if($submission->submission_type === 'add')
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded text-sm font-semibold">Tambah Anggota Baru</span>
                        @else
                            <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded text-sm font-semibold">Koreksi Data</span>
                        @endif
                    </div>

                    <div class="mb-5">
                        <span class="text-sm font-bold text-gray-600 block mb-2">Pesan Usulan Perubahan</span>
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 text-gray-700 whitespace-pre-wrap font-medium">@php
                            $data = is_string($submission->submitted_data) ? json_decode($submission->submitted_data, true) : $submission->submitted_data;
                            echo htmlspecialchars($data['usulan'] ?? '-');
                        @endphp</div>
                    </div>

                    @if($submission->reason)
                    <div class="mb-5">
                        <span class="text-sm font-bold text-gray-600 block mb-2">Alasan / Catatan Pengirim</span>
                        <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200 text-gray-700 whitespace-pre-wrap italic">{{ $submission->reason }}</div>
                    </div>
                    @endif

                    <!-- Photos -->
                    @if($submission->photo_path)
                    <div class="mb-5">
                        <span class="text-sm font-bold text-gray-600 block mb-2">Lampiran Dokumen/Foto</span>
                        <a href="{{ asset('storage/' . $submission->photo_path) }}" target="_blank" class="block border border-gray-200 rounded-lg p-2 max-w-sm hover:ring-2 hover:ring-blue-500 transition-all">
                            <img src="{{ asset('storage/' . $submission->photo_path) }}" alt="Lampiran Pengajuan" class="w-full h-auto rounded">
                            <p class="text-xs text-center text-blue-600 mt-2 font-medium">Klik untuk memperbesar</p>
                        </a>
                    </div>
                    @endif

                    <!-- Action Panel -->
                    @if($submission->status === 'pending')
                    <div class="mt-8 pt-6 border-t border-gray-200 bg-gray-50 -mx-6 -mb-6 p-6 flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            Silakan cermati pengajuan, lalu ubah status secara manual di bawah. <br><em class="text-xs text-gray-400">(Tugas admin mengaplikasikan perubahan secara manual di halaman Kelola Anggota)</em>
                        </div>
                        <div class="flex gap-3">
                            <form method="POST" action="{{ route('admin.submissions.update_status', $submission->id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md shadow-sm hover:bg-red-700 font-semibold text-sm transition-colors" onclick="return confirm('Tolak usulan ini?')">Tolak</button>
                            </form>
                            <form method="POST" action="{{ route('admin.submissions.update_status', $submission->id) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md shadow-sm hover:bg-green-700 font-semibold text-sm transition-colors" onclick="return confirm('Setujui (Tandai Selesai) usulan ini?')">Setujui (Selesai)</button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <p class="text-gray-500 text-center text-sm font-semibold">Tindakan tidak lagi tersedia. Usulan ini sudah berstatus <span class="uppercase">{{ $submission->status }}</span>.</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="pt-2 text-left">
                <a href="{{ route('admin.submissions.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium hover:underline flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">arrow_back</span> Kembali ke Daftar Pengajuan
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
