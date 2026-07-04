<x-guest-layout>
    <h4 class="text-center mb-3 fw-bold text-uppercase tracking-wider" style="color: var(--accent-gold);">
        <i class="bi bi-shield-lock me-2"></i> Lupa Kata Sandi
    </h4>
    
    <div class="mb-4 text-muted-custom small text-center">
        Lupa kata sandi Anda? Silakan masukkan alamat email terdaftar Anda. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda melalui email.
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success bg-opacity-10 text-success border-success mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="form-label">Alamat Email</label>
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-muted" style="border-right: none;"><i class="bi bi-envelope"></i></span>
                <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus placeholder="nama@sismokap.test" style="border-left: none;">
            </div>
            @error('email')
                <div class="text-danger mt-1 small">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-military mb-3">
            Kirim Tautan Reset <i class="bi bi-send ms-1"></i>
        </button>

        <div class="text-center">
            <a class="auth-link small" href="{{ route('login') }}">Kembali ke Login</a>
        </div>
    </form>
</x-guest-layout>
