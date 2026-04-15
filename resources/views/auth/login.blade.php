{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.guest')

@section('title', 'Login Admin')

@section('card_header')
    <div style="width:52px;height:52px;background:rgba(255,255,255,.2);border-radius:14px;
                display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
        <i class="bi bi-shield-lock-fill fs-4"></i>
    </div>
    <h4 class="fw-bold mb-1">Masuk ke Sistem</h4>
    <p class="mb-0 opacity-75 small">Sistem Peminjaman Alat</p>
@endsection

@section('content')
    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Username</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text"
                       name="username"
                       class="form-control @error('username') is-invalid @enderror"
                       value="{{ old('username') }}"
                       placeholder="Masukkan username"
                       autofocus
                       autocomplete="username">
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password"
                       name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="••••••••"
                       autocomplete="current-password">
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="bi bi-eye" id="iconPassword"></i>
                </button>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-4 d-flex align-items-center justify-content-between">
            <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label small" for="remember">Ingat saya</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">
            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
        </button>
    </form>
@endsection

@section('scripts')
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.querySelector('input[name="password"]');
        const icon  = document.getElementById('iconPassword');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    });
</script>
@endsection