@extends('layouts.app', [
    'activePage' => 'barang',
])

@section('content')
<div class="p-6 min-h-screen bg-slate-50">
    <div class="max-w-4xl mx-auto">
        {{-- Breadcrumb --}}
        <nav class="flex mb-4 text-sm text-slate-500">
            <a href="{{ route('barang.index') }}" class="hover:text-teal-600 transition-colors">Data Barang</a>
            <span class="mx-2">/</span>
            <span class="text-slate-900 font-medium">Tambah Barang Baru</span>
        </nav>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            {{-- Header --}}
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-xl font-bold text-slate-900 flex items-center">
                    <i class="fas fa-boxes mr-2 text-teal-600"></i> Form Tambah Inventaris
                </h2>
                <p class="text-sm text-slate-500 mt-1">Lengkapi detail identitas. <b>Wajib upload 4 foto</b> (bisa dicicil satu-satu).</p>
            </div>

            <form id="formBarang" action="{{ route('Barang.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- SECTION 1: Identitas Barang --}}
                    <div class="md:col-span-2">
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Identitas Barang</h3>
                        <hr class="mt-2 border-slate-100">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Barang <span class="text-red-500">*</span> </label>
                        <input type="text" name="nama_barang" value="{{ old('nama_barang') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all outline-none text-sm"
                            placeholder="Contoh: Laptop MacBook Pro" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kode Barang</label>
                        <input type="text" name="kode_barang" value="{{ old('kode_barang') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all outline-none text-sm"
                            placeholder="Contoh: 3.01.01.04.001">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">NUP (No. Urut Pendaftaran)</label>
                        <input type="text" name="nup" value="{{ old('nup') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all outline-none text-sm"
                            placeholder="Contoh: 1" >
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Merek / Type <span class="text-red-500">*</span> </label>
                        <input type="text" name="merek" value="{{ old('merek') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all outline-none text-sm"
                            placeholder="Contoh: Apple / M2 Pro">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kondisi <span class="text-red-500">*</span> </label>
                        <select name="kondisi" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all outline-none text-sm bg-white">
                            <option value="Baik">Baik</option>
                            <option value="Rusak Ringan">Rusak Ringan</option>
                            <option value="Rusak Berat">Rusak Berat</option>
                        </select>
                    </div>

                    {{-- SECTION 2: Legalitas & Nilai --}}
                    <div class="md:col-span-2 pt-4">
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Legalitas & Nilai</h3>
                        <hr class="mt-2 border-slate-100">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Perolehan <span class="text-red-500">*</span> </label>
                        <input type="date" name="tgl_peroleh" value="{{ old('tgl_peroleh') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all outline-none text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nilai Perolehan (Rp) <span class="text-red-500">*</span> </label>
                        <input type="number" step="0.01" name="nilai_peroleh" value="{{ old('nilai_peroleh') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all outline-none text-sm"
                            placeholder="0.00" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor SK PSP</label>
                        <input type="text" name="nomor_sk_psp" value="{{ old('nomor_sk_psp') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all outline-none text-sm"
                            placeholder="Masukkan nomor SK jika ada">
                    </div>

                    {{-- SECTION 3: Penempatan & Lokasi --}}
                    <div class="md:col-span-2 pt-4">
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Penempatan & Lokasi</h3>
                        <hr class="mt-2 border-slate-100">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Ruangan <span class="text-red-500">*</span> </label>
                        <input type="text" name="ruangan" value="{{ old('ruangan') }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all outline-none text-sm"
                            placeholder="Contoh: Ruang Rapat Lt. 2">
                    </div>

                    {{-- Bagian Lokasi Otomatis --}}
                    <div class="md:col-span-2 p-5 bg-teal-50/50 rounded-2xl border border-teal-100">
                        <label class="block text-sm font-bold text-teal-800 mb-3 flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 animate-pulse"></i> Lokasi GPS & Alamat Otomatis
                        </label>

                        <div class="space-y-4">
                            <div>
                                <label class="text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-1 block">Alamat Terdeteksi <span class="text-red-500">*</span> </label>
                                <input type="text" name="lokasi" id="lokasi_nama" readonly
                                    class="w-full px-4 py-2.5 rounded-lg bg-white border border-slate-200 text-slate-600 text-sm outline-none cursor-not-allowed"
                                    placeholder="Mencari alamat...">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-1 block">Latitude</label>
                                    <input type="text" name="latitude" id="lat" readonly
                                        class="w-full px-4 py-2 rounded-lg bg-white border border-slate-200 text-slate-600 text-sm outline-none cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="text-[10px] uppercase tracking-wider text-slate-500 font-bold mb-1 block">Longitude</label>
                                    <input type="text" name="longitude" id="lng" readonly
                                        class="w-full px-4 py-2 rounded-lg bg-white border border-slate-200 text-slate-600 text-sm outline-none cursor-not-allowed">
                                </div>
                            </div>
                        </div>
                        <p id="geo-status" class="text-[11px] mt-2 text-slate-500 italic font-medium">Meminta akses sensor lokasi...</p>
                    </div>

                    {{-- Bagian Upload Foto (Modifikasi untuk cicil 1 per 1) --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Foto Barang <span id="photo-counter" class="text-teal-600">(0/4) <span class="text-red-500">*</span> </span>
                        </label>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4" id="preview-container">
                            {{-- Preview muncul di sini --}}
                        </div>

                        <div id="drop-area" class="relative group border-2 border-slate-200 border-dashed rounded-xl p-8 transition-all hover:border-teal-400 text-center cursor-pointer">
                            {{-- Ganti baris ini di file blade Anda --}}
                            <input type="file" id="file-upload" name="fotoBarang[]" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" multiple>
                            <div class="space-y-2">
                                <i class="fas fa-camera text-slate-300 text-3xl"></i>
                                <p class="text-sm text-slate-600 font-medium">Klik atau seret foto ke sini</p>
                                <p class="text-xs text-slate-400">Bisa upload satu per satu hingga terkumpul 4 foto</p>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('barang.index') }}"
                        class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-teal-600 text-white font-semibold text-sm hover:bg-teal-700 shadow-lg shadow-teal-600/20 transition-all flex items-center">
                        <i class="fas fa-save mr-2"></i> Simpan Inventaris
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // --- LOGIKA UPLOAD FOTO (DICICIL) ---
    const fileInput = document.getElementById('file-upload');
    const previewContainer = document.getElementById('preview-container');
    const photoCounter = document.getElementById('photo-counter');
    const form = document.getElementById('formBarang');

    // Tempat penyimpanan file sementara (DataTransfer memungkinan manipulasi FileList)
    let dt = new DataTransfer();

    fileInput.addEventListener('change', function() {
        const files = this.files;

        for (let i = 0; i < files.length; i++) {
            // Cek jika sudah 4, jangan tambah lagi
            if (dt.items.length >= 4) {
                Swal.fire('Info', 'Maksimal 4 foto saja.', 'info');
                break;
            }

            // Tambahkan file ke DataTransfer
            dt.items.add(files[i]);

            // Buat Preview
            const reader = new FileReader();
            const fileIndex = dt.items.length - 1;

            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = "relative aspect-square rounded-xl overflow-hidden border border-slate-200 shadow-sm group";
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-cover">
                    <button type="button" onclick="removePhoto(${fileIndex})" class="absolute top-1 right-1 bg-red-500 text-white w-6 h-6 rounded-full text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                previewContainer.appendChild(div);
            }
            reader.readAsDataURL(files[i]);
        }

        updateStatus();
    });

    function removePhoto(index) {
        // Logika hapus foto tertentu
        const newDt = new DataTransfer();
        const files = dt.files;

        for (let i = 0; i < files.length; i++) {
            if (i !== index) newDt.items.add(files[i]);
        }

        dt = newDt;
        renderPreviews();
        updateStatus();
    }

    function renderPreviews() {
        previewContainer.innerHTML = '';
        const files = dt.files;
        for (let i = 0; i < files.length; i++) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = "relative aspect-square rounded-xl overflow-hidden border border-slate-200 shadow-sm group";
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-cover">
                    <button type="button" onclick="removePhoto(${i})" class="absolute top-1 right-1 bg-red-500 text-white w-6 h-6 rounded-full text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                previewContainer.appendChild(div);
            }
            reader.readAsDataURL(files[i]);
        }
    }

    function updateStatus() {
        const count = dt.items.length;
        photoCounter.innerText = `(${count}/4)`;
        if (count === 4) {
            photoCounter.className = "text-green-600 font-bold";
        } else {
            photoCounter.className = "text-teal-600";
        }
    }

    // --- VALIDASI SAAT SIMPAN ---
    form.addEventListener('submit', function(e) {
        // Masukkan file dari DataTransfer kembali ke input asli sebelum dikirim
        fileInput.files = dt.files;

        if (fileInput.files.length !== 4) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Foto Belum Lengkap',
                text: 'Anda harus mengunggah tepat 4 foto sebelum dapat menyimpan data.',
                confirmButtonColor: '#0d9488'
            });
        }
    });

    // --- GEOLOCATION LOGIC ---
    window.onload = function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    document.getElementById('lat').value = lat;
                    document.getElementById('lng').value = lng;
                    const status = document.getElementById('geo-status');
                    status.innerText = "Koordinat terkunci. Mengambil alamat...";

                    fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('lokasi_nama').value = data.display_name;
                            status.innerText = "Lokasi berhasil ditemukan!";
                            status.className = "text-[11px] mt-2 text-green-600 font-semibold";
                        })
                        .catch(() => {
                            document.getElementById('lokasi_nama').value = "Alamat tidak dapat dijangkau";
                        });
                },
                (error) => {
                    document.getElementById('geo-status').innerText = "Akses GPS ditolak / tidak aktif.";
                    document.getElementById('geo-status').className = "text-[11px] mt-2 text-red-500 font-semibold";
                },
                { enableHighAccuracy: true }
            );
        }
    };
</script>
@endsection
