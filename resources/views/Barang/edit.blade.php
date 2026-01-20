@extends('layouts.app', [
    'activePage' => 'barang',
])

@section('content')
<div class="p-6 min-h-screen bg-slate-50">
    <div class="max-w-4xl mx-auto">
        {{-- Breadcrumb --}}
        <nav class="flex mb-4 text-sm text-slate-500">
            <a href="{{ route('Barang.index') }}" class="hover:text-teal-600 transition-colors">Data Barang</a>
            <span class="mx-2">/</span>
            <span class="text-slate-900 font-medium">Edit Inventaris</span>
        </nav>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-xl font-bold text-slate-900 flex items-center">
                    <i class="fas fa-edit mr-2 text-teal-600"></i> Edit Data Inventaris
                </h2>
                <p class="text-sm text-slate-500 mt-1">Perbarui detail barang. Pastikan total foto tetap berjumlah 4.</p>
            </div>

            <form id="formBarang" action="{{ route('Barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- SECTION 1: Identitas Barang --}}
                    <div class="md:col-span-2">
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Identitas Barang</h3>
                        <hr class="mt-2 border-slate-100">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Barang</label>
                        <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all outline-none text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kode Barang</label>
                        <input type="text" name="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all outline-none text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">NUP</label>
                        <input type="text" name="nup" value="{{ old('nup', $barang->nup) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all outline-none text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kondisi</label>
                        <select name="kondisi" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all outline-none text-sm bg-white">
                            <option value="Baik" {{ $barang->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak Ringan" {{ $barang->kondisi == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Rusak Berat" {{ $barang->kondisi == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                    </div>

                    {{-- SECTION 2: Lokasi (Readonly) --}}
                    <div class="md:col-span-2 p-5 bg-slate-50 rounded-2xl border border-slate-200">
                        <label class="block text-sm font-bold text-slate-700 mb-3"><i class="fas fa-map-marker-alt mr-2"></i> Lokasi Terdaftar</label>
                        <input type="text" value="{{ $barang->lokasi }}" readonly class="w-full px-4 py-2.5 rounded-lg bg-slate-100 border border-slate-200 text-slate-500 text-sm outline-none cursor-not-allowed mb-3">
                        <p class="text-[11px] text-slate-400 italic">*Lokasi GPS diambil saat pendaftaran pertama.</p>
                    </div>

                    {{-- SECTION 3: Foto (Cicil/Hapus) --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Kelola Foto <span id="photo-counter" class="text-teal-600">(0/4)</span>
                        </label>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4" id="preview-container">
                            {{-- Foto lama akan dimuat di sini via JavaScript --}}
                        </div>

                        <div id="drop-area" class="relative group border-2 border-slate-200 border-dashed rounded-xl p-8 transition-all hover:border-teal-400 text-center cursor-pointer">
                            <input type="file" id="file-upload" name="fotoBarang[]" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" multiple>
                            <div class="space-y-2">
                                <i class="fas fa-plus-circle text-slate-300 text-3xl"></i>
                                <p class="text-sm text-slate-600 font-medium">Tambah Foto Baru</p>
                                <p class="text-xs text-slate-400">Total foto harus tetap 4</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('Barang.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-teal-600 text-white font-semibold text-sm hover:bg-teal-700 shadow-lg shadow-teal-600/20 transition-all flex items-center">
                        <i class="fas fa-sync mr-2"></i> Update Inventaris
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const fileInput = document.getElementById('file-upload');
    const previewContainer = document.getElementById('preview-container');
    const photoCounter = document.getElementById('photo-counter');
    const form = document.getElementById('formBarang');

    // Simpan data foto yang sudah ada dari database
    // Asumsi: $barang->foto adalah array/JSON nama file
    let existingPhotos = @json(json_decode($barang->foto_barang) ?? []);
    let newFiles = new DataTransfer();

    function renderPreviews() {
        previewContainer.innerHTML = '';
        let currentCount = 0;

        // Tampilkan Foto Lama
        existingPhotos.forEach((photo, index) => {
            const div = document.createElement('div');
            div.className = "relative aspect-square rounded-xl overflow-hidden border border-slate-200 shadow-sm group";
            div.innerHTML = `
                <img src="/storage/barang/${photo}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <button type="button" onclick="removeExisting(${index})" class="bg-red-500 text-white p-2 rounded-lg text-xs">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
                <input type="hidden" name="existing_photos[]" value="${photo}">
            `;
            previewContainer.appendChild(div);
            currentCount++;
        });

        // Tampilkan Preview Foto Baru
        Array.from(newFiles.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = "relative aspect-square rounded-xl overflow-hidden border border-teal-200 shadow-sm group";
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <button type="button" onclick="removeNew(${index})" class="bg-red-500 text-white p-2 rounded-lg text-xs">
                            <i class="fas fa-times"></i> Batal
                        </button>
                    </div>
                `;
                previewContainer.appendChild(div);
            }
            reader.readAsDataURL(file);
            currentCount++;
        });

        updateStatus(currentCount);
    }

    function removeExisting(index) {
        existingPhotos.splice(index, 1);
        renderPreviews();
    }

    function removeNew(index) {
        const dt = new DataTransfer();
        const files = newFiles.files;
        for (let i = 0; i < files.length; i++) {
            if (i !== index) dt.items.add(files[i]);
        }
        newFiles = dt;
        renderPreviews();
    }

    fileInput.addEventListener('change', function() {
        for (let i = 0; i < this.files.length; i++) {
            if (existingPhotos.length + newFiles.items.length >= 4) {
                Swal.fire('Info', 'Total foto tidak boleh lebih dari 4.', 'info');
                break;
            }
            newFiles.items.add(this.files[i]);
        }
        renderPreviews();
    });

    function updateStatus(count) {
        photoCounter.innerText = `(${count}/4)`;
        photoCounter.className = count === 4 ? "text-green-600 font-bold" : "text-teal-600";
    }

    form.addEventListener('submit', function(e) {
        fileInput.files = newFiles.files;
        const total = existingPhotos.length + newFiles.items.length;

        if (total !== 4) {
            e.preventDefault();
            Swal.fire('Gagal', 'Total foto harus tepat 4 buah.', 'error');
        }
    });

    // Jalankan render saat pertama kali halaman dimuat
    window.onload = renderPreviews;
</script>
@endsection
