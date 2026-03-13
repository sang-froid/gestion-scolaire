@extends('layouts.auth')

@section('title', 'Connexion')

@section('content')
    <h5 class="card-title text-center fw-bold mb-4" style="color: #1e4d8c;">
        Connexion
    </h5>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Adresse email</label>
            <input type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="exemple@email.com"
                   required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Mot de passe --}}
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   id="password" name="password"
                   placeholder="••••••••"
                   required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Se souvenir de moi --}}
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input"
                   id="remember" name="remember"
                   {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">
                Se souvenir de moi
            </label>
        </div>

        {{-- Bouton connexion --}}
        <div class="d-grid">
            <button type="submit" class="btn btn-primary"
                    style="background-color: #1e4d8c; border-color: #1e4d8c;">
                Se connecter
            </button>
        </div>

        {{-- Mot de passe oublié --}}
        @if(Route::has('password.request'))
            <div class="text-center mt-3">
                <a href="{{ route('password.request') }}"
                   class="text-muted small text-decoration-none">
                    Mot de passe oublié ?
                </a>
            </div>
        @endif

    </form>
@endsection

@section('auth-links')
    Pas encore de compte ?
    <a href="{{ route('register') }}" class="text-decoration-none" style="color: #1e4d8c;">
        S'inscrire
    </a>
@endsection
