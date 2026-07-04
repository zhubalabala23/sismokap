<x-guest-layout>
    <h4 class="text-center mb-4 fw-bold text-uppercase tracking-wider" style="color: var(--accent-gold);">
        <i class="bi bi-person-plus me-2"></i> Pendaftaran Akun
    </h4>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-muted" style="border-right: none;"><i class="bi bi-person"></i></span>
                <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nama lengkap Anda" style="border-left: none;">
            </div>
            @error('name')
                <div class="text-danger mt-1 small">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Alamat Email</label>
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-muted" style="border-right: none;"><i class="bi bi-envelope"></i></span>
                <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@sismokap.test" style="border-left: none;">
            </div>
            @error('email')
                <div class="text-danger mt-1 small">{{ $message }}</div>
            @enderror
        </div>

        <!-- Role -->
        <div class="mb-3">
            <label for="role" class="form-label">Peran (Role)</label>
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-muted" style="border-right: none;"><i class="bi bi-person-badge"></i></span>
                <select id="role" name="role" class="form-control form-select @error('role') is-invalid @enderror" style="border-left: none; background-color: rgba(15, 23, 42, 0.6); color: #F8FAFC;">
                    <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operator (Default)</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                    <option value="pimpinan" {{ old('role') == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                </select>
            </div>
            @error('role')
                <div class="text-danger mt-1 small">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Kata Sandi</label>
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-muted" style="border-right: none;"><i class="bi bi-lock"></i></span>
                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password" placeholder="Minimal 8 karakter" style="border-left: none;">
            </div>
            @error('password')
                <div class="text-danger mt-1 small">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-muted" style="border-right: none;"><i class="bi bi-lock-fill"></i></span>
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password" placeholder="Ulangi kata sandi" style="border-left: none;">
            </div>
        </div>

        <button type="submit" class="btn btn-military mb-3">
            Daftar <i class="bi bi-person-plus ms-1"></i>
        </button>

        <div class="text-center">
            <span class="text-muted-custom small">Sudah memiliki akun?</span>
            <a class="auth-link small ms-1" href="{{ route('login') }}">Masuk</a>
        </div>
    </form>
</x-guest-layout>
