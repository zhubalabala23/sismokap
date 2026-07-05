@extends('layouts.admin')

@section('title', 'Tambah User - SISMOKAP')
@section('page_title', 'Tambah User Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <h5 class="fw-bold mb-0 text-dark">Formulir User Baru</h5>
                <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary btn-sm fs-7">
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

            <form action="{{ route('admin.user.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <!-- Nama -->
                    <div class="col-12">
                        <label for="name" class="form-label fs-7 fw-semibold text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control fs-7 @error('name') is-invalid @enderror" placeholder="Masukkan nama lengkap user..." value="{{ old('name') }}" required>
                    </div>

                    <!-- Email -->
                    <div class="col-12 col-md-6">
                        <label for="email" class="form-label fs-7 fw-semibold text-dark">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control fs-7 @error('email') is-invalid @enderror" placeholder="Contoh: user@domain.com" value="{{ old('email') }}" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="role" class="form-label fs-7 fw-semibold text-dark">Hak Akses / Role <span class="text-danger">*</span></label>
                        <select name="role" id="role" class="form-select fs-7 @error('role') is-invalid @enderror" required>
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                            <option value="pengawas" {{ old('role') == 'pengawas' ? 'selected' : '' }}>Pengawas</option>
                            <option value="pimpinan" {{ old('role') == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                        </select>
                    </div>

                    <!-- Password -->
                    <div class="col-12 col-md-6">
                        <label for="password" class="form-label fs-7 fw-semibold text-dark">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="form-control fs-7 @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter..." required>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="col-12 col-md-6">
                        <label for="password_confirmation" class="form-label fs-7 fw-semibold text-dark">Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control fs-7" placeholder="Ketik ulang password..." required>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-light border fs-7 px-4">Reset</button>
                    <button type="submit" class="btn btn-primary fs-7 px-4">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
