@extends('layouts.admin')

@section('title', 'Tambah Proyek - SISMOKAP')
@section('page_title', 'Tambah Proyek Konstruksi')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <h5 class="fw-bold mb-0 text-dark">Formulir Proyek Baru</h5>
                <a href="{{ route('admin.proyek.index') }}" class="btn btn-outline-secondary btn-sm fs-7">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
                    <ul class="mb-0 fs-7">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.proyek.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <!-- Kode Proyek -->
                    <div class="col-12 col-md-6">
                        <label for="kode_proyek" class="form-label fs-7 fw-semibold text-dark">Kode Proyek <span class="text-danger">*</span></label>
                        <input type="text" name="kode_proyek" id="kode_proyek" class="form-control fs-7 @error('kode_proyek') is-invalid @enderror" placeholder="Contoh: PRJ-2024-001" value="{{ old('kode_proyek') }}" required>
                    </div>

                    <!-- Nama Proyek -->
                    <div class="col-12 col-md-6">
                        <label for="nama_proyek" class="form-label fs-7 fw-semibold text-dark">Nama Proyek <span class="text-danger">*</span></label>
                        <select name="nama_proyek" id="nama_proyek" class="form-select fs-7 @error('nama_proyek') is-invalid @enderror" required>
                            <option value="">Pilih Nama Proyek</option>
                            <option value="Gedung Serba Guna" {{ old('nama_proyek') == 'Gedung Serba Guna' ? 'selected' : '' }}>Gedung Serba Guna</option>
                            <option value="Kontruksi Jembatan" {{ old('nama_proyek') == 'Kontruksi Jembatan' ? 'selected' : '' }}>Kontruksi Jembatan</option>
                            <option value="Kontruksi Jalan" {{ old('nama_proyek') == 'Kontruksi Jalan' ? 'selected' : '' }}>Kontruksi Jalan</option>
                            <option value="Kontruksi Perumahan" {{ old('nama_proyek') == 'Kontruksi Perumahan' ? 'selected' : '' }}>Kontruksi Perumahan</option>
                        </select>
                    </div>

                    <!-- Jenis Pekerjaan -->
                    <div class="col-12 col-md-6">
                        <label for="jenis_pekerjaan" class="form-label fs-7 fw-semibold text-dark">Jenis Pekerjaan <span class="text-danger">*</span></label>
                        <input type="text" name="jenis_pekerjaan" id="jenis_pekerjaan" class="form-control fs-7 @error('jenis_pekerjaan') is-invalid @enderror" placeholder="Contoh: Gedung, Gudang, Mess, dll." value="{{ old('jenis_pekerjaan') }}" required>
                    </div>

                    <!-- Tahapan Pekerjaan -->
                    <div class="col-12 col-md-6">
                        <label for="tahapan_pekerjaan" class="form-label fs-7 fw-semibold text-dark">Tahapan Pekerjaan <span class="text-danger">*</span></label>
                        <input type="text" name="tahapan_pekerjaan" id="tahapan_pekerjaan" class="form-control fs-7 @error('tahapan_pekerjaan') is-invalid @enderror" placeholder="Contoh: Perencanaan, Pondasi, Struktur, Finishing, dll." value="{{ old('tahapan_pekerjaan') }}" required>
                    </div>

                    <!-- Lokasi -->
                    <div class="col-12 col-md-6">
                        <label for="lokasi_nama" class="form-label fs-7 fw-semibold text-dark">Lokasi Proyek <span class="text-danger">*</span></label>
                        <input type="text" name="lokasi_nama" id="lokasi_nama" class="form-control fs-7 @error('lokasi_nama') is-invalid @enderror" placeholder="Contoh: Madiun Kota" value="{{ old('lokasi_nama') }}" required>
                    </div>

                    <!-- Pelaksana -->
                    <div class="col-12 col-md-6">
                        <label for="pelaksana_nama" class="form-label fs-7 fw-semibold text-dark">Pelaksana <span class="text-danger">*</span></label>
                        <input type="text" name="pelaksana_nama" id="pelaksana_nama" class="form-control fs-7 @error('pelaksana_nama') is-invalid @enderror" placeholder="Contoh: CV. Karya Mandiri" value="{{ old('pelaksana_nama') }}" required>
                    </div>

                    <!-- Nilai Kontrak -->
                    <div class="col-12 col-md-6">
                        <label for="nilai_kontrak" class="form-label fs-7 fw-semibold text-dark">Nilai Kontrak (Rupiah) <span class="text-danger">*</span></label>
                        <input type="number" name="nilai_kontrak" id="nilai_kontrak" class="form-control fs-7 @error('nilai_kontrak') is-invalid @enderror" placeholder="Contoh: 1500000000" value="{{ old('nilai_kontrak') }}" required>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div class="col-12 col-md-6">
                        <label for="tanggal_mulai" class="form-label fs-7 fw-semibold text-dark">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control fs-7 @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai') }}" required>
                    </div>

                    <!-- Tanggal Selesai -->
                    <div class="col-12 col-md-6">
                        <label for="tanggal_selesai" class="form-label fs-7 fw-semibold text-dark">Tanggal Selesai <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control fs-7 @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai') }}" required>
                    </div>

                    <!-- Target Progress -->
                    <div class="col-12 col-md-6">
                        <label for="target_progress" class="form-label fs-7 fw-semibold text-dark">Target Progress (%) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" max="100" name="target_progress" id="target_progress" class="form-control fs-7 @error('target_progress') is-invalid @enderror" value="{{ old('target_progress', 100.00) }}" required>
                    </div>

                    <!-- Status -->
                    <div class="col-12 col-md-6">
                        <label for="status" class="form-label fs-7 fw-semibold text-dark">Status Awal <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select fs-7 @error('status') is-invalid @enderror" required>
                            <option value="perencanaan" {{ old('status') == 'perencanaan' ? 'selected' : '' }}>Perencanaan</option>
                            <option value="berjalan" {{ old('status', 'berjalan') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                            <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="terlambat" {{ old('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        </select>
                    </div>

                    <!-- Keterangan -->
                    <div class="col-12">
                        <label for="keterangan" class="form-label fs-7 fw-semibold text-dark">Keterangan (Opsional)</label>
                        <textarea name="keterangan" id="keterangan" rows="3" class="form-control fs-7 @error('keterangan') is-invalid @enderror" placeholder="Tulis keterangan tambahan mengenai proyek...">{{ old('keterangan') }}</textarea>
                    </div>

                    <!-- Gambar Proyek -->
                    <div class="col-12">
                        <label for="gambar_proyek" class="form-label fs-7 fw-semibold text-dark">Gambar Proyek (Opsional)</label>
                        <input type="file" name="gambar_proyek" id="gambar_proyek" class="form-control fs-7 @error('gambar_proyek') is-invalid @enderror" accept="image/*">
                        <small class="text-muted d-block mt-1 fs-8">Unggah gambar utama proyek. Format: JPG, PNG, JPEG, WEBP (Maks. 2MB)</small>
                        @error('gambar_proyek')
                            <div class="invalid-feedback fs-8 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Foto Dokumentasi Awal -->
                    <div class="col-12">
                        <label for="foto" class="form-label fs-7 fw-semibold text-dark">Foto Dokumentasi Awal (Opsional)</label>
                        <input type="file" name="foto" id="foto" class="form-control fs-7 @error('foto') is-invalid @enderror" accept="image/*">
                        <small class="text-muted d-block mt-1 fs-8">Unggah foto kondisi awal proyek jika ada. Format: JPG, PNG, JPEG, WEBP (Maks. 2MB)</small>
                        @error('foto')
                            <div class="invalid-feedback fs-8 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-light border fs-7 px-4">Reset</button>
                    <button type="submit" class="btn btn-primary fs-7 px-4">Simpan Proyek</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
