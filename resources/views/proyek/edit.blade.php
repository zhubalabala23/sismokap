@extends('layouts.admin')

@section('title', 'Edit Proyek - SISMOKAP')
@section('page_title', 'Edit Proyek Konstruksi')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <h5 class="fw-bold mb-0 text-dark">Formulir Edit Proyek</h5>
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

            <form action="{{ route('admin.proyek.update', $proyek->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <!-- Kode Proyek -->
                    <div class="col-12 col-md-6">
                        <label for="kode_proyek" class="form-label fs-7 fw-semibold text-dark">Kode Proyek <span class="text-danger">*</span></label>
                        <input type="text" name="kode_proyek" id="kode_proyek" class="form-control fs-7 @error('kode_proyek') is-invalid @enderror" placeholder="Contoh: PRJ-2024-001" value="{{ old('kode_proyek', $proyek->kode_proyek) }}" required>
                    </div>

                    <!-- Nama Proyek -->
                    <div class="col-12 col-md-6">
                        <label for="nama_proyek" class="form-label fs-7 fw-semibold text-dark">Nama Proyek <span class="text-danger">*</span></label>
                        <input type="text" name="nama_proyek" id="nama_proyek" class="form-control fs-7 @error('nama_proyek') is-invalid @enderror" placeholder="Contoh: Gedung Serbaguna" value="{{ old('nama_proyek', $proyek->nama_proyek) }}" required>
                    </div>

                    <!-- Jenis Pekerjaan -->
                    <div class="col-12 col-md-6">
                        <label for="jenis_pekerjaan" class="form-label fs-7 fw-semibold text-dark">Jenis Pekerjaan <span class="text-danger">*</span></label>
                        <input type="text" name="jenis_pekerjaan" id="jenis_pekerjaan" class="form-control fs-7 @error('jenis_pekerjaan') is-invalid @enderror" placeholder="Contoh: Gedung, Gudang, Mess, dll." value="{{ old('jenis_pekerjaan', $proyek->jenis_pekerjaan) }}" required>
                    </div>

                    <!-- Tahapan Pekerjaan -->
                    <div class="col-12 col-md-6">
                        <label for="tahapan_pekerjaan" class="form-label fs-7 fw-semibold text-dark">Tahapan Pekerjaan <span class="text-danger">*</span></label>
                        <input type="text" name="tahapan_pekerjaan" id="tahapan_pekerjaan" class="form-control fs-7 @error('tahapan_pekerjaan') is-invalid @enderror" placeholder="Contoh: Perencanaan, Pondasi, Struktur, Finishing, dll." value="{{ old('tahapan_pekerjaan', $proyek->tahapan_pekerjaan) }}" required>
                    </div>

                    <!-- Lokasi -->
                    <div class="col-12 col-md-6">
                        <label for="lokasi_nama" class="form-label fs-7 fw-semibold text-dark">Lokasi Proyek <span class="text-danger">*</span></label>
                        <input type="text" name="lokasi_nama" id="lokasi_nama" class="form-control fs-7 @error('lokasi_nama') is-invalid @enderror" placeholder="Contoh: Kampus Trilogi, Jakarta" value="{{ old('lokasi_nama', $proyek->lokasi?->nama_lokasi) }}" required>
                    </div>

                    <!-- Satuan Pelaksana -->
                    <div class="col-12 col-md-6">
                        <label for="kontraktor_id" class="form-label fs-7 fw-semibold text-dark">Satuan Pelaksana <span class="text-danger">*</span></label>
                        <select name="kontraktor_id" id="kontraktor_id" class="form-select fs-7 @error('kontraktor_id') is-invalid @enderror" required>
                            <option value="">Pilih Satuan Pelaksana</option>
                            @foreach($kontraktors as $kontraktor)
                                <option value="{{ $kontraktor->id }}" {{ old('kontraktor_id', $proyek->kontraktor_id) == $kontraktor->id ? 'selected' : '' }}>{{ $kontraktor->nama_kontraktor }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nilai Kontrak -->
                    <div class="col-12 col-md-6">
                        <label for="nilai_kontrak" class="form-label fs-7 fw-semibold text-dark">Nilai Kontrak (Rupiah) <span class="text-danger">*</span></label>
                        <input type="number" name="nilai_kontrak" id="nilai_kontrak" class="form-control fs-7 @error('nilai_kontrak') is-invalid @enderror" placeholder="Contoh: 1500000000" value="{{ old('nilai_kontrak', intval($proyek->nilai_kontrak)) }}" required>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div class="col-12 col-md-6">
                        <label for="tanggal_mulai" class="form-label fs-7 fw-semibold text-dark">Tanggal Mulai <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control fs-7 @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai', $proyek->tanggal_mulai->format('Y-m-d')) }}" required>
                    </div>

                    <!-- Tanggal Selesai -->
                    <div class="col-12 col-md-6">
                        <label for="tanggal_selesai" class="form-label fs-7 fw-semibold text-dark">Tanggal Selesai <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control fs-7 @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai', $proyek->tanggal_selesai->format('Y-m-d')) }}" required>
                    </div>

                    <!-- Target Progress -->
                    <div class="col-12 col-md-6">
                        <label for="target_progress" class="form-label fs-7 fw-semibold text-dark">Target Progress (%) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" max="100" name="target_progress" id="target_progress" class="form-control fs-7 @error('target_progress') is-invalid @enderror" value="{{ old('target_progress', $proyek->target_progress) }}" required>
                    </div>

                    <!-- Status -->
                    <div class="col-12 col-md-6">
                        <label for="status" class="form-label fs-7 fw-semibold text-dark">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select fs-7 @error('status') is-invalid @enderror" required>
                            <option value="perencanaan" {{ old('status', $proyek->status) == 'perencanaan' ? 'selected' : '' }}>Perencanaan</option>
                            <option value="berjalan" {{ old('status', $proyek->status) == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                            <option value="selesai" {{ old('status', $proyek->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="terlambat" {{ old('status', $proyek->status) == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        </select>
                    </div>

                    <!-- Keterangan -->
                    <div class="col-12">
                        <label for="keterangan" class="form-label fs-7 fw-semibold text-dark">Keterangan (Opsional)</label>
                        <textarea name="keterangan" id="keterangan" rows="3" class="form-control fs-7 @error('keterangan') is-invalid @enderror" placeholder="Tulis keterangan tambahan mengenai proyek...">{{ old('keterangan', $proyek->keterangan) }}</textarea>
                    </div>

                    <!-- Foto Dokumentasi Awal -->
                    <div class="col-12">
                        <label for="foto" class="form-label fs-7 fw-semibold text-dark">Foto Dokumentasi Awal (Opsional)</label>
                        @php
                            $initialFoto = $proyek->dokumentasi->first();
                        @endphp
                        @if($initialFoto)
                            <div class="mb-2">
                                <img src="{{ $initialFoto->file_url }}" alt="Dokumentasi Awal" class="img-thumbnail" style="max-height: 150px;">
                                <small class="text-muted d-block mt-1">Foto saat ini</small>
                            </div>
                        @endif
                        <input type="file" name="foto" id="foto" class="form-control fs-7 @error('foto') is-invalid @enderror" accept="image/*">
                        <small class="text-muted d-block mt-1 fs-8">Unggah foto baru untuk mengganti atau menambahkan foto kondisi awal proyek. Format: JPG, PNG, JPEG, WEBP (Maks. 2MB)</small>
                        @error('foto')
                            <div class="invalid-feedback fs-8 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary fs-7 px-4">Perbarui Proyek</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
