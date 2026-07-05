<x-guest-layout>
    <h4 class="text-center mb-4 fw-bold text-uppercase tracking-wider" style="color: var(--accent-gold);">
        <i class="bi bi-box-arrow-in-right me-2"></i> Autentikasi Pengguna
    </h4>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success bg-opacity-10 text-success border-success mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Alamat Email</label>
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-muted" style="border-right: none;"><i class="bi bi-envelope"></i></span>
                <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@sismokap.test" style="border-left: none;">
            </div>
            @error('email')
                <div class="text-danger mt-1 small">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Kata Sandi</label>
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-muted" style="border-right: none;"><i class="bi bi-lock"></i></span>
                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password" placeholder="Masukkan kata sandi" style="border-left: none;">
            </div>
            @error('password')
                <div class="text-danger mt-1 small">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-4">
            <div class="form-check checkbox-custom">
                <input id="remember_me" type="checkbox" name="remember" class="form-check-input">
                <label for="remember_me" class="form-check-label text-muted-custom small">Ingat saya</label>
            </div>
        </div>

        <button type="submit" class="btn btn-military mb-3">
            Masuk <i class="bi bi-box-arrow-in-right ms-1"></i>
        </button>
    </form>
</x-guest-layout>
