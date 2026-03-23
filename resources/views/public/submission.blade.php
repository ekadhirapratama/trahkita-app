<x-public-layout>
    <div class="flex items-center justify-between w-full mb-6 relative px-2">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('member.profile', $member->id) }}"
            class="flex items-center text-primary active:scale-95 duration-200 p-2 -ml-2 rounded-full hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
        </a>
        <h1 class="font-headline text-xl font-bold tracking-tight text-primary">Ajukan Perubahan</h1>
        <div class="w-10"></div> <!-- Spacer for balance -->
    </div>

    <section class="max-w-2xl mx-auto w-full px-2">
        <h2 class="text-3xl font-extrabold text-primary leading-tight mb-2">Ajukan Perubahan atau Koreksi</h2>
        <p class="text-secondary text-lg leading-relaxed font-body">Bantu kami menjaga akurasi silsilah keluarga besar
            kita tetap terjaga dengan baik untuk Profil <strong>{{ $member->full_name }}</strong>.</p>
    </section>

    <form action="{{ route('member.suggest.store', $member->id) }}" method="POST" enctype="multipart/form-data"
        class="space-y-10 mt-8 max-w-2xl mx-auto w-full px-2" x-data="{ fileName: null }">
        @csrf

        @if(session('success'))
            <div
                class="flex items-start gap-3 p-4 bg-primary-fixed/30 border border-primary-fixed rounded-xl shadow-sm mb-6">
                <span class="material-symbols-outlined text-primary mt-0.5">check_circle</span>
                <p class="text-sm text-on-primary-fixed leading-relaxed font-semibold">
                    {{ session('success') }}
                </p>
            </div>
        @endif

        @if($errors->any())
            <div
                class="p-4 bg-error-container text-on-error-container rounded-xl text-sm mb-6 shadow-sm border border-error/20">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Mandatory Fields Group -->
        <div class="space-y-6">
            <div class="space-y-4">
                <label class="block text-primary font-bold text-lg tracking-tight font-headline">Jenis Perubahan <span
                        class="text-error">*</span></label>
                <div class="grid grid-cols-1 gap-3">
                    <label
                        class="flex items-center p-4 bg-surface-container-lowest rounded-xl cursor-pointer border-2 border-transparent has-[:checked]:border-primary transition-all shadow-sm">
                        <input class="w-5 h-5 text-primary border-outline focus:ring-primary" name="submission_type"
                            type="radio" value="add" {{ old('submission_type', 'add') === 'add' ? 'checked' : '' }}
                            required />
                        <div class="ml-4">
                            <span class="block font-bold text-on-surface font-headline">Tambah Anggota Baru</span>
                            <span class="text-sm text-on-surface-variant font-body">Menambahkan kelahiran atau anggota
                                keluarga yang belum terdaftar.</span>
                        </div>
                    </label>
                    <label
                        class="flex items-center p-4 bg-surface-container-lowest rounded-xl cursor-pointer border-2 border-transparent has-[:checked]:border-primary transition-all shadow-sm">
                        <input class="w-5 h-5 text-primary border-outline focus:ring-primary" name="submission_type"
                            type="radio" value="update" {{ old('submission_type') === 'update' ? 'checked' : '' }}
                            required />
                        <div class="ml-4">
                            <span class="block font-bold text-on-surface font-headline">Koreksi Data</span>
                            <span class="text-sm text-on-surface-variant font-body">Memperbaiki kesalahan nama, tanggal,
                                atau foto pada profil yang ada.</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="space-y-2 pt-6">
                <label class="block text-primary font-bold text-lg tracking-tight font-headline">Usulan Perubahan <span
                        class="text-error">*</span></label>
                <textarea name="usulan" required
                    class="w-full bg-surface-container-high border-none rounded-xl p-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary transition-all font-body"
                    placeholder="Contoh: Tambahkan putra ketiga dari Bapak Ahmad bernama Budi Santoso, lahir 12 Mei 1995..."
                    rows="4">{{ old('usulan') }}</textarea>
            </div>

            <div class="space-y-2 pt-6">
                <label class="block text-primary font-bold text-lg tracking-tight font-headline">Unggah Dokumen atau
                    Foto</label>
                <label
                    class="block border-2 border-dashed border-outline-variant rounded-2xl p-8 text-center bg-surface-container-lowest hover:bg-surface-container-low transition-colors group cursor-pointer relative shadow-sm">
                    <input type="file" name="photo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                        accept="image/jpeg,image/png,image/webp" @change="fileName = $event.target.files[0]?.name">

                    <span
                        class="material-symbols-outlined text-4xl text-outline mb-2 group-hover:scale-110 transition-transform">cloud_upload</span>
                    <p class="font-bold text-on-surface font-headline"
                        x-text="fileName ? 'File terpilih:' : 'Ketuk untuk unggah file'"></p>
                    <p class="text-sm font-semibold text-primary/80 mt-1" x-show="fileName" x-text="fileName"></p>
                    <p class="text-xs text-on-surface-variant mt-1 font-body" x-show="!fileName">Maksimal 2MB. Format:
                        JPG, PNG, atau WEBP.</p>
                </label>
            </div>
        </div>

        <!-- Identity Group -->
        <div class="bg-surface-container-low p-6 rounded-2xl space-y-6 shadow-sm border border-outline-variant/10">
            <div>
                <h3 class="text-xl font-bold text-on-secondary-container font-headline">Identitas Pengirim (Opsional)
                </h3>
                <p class="text-sm text-secondary font-body mt-1">Kosongkan jika Anda ingin mengirimkan usulan secara
                    anonim.</p>
            </div>
            <div class="space-y-4">
                <div class="space-y-2">
                    <label class="block text-on-surface font-semibold text-sm font-label">Nama Lengkap</label>
                    <input name="submitter_name" value="{{ old('submitter_name') }}"
                        class="w-full bg-surface-container-lowest border-none rounded-xl p-4 focus:ring-2 focus:ring-primary font-body"
                        type="text" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="block text-on-surface font-semibold text-sm font-label">Email</label>
                        <input name="submitter_email" value="{{ old('submitter_email') }}"
                            class="w-full bg-surface-container-lowest border-none rounded-xl p-4 focus:ring-2 focus:ring-primary font-body"
                            type="email" />
                    </div>
                    <div class="space-y-2">
                        <label class="block text-on-surface font-semibold text-sm font-label">Nomor Telepon</label>
                        <input name="submitter_phone" value="{{ old('submitter_phone') }}"
                            class="w-full bg-surface-container-lowest border-none rounded-xl p-4 focus:ring-2 focus:ring-primary font-body"
                            type="tel" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="space-y-6">
            <div class="space-y-2">
                <label class="block text-primary font-bold text-lg tracking-tight font-headline">Alasan / Catatan Admin
                    (Opsional)</label>
                <textarea name="reason"
                    class="w-full bg-surface-container-high border-none rounded-xl p-4 text-on-surface placeholder:text-outline focus:ring-2 focus:ring-primary font-body"
                    placeholder="Jelaskan alasan perubahan ini dilakukan..." rows="3">{{ old('reason') }}</textarea>
            </div>
        </div>

        <!-- Submit Button Area -->
        <div class="pt-6 relative pb-10">
            <button
                class="w-full py-5 bg-primary text-on-primary rounded-xl font-bold text-lg shadow-xl shadow-primary/20 active:scale-95 duration-200 ease-out flex items-center justify-center gap-2 hover:bg-primary-container transition-colors font-headline group"
                type="submit">
                <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">send</span>
                Kirim Usulan
            </button>
            <div
                class="flex items-start gap-3 p-4 bg-tertiary-container/30 rounded-xl mt-6 border border-tertiary-container/50">
                <span class="material-symbols-outlined text-tertiary mt-0.5" data-icon="info">info</span>
                <p class="text-sm text-on-tertiary-container leading-relaxed font-body">
                    Terima kasih, perubahan Anda akan ditinjau koordinator keluarga sebelum tersimpan pada pusat data
                    interaktif.
                </p>
            </div>
        </div>
    </form>
</x-public-layout>