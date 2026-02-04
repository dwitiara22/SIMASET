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
                <p class="text-sm text-slate-500 mt-1">Perbarui data aset. Total foto harus tetap berjumlah 4.</p>
            </div>

            {{-- Menampilkan Error Flash --}}
            @if(session('error'))
                <div class="mx-6 mt-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form id="formBarang" action="{{ route('Barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- SECTION 1: Identitas --}}
                    <div class="md:col-span-2">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Identitas Barang</h3>
                        <hr class="mt-2 border-slate-100">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Barang <span class="text-red-500">*</span> </label>
                        <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500/20 outline-none text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kode Barang</label>
                        <input type="text" name="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm outline-none" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">NUP</label>
                        <input type="text" name="nup" value="{{ old('nup', $barang->nup) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm outline-none" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Merek / Type <span class="text-red-500">*</span> </label>
                        <input type="text" name="merek" value="{{ old('merek', $barang->merek) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kondisi <span class="text-red-500">*</span> </label>
                        <select name="kondisi" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm bg-white">
                            <option value="Baik" {{ $barang->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak Ringan" {{ $barang->kondisi == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Rusak Berat" {{ $barang->kondisi == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                    </div>

                    {{-- SECTION 2: Legalitas & Lokasi --}}
                    <div class="md:col-span-2 pt-4">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Legalitas & Penempatan</h3>
                        <hr class="mt-2 border-slate-100">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Perolehan <span class="text-red-500">*</span> </label>
                        <input type="date" name="tgl_peroleh" value="{{ old('tgl_peroleh', $barang->tgl_peroleh) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nilai Perolehan (Rp) <span class="text-red-500">*</span> </label>
                        <input type="number" step="0.01" name="nilai_peroleh" value="{{ old('nilai_peroleh', $barang->nilai_peroleh) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Ruangan <span class="text-red-500">*</span> </label>
                        <input type="text" name="ruangan" value="{{ old('ruangan', $barang->ruangan) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor SK PSP</label>
                        <input type="text" name="nomor_sk_psp" value="{{ old('nomor_sk_psp', $barang->nomor_sk_psp) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all outline-none text-sm"
                            placeholder="Masukkan nomor SK jika ada">
                    </div>

                    <div class="md:col-span-2 p-5 bg-teal-50/50 rounded-2xl border border-teal-100">
                        <div class="flex justify-between items-center mb-3">
                            <label class="text-sm font-bold text-teal-800 flex items-center">
                                <i class="fas fa-map-marker-alt mr-2"></i> Lokasi GPS <span class="text-red-500">*</span>
                            </label>
                            <button type="button" onclick="updateLocation()" class="text-[10px] bg-teal-600 text-white px-3 py-1 rounded-lg hover:bg-teal-700 font-bold uppercase">
                                <i class="fas fa-sync-alt mr-1"></i> Update Lokasi
                            </button>
                        </div>
                        <div class="space-y-4">
                            <input type="text" name="lokasi" id="lokasi_nama" value="{{ old('lokasi', $barang->lokasi) }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 text-sm">
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="latitude" id="lat" value="{{ old('latitude', $barang->latitude) }}" readonly class="bg-slate-100 px-4 py-2 rounded-lg text-xs">
                                <input type="text" name="longitude" id="lng" value="{{ old('longitude', $barang->longitude) }}" readonly class="bg-slate-100 px-4 py-2 rounded-lg text-xs">
                            </div>
                            <p id="geo-status" class="text-[11px] font-bold"></p>
                        </div>
                    </div>

                    {{-- FOTO SECTION --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Foto Inventaris <span id="photo-counter" class="ml-2"></span> <span class="text-red-500">*</span> 
                        </label>

                        {{-- Container Preview --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4" id="preview-container">
                            {{-- JS akan mengisi ini --}}
                        </div>

                        {{-- Area Upload --}}
                        <div id="drop-area" class="relative group border-2 border-slate-200 border-dashed rounded-xl p-8 text-center cursor-pointer transition-all bg-white">
                            <input type="file" id="file-upload" name="fotoBarang[]" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" multiple>
                            <div id="drop-area-content" class="space-y-2">
                                <i class="fas fa-camera text-slate-300 text-3xl"></i>
                                <p class="text-sm text-slate-600 font-medium">Klik untuk tambah foto baru</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('Barang.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-teal-600 text-white text-sm font-semibold hover:bg-teal-700 shadow-lg transition-all">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // 1. Inisialisasi Elemen & Data
    const previewContainer = document.getElementById('preview-container');
    const photoCounter = document.getElementById('photo-counter');
    const fileInput = document.getElementById('file-upload');
    const dropArea = document.getElementById('drop-area');
    const form = document.getElementById('formBarang');

    let existingPhotos = @json(is_array($barang->fotoBarang) ? $barang->fotoBarang : json_decode($barang->fotoBarang, true) ?? []);
    let newFilesDt = new DataTransfer();

    // 2. Fungsi Render Foto (Preview)
    function renderPreviews() {
        previewContainer.innerHTML = '';
        let totalCount = 0;

        existingPhotos.forEach((photo, index) => {
            const div = document.createElement('div');
            div.className = "relative aspect-square rounded-xl overflow-hidden border border-slate-200 group";
            div.innerHTML = `
                <img src="/storage/${photo}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-red-600/90 text-white opacity-0 group-hover:opacity-100 transition-all flex flex-col items-center justify-center cursor-pointer" onclick="removeExisting(${index})">
                    <i class="fas fa-trash-alt text-xl mb-1"></i>
                    <span class="text-[10px] font-bold uppercase">Hapus</span>
                </div>
                <input type="hidden" name="existing_photos[]" value="${photo}">
            `;
            previewContainer.appendChild(div);
            totalCount++;
        });

        Array.from(newFilesDt.files).forEach((file, index) => {
            const url = URL.createObjectURL(file);
            const div = document.createElement('div');
            div.className = "relative aspect-square rounded-xl overflow-hidden border-2 border-teal-500 group";
            div.innerHTML = `
                <img src="${url}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/70 text-white opacity-0 group-hover:opacity-100 transition-all flex flex-col items-center justify-center cursor-pointer" onclick="removeNew(${index})">
                    <i class="fas fa-times text-xl mb-1"></i>
                    <span class="text-[10px] font-bold uppercase">Batal</span>
                </div>
            `;
            previewContainer.appendChild(div);
            totalCount++;
        });

        photoCounter.innerText = `(${totalCount}/4)`;
        fileInput.disabled = (totalCount >= 4);
        if (dropArea) {
            dropArea.style.opacity = (totalCount >= 4) ? "0.5" : "1";
            dropArea.style.pointerEvents = (totalCount >= 4) ? "none" : "auto";
        }
    }

    // 3. Event Listeners untuk Foto
    window.removeExisting = function(index) {
        existingPhotos.splice(index, 1);
        renderPreviews();
    }

    window.removeNew = function(index) {
        const dt = new DataTransfer();
        for (let i = 0; i < newFilesDt.files.length; i++) {
            if (i !== index) dt.items.add(newFilesDt.files[i]);
        }
        newFilesDt = dt;
        renderPreviews();
    }

    fileInput.addEventListener('change', function() {
        for (let file of this.files) {
            if (existingPhotos.length + newFilesDt.items.length < 4) {
                newFilesDt.items.add(file);
            }
        }
        this.value = '';
        renderPreviews();
    });

    form.addEventListener('submit', function(e) {
        fileInput.disabled = false;
        fileInput.files = newFilesDt.files;
        const total = existingPhotos.length + newFilesDt.items.length;
        if (total !== 4) {
            e.preventDefault();
            Swal.fire('Gagal', `Total foto wajib 4. (Sekarang: ${total})`, 'error');
        }
    });

    // 4. LOGIK GPS (Dipastikan Terbuka ke Global/Window)
    window.updateLocation = function() {
        const status = document.getElementById('geo-status');
        const latInput = document.getElementById('lat');
        const lngInput = document.getElementById('lng');
        const lokasiInput = document.getElementById('lokasi_nama');

        status.innerText = "Mencari koordinat...";
        status.className = "text-[11px] text-teal-600 animate-pulse";

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;

                    latInput.value = lat;
                    lngInput.value = lng;

                    // Reverse Geocoding menggunakan Nominatim (OpenStreetMap)
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                        .then(r => r.json())
                        .then(data => {
                            lokasiInput.value = data.display_name;
                            status.innerText = "Lokasi berhasil diperbarui!";
                            status.className = "text-[11px] text-green-600 font-bold";
                        })
                        .catch(err => {
                            console.error(err);
                            status.innerText = "Koordinat didapat, gagal mengambil nama alamat.";
                        });
                },
                (error) => {
                    let msg = "Gagal akses lokasi.";
                    if(error.code === 1) msg = "Izin lokasi ditolak pengguna.";
                    Swal.fire('GPS Error', msg, 'error');
                    status.innerText = "Gagal mendapatkan lokasi.";
                    status.className = "text-[11px] text-red-500";
                },
                { enableHighAccuracy: true, timeout: 5000 }
            );
        } else {
            Swal.fire('Error', 'Browser Anda tidak mendukung Geolocation.', 'error');
        }
    }

    // 5. Jalankan Fungsi Start-up menggunakan DOMContentLoaded (Lebih Aman)
    document.addEventListener('DOMContentLoaded', function() {
        renderPreviews();
    });
</script>
@endsection
