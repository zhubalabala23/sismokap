@extends('layouts.admin')

@section('title', 'Edit User - SISMOKAP')
@section('page_title', 'Edit Data User')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <h5 class="fw-bold mb-0 text-dark">Formulir Edit User</h5>
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

            <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <!-- Nama -->
                    <div class="col-12">
                        <label for="name" class="form-label fs-7 fw-semibold text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control fs-7 @error('name') is-invalid @enderror" placeholder="Masukkan nama lengkap user..." value="{{ old('name', $user->name) }}" required>
                    </div>

                    <!-- Email -->
                    <div class="col-12 col-md-6">
                        <label for="email" class="form-label fs-7 fw-semibold text-dark">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control fs-7 @error('email') is-invalid @enderror" placeholder="Contoh: user@domain.com" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <!-- Role -->
                    <div class="col-12 col-md-6">
                        <label for="role" class="form-label fs-7 fw-semibold text-dark">Hak Akses / Role <span class="text-danger">*</span></label>
                        <select name="role" id="role" class="form-select fs-7 @error('role') is-invalid @enderror" required>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="operator" {{ old('role', $user->role) == 'operator' ? 'selected' : '' }}>Operator</option>
                            <option value="pimpinan" {{ old('role', $user->role) == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                        </select>
                    </div>

                    <div class="col-12 mt-4 border-top pt-3">
                        <p class="text-muted fs-8 mb-2">Isi kolom di bawah ini HANYA jika Anda ingin mengganti password user.</p>
                    </div>

                    <!-- Password -->
                    <div class="col-12 col-md-6">
                        <label for="password" class="form-label fs-7 fw-semibold text-dark">Password Baru</label>
                        <input type="password" name="password" id="password" class="form-control fs-7 @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter...">
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="col-12 col-md-6">
                        <label for="password_confirmation" class="form-label fs-7 fw-semibold text-dark">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control fs-7" placeholder="Ketik ulang password baru...">
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary fs-7 px-4">Perbarui User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
