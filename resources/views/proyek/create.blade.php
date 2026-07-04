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

            <form action="{{ route('admin.proyek.store') }}" method="POST">
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
                        <input type="text" name="nama_proyek" id="nama_proyek" class="form-control fs-7 @error('nama_proyek') is-invalid @enderror" placeholder="Contoh: Gedung Serbaguna" value="{{ old('nama_proyek') }}" required>
                    </div>

                    <!-- Lokasi -->
                    <div class="col-12 col-md-6">
                        <label for="lokasi_id" class="form-label fs-7 fw-semibold text-dark">Lokasi Proyek <span class="text-danger">*</span></label>
                        <select name="lokasi_id" id="lokasi_id" class="form-select fs-7 @error('lokasi_id') is-invalid @enderror" required>
                            <option value="">Pilih Lokasi</option>
                            @foreach($lokasis as $lokasi)
                                <option value="{{ $lokasi->id }}" {{ old('lokasi_id') == $lokasi->id ? 'selected' : '' }}>{{ $lokasi->nama_lokasi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kontraktor -->
                    <div class="col-12 col-md-6">
                        <label for="kontraktor_id" class="form-label fs-7 fw-semibold text-dark">Kontraktor Pelaksana <span class="text-danger">*</span></label>
                        <select name="kontraktor_id" id="kontraktor_id" class="form-select fs-7 @error('kontraktor_id') is-invalid @enderror" required>
                            <option value="">Pilih Kontraktor</option>
                            @foreach($kontraktors as $kontraktor)
                                <option value="{{ $kontraktor->id }}" {{ old('kontraktor_id') == $kontraktor->id ? 'selected' : '' }}>{{ $kontraktor->nama_kontraktor }}</option>
                            @endforeach
                        </select>
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
                            <option value="berjalan" {{ old('status', 'berjalan') == 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                            <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="terlambat" {{ old('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        </select>
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
