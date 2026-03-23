<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Anggota Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    @if($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <ul class="list-disc pl-5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.members.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Lengkap -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="full_name" value="{{ old('full_name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>

                            <!-- Panggilan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Panggilan</label>
                                <input type="text" name="nickname" value="{{ old('nickname') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <!-- Gender -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <select name="gender" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>

                            <!-- Tgl Lahir -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                                <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <!-- Tempat Lahir -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                                <input type="text" name="birth_place" value="{{ old('birth_place') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <!-- Nomor HP -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nomor Telepon PRIBADI (Tersembunyi)</label>
                                <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                        </div>

                        <!-- Ayah -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ayah</label>
                                <select name="father_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" style="max-width: 100%;">
                                    <option value="">-- Tidak Diketahui --</option>
                                    @foreach($allMembers as $m)
                                        @if($m->gender == 'male')
                                            <option value="{{ $m->id }}" {{ old('father_id') == $m->id ? 'selected' : '' }}>{{ $m->full_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <!-- Ibu -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ibu</label>
                                <select name="mother_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" style="max-width: 100%;">
                                    <option value="">-- Tidak Diketahui --</option>
                                    @foreach($allMembers as $m)
                                        @if($m->gender == 'female')
                                            <option value="{{ $m->id }}" {{ old('mother_id') == $m->id ? 'selected' : '' }}>{{ $m->full_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pekerjaan PRIBADI (Tersembunyi)</label>
                                <input type="text" name="occupation" value="{{ old('occupation') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Alamat PRIBADI (Tersembunyi)</label>
                                <textarea name="current_address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('current_address') }}</textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Upload Foto Profil (Opsional)</label>
                                <input type="file" name="photo" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="text-xs text-gray-500 mt-1">Maks 2MB. Format: JPG, PNG, WEBP.</p>
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end gap-3">
                            <a href="{{ route('admin.members.index') }}" class="px-5 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</a>
                            <button type="submit" class="px-5 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">Simpan Anggota</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
