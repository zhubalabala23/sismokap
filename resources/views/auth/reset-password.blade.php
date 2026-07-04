<x-guest-layout>
    <h4 class="text-center mb-4 fw-bold text-uppercase tracking-wider" style="color: var(--accent-gold);">
        <i class="bi bi-key-fill me-2"></i> Reset Kata Sandi
    </h4>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Alamat Email</label>
            <div class="input-group">
                <span class="input-group-text bg-dark border-secondary text-muted" style="border-right: none;"><i class="bi bi-envelope"></i></span>
                <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" style="border-left: none;">
            </div>
            @error('email')
                <div class="text-danger mt-1 small">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Kata Sandi Baru</label>
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
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password" placeholder="Ulangi kata sandi baru" style="border-left: none;">
            </div>
        </div>

        <button type="submit" class="btn btn-military mb-3">
            Reset Kata Sandi <i class="bi bi-check-circle ms-1"></i>
        </button>
    </form>
</x-guest-layout>
