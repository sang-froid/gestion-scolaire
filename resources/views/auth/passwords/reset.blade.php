@extends('layouts.auth')

@section('title', 'Réinitialisation du mot de passe — Gestion Scolaire')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0 min-vh-100">

        {{-- Colonne gauche image --}}
        <div class="col-md-6 position-relative d-none d-md-block"
             style="background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
                    url('{{ asset('assets/images/scolaire.jpg') }}');
                    background-size:cover; background-position:center;">
            <div class="d-flex flex-column justify-content-center h-100 p-5 text-white">
                <h1 class="display-4 fw-bold mb-4 text-center" style="text-shadow:2px 2px 4px rgba(0,0,0,0.2)">
                    Gestion Scolaire
                </h1>
                <p class="lead mb-5 text-center" style="font-size:1.1rem;opacity:.95">
                    Une plateforme complète pour gérer la scolarité de vos enfants en toute simplicité
                </p>
                <div class="border-top border-white border-opacity-25 pt-4 mt-3">
                    <div class="row">
                        <div class="col-4 text-center">
                            <div class="fw-bold fs-4">100%</div>
                            <small class="opacity-75">Suivi en ligne</small>
                        </div>
                        <div class="col-4 text-center">
                            <div class="fw-bold fs-4">24/7</div>
                            <small class="opacity-75">Accès parents</small>
                        </div>
                        <div class="col-4 text-center">
                            <div class="fw-bold fs-4">Sécurisé</div>
                            <small class="opacity-75">Données protégées</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Colonne droite formulaire --}}
        <div class="col-md-6 d-flex align-items-center justify-content-center p-4" style="background:#f8f9fa">
            <div class="w-100" style="max-width:400px">

                <div class="text-center mb-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center
                                justify-content-center mx-auto mb-3"
                         style="width:60px;height:60px">
                        <i class="bi bi-shield-lock-fill fs-4" style="color:#1e4d8c"></i>
                    </div>
                    <h4 class="fw-bold" style="color:#1e4d8c">Nouveau mot de passe</h4>
                    <p class="text-muted small">Choisissez un mot de passe d'au moins 8 caractères.</p>
                </div>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">
                        <label for="email" class="form-label small fw-semibold">Adresse e-mail</label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email"
                               value="{{ $email ?? old('email') }}"
                               required autofocus/>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label small fw-semibold">Nouveau mot de passe</label>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password"
                               placeholder="••••••••" required/>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password-confirm" class="form-label small fw-semibold">
                            Confirmer le mot de passe
                        </label>
                        <input type="password"
                               class="form-control"
                               id="password-confirm"
                               name="password_confirmation"
                               placeholder="••••••••" required/>
                    </div>

                    <button type="submit" class="btn w-100 py-2 fw-semibold text-white mb-3"
                            style="background:#1e4d8c;border-radius:8px">
                        <i class="bi bi-check-circle me-2"></i>Réinitialiser le mot de passe
                    </button>

                    <div class="text-center">
                        <a href="{{ route('login') }}"
                           class="text-decoration-none small fw-semibold"
                           style="color:#1e4d8c">
                            <i class="bi bi-arrow-left me-1"></i>Retour à la connexion
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<style>
    .form-control {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 12px;
    }
    .form-control:focus {
        border-color: #1e4d8c;
        box-shadow: 0 0 0 2px rgba(30,77,140,.1);
    }
</style>

@endsection