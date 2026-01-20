@extends('layouts.app', ['activePage' => 'barang'])

@section('content')
<div class="p-6 min-h-screen bg-slate-50">
    <div class="max-w-4xl mx-auto">
        <nav class="flex mb-4 text-sm text-slate-500">
            <a href="{{ route('Barang.index') }}" class="hover:text-teal-600">Data Barang</a>
            <span class="mx-2">/</span>
            <span class="text-slate-900 font-medium">Edit Inventaris</span>
        </nav>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-xl font-bold text-slate-900 flex items-center">
                    <i class="fas fa-edit mr-2 text-teal-600"></i> Edit Inventaris: {{ $barang->nama_barang }}
                </h2>
            </div>

            <form id="formBarang" action="{{ route('Barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Identitas --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Barang</label>
                        <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-teal-500/20 focus:border-teal-500 outline-none text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kondisi</label>
                        <select name="kondisi" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm bg-white">
                            <option value="Baik" {{ $barang->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak Ringan" {{ $barang->kondisi == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Rusak Berat" {{ $barang->kondisi == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                    </div>

                    {{-- Lokasi & GPS --}}
                    <div class="md:col-span-2 p-5 bg-teal-50/50 rounded-2xl border border-teal-100">
                        <div class="flex justify-between items-center mb-3">
                            <label class="text-sm font-bold text-teal-800 flex items-center">
                                <i class="fas fa-map-marker-alt mr-2"></i> Lokasi & GPS
                            </label>
                            <button type="button" onclick="updateLocation()" class="text-xs bg-teal-600 text-white px-3 py-1.5 rounded-lg hover:bg-teal-700 transition-all">
                                <i class="fas fa-sync-alt mr-1"></i> Update Lokasi ke Posisi Sekarang
                            </button>
                        </div>

                        <div class="space-y-4">
                            <input type="text" name="lokasi" id="lokasi_nama" value="{{ old('lokasi', $barang->lokasi) }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="latitude" id="lat" value="{{ old('latitude', $barang->latitude) }}" readonly class="bg-white/50 px-4 py-2 rounded-lg border border-slate-200 text-sm outline-none cursor-not-allowed">
                                <input type="text" name="longitude" id="lng" value="{{ old('longitude', $barang->longitude) }}" readonly class="bg-white/50 px-4 py-2 rounded-lg border border-slate-200 text-sm outline-none cursor-not-allowed">
                            </div>
                        </div>
                        <p id="geo-status" class="text-[11px] mt-2 text-slate-500 italic">Gunakan tombol update untuk menyegarkan koordinat GPS.</p>
                    </div>

                    {{-- Pengelolaan Foto --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Foto Barang <span id="photo-counter" class="text-teal-600">(4/4)</span>
                        </label>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4" id="preview-container">
                            {{-- Foto akan dirender oleh JS --}}
                        </div>

                        <div id="drop-area" class="relative group border-2 border-slate-200 border-dashed rounded-xl p-8 text-center cursor-pointer hover:border-teal-400">
                            <input type="file" id="file-upload" name="fotoBarang[]" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" multiple>
                            <div class="space-y-2">
                                <i class="fas fa-plus-circle text-slate-300 text-3xl"></i>
                                <p class="text-sm text-slate-600">Tambah Foto Baru</p>
                                <p class="text-xs text-slate-400">Pastikan total tetap 4 foto</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('Barang.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-teal-600 text-white text-sm font-semibold hover:bg-teal-700 shadow-lg shadow-teal-600/20 flex items-center">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const previewContainer = document.getElementById('preview-container');
    const photoCounter = document.getElementById('photo-counter');
    const fileInput = document.getElementById('file-upload');
    const form = document.getElementById('formBarang');

    // Load foto lama dari database
    let existingPhotos = @json(json_decode($barang->foto_barang) ?? []);
    let newFiles = new DataTransfer();

    function renderPreviews() {
        previewContainer.innerHTML = '';
        let count = 0;

        // Render Foto yang sudah ada
        existingPhotos.forEach((photo, index) => {
            const div = document.createElement('div');
            div.className = "relative aspect-square rounded-xl overflow-hidden border border-slate-200 shadow-sm group";
            div.innerHTML = `
                <img src="/storage/barang/${photo}" class="w-full h-full object-cover">
                <button type="button" onclick="removeExisting(${index})" class="absolute inset-0 bg-red-600/80 text-white opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                    <i class="fas fa-trash mr-2"></i> Hapus
                </button>
                <input type="hidden" name="existing_photos[]" value="${photo}">
            `;
            previewContainer.appendChild(div);
            count++;
        });

        // Render Foto Baru
        Array.from(newFiles.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const div = document.createElement('div');
                div.className = "relative aspect-square rounded-xl overflow-hidden border border-teal-200 shadow-sm group";
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-cover">
                    <button type="button" onclick="removeNew(${index})" class="absolute inset-0 bg-black/60 text-white opacity-0 group-hover:opacity-100 transition-all flex items-center justify-center">
                        <i class="fas fa-times mr-2"></i> Batal
                    </button>
                `;
                previewContainer.appendChild(div);
            }
            reader.readAsDataURL(file);
            count++;
        });

        photoCounter.innerText = `(${count}/4)`;
        photoCounter.className = count === 4 ? "text-green-600 font-bold" : "text-red-500 font-bold";
    }

    function removeExisting(index) { existingPhotos.splice(index, 1); renderPreviews(); }
    function removeNew(index) {
        const dt = new DataTransfer();
        Array.from(newFiles.files).forEach((f, i) => { if(i !== index) dt.items.add(f); });
        newFiles = dt;
        renderPreviews();
    }

    fileInput.addEventListener('change', function() {
        for (let file of this.files) {
            if (existingPhotos.length + newFiles.items.length >= 4) break;
            newFiles.items.add(file);
        }
        renderPreviews();
    });

    // --- Geolocation Update ---
    function updateLocation() {
        const status = document.getElementById('geo-status');
        status.innerText = "Mencari lokasi terbaru...";
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((pos) => {
                document.getElementById('lat').value = pos.coords.latitude;
                document.getElementById('lng').value = pos.coords.longitude;
                fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${pos.coords.latitude}&lon=${pos.coords.longitude}`)
                    .then(r => r.json()).then(data => {
                        document.getElementById('lokasi_nama').value = data.display_name;
                        status.innerText = "Lokasi diperbarui!";
                        status.className = "text-[11px] mt-2 text-green-600 font-bold";
                    });
            }, () => { status.innerText = "Gagal akses GPS."; });
        }
    }

    form.addEventListener('submit', (e) => {
        fileInput.files = newFiles.files;
        if (existingPhotos.length + newFiles.items.length !== 4) {
            e.preventDefault();
            Swal.fire('Error', 'Total foto harus tepat 4.', 'error');
        }
    });

    window.onload = renderPreviews;
</script>
@endsection
