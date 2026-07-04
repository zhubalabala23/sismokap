@extends('layouts.admin')

@section('title', 'Pengaturan Umum - SISMOKAP')
@section('page_title', 'Pengaturan Umum')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card p-4 bg-white shadow-sm border-0 rounded-4">
            <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                <div class="bg-primary-subtle text-primary p-3 rounded-4 me-3">
                    <i class="bi bi-gear-wide-connected fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-1">Pengaturan Instansi & Aplikasi</h5>
                    <p class="text-muted mb-0 fs-7">Ubah profil nama instansi, logo, dan alamat operasional</p>
                </div>
            </div>

            <form action="{{ route('admin.setting.pengaturan.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Nama Instansi -->
                <div class="mb-4">
                    <label for="nama_instansi" class="form-label fs-7 fw-semibold text-muted text-uppercase mb-2">Nama Instansi</label>
                    <input type="text" name="nama_instansi" id="nama_instansi" 
                           class="form-control rounded-3 fs-7 py-2.5 px-3 @error('nama_instansi') is-invalid @enderror" 
                           value="{{ old('nama_instansi', $settings['nama_instansi']) }}" 
                           placeholder="Masukkan nama instansi / dinas">
                    @error('nama_instansi')
                        <div class="invalid-feedback fs-8 mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Logo Instansi -->
                <div class="mb-4">
                    <label class="form-label fs-7 fw-semibold text-muted text-uppercase mb-2">Logo Instansi</label>
                    <div class="d-flex align-items-center gap-4 p-3 bg-light rounded-4">
                        <div class="logo-preview-box text-center bg-white rounded-3 border d-flex align-items-center justify-content-center" 
                             style="width: 100px; height: 100px; overflow: hidden;">
                            @if($settings['logo'])
                                <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo" class="img-fluid" style="max-height: 90px; object-fit: contain;">
                            @else
                                <i class="bi bi-image text-muted fs-2"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <input type="file" name="logo" id="logo" 
                                   class="form-control rounded-3 fs-7 @error('logo') is-invalid @enderror">
                            <small class="text-muted d-block mt-2 fs-8">Format yang diizinkan: JPG, JPEG, PNG, SVG (Maks. 2MB)</small>
                            @error('logo')
                                <div class="invalid-feedback fs-8 mt-1 d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Alamat Instansi -->
                <div class="mb-4">
                    <label for="alamat" class="form-label fs-7 fw-semibold text-muted text-uppercase mb-2">Alamat Instansi</label>
                    <textarea name="alamat" id="alamat" rows="4" 
                              class="form-control rounded-3 fs-7 py-2.5 px-3 @error('alamat') is-invalid @enderror" 
                              placeholder="Masukkan alamat instansi lengkap">{{ old('alamat', $settings['alamat']) }}</textarea>
                    @error('alamat')
                        <div class="invalid-feedback fs-8 mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-end gap-2 border-top pt-4">
                    <button type="submit" class="btn btn-primary rounded-3 px-4 py-2.5 fs-7 fw-semibold shadow-sm">
                        <i class="bi bi-save-fill me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
