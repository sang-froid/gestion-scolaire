@extends('layouts.auth')

@section('title', 'Connexion — Gestion Scolaire')

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
          <h4 class="fw-bold" style="color:#1e4d8c">Connexion</h4>
          <p class="text-muted small">Accédez à votre espace personnel</p>
        </div>

        @if(session('success'))
          <div class="alert alert-dismissible d-flex align-items-center gap-2 rounded-3 mb-3"
               style="background:rgba(16,185,129,.1);color:#065F46;border-left:4px solid #10B981;border:1px solid rgba(16,185,129,.2)">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
          </div>
        @endif

        @if(session('error'))
          <div class="alert alert-dismissible d-flex align-items-center gap-2 rounded-3 mb-3"
               style="background:rgba(244,63,94,.1);color:#9F1239;border-left:4px solid #F43F5E;border:1px solid rgba(244,63,94,.2)">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
          </div>
        @endif

        {{-- FORMULAIRE --}}
        {{-- La classe "login-form" sert au CSS du loader --}}
        <form method="POST" action="{{ route('login.post') }}" class="login-form">
          @csrf

          <div class="mb-3">
            <label for="email" class="form-label small fw-semibold">Email</label>
            <input type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="exemple@email.com"
                   autofocus required/>
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="password" class="form-label small fw-semibold">Mot de passe</label>
            <input type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   id="password" name="password"
                   placeholder="••••••••" required/>
            @error('password')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <!-- <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="remember" name="remember"/>
              <label class="form-check-label small" for="remember">Se souvenir</label>
            </div>
          </div> -->

          <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember"/>
                <label class="form-check-label small" for="remember">Se souvenir</label>
            </div>
            <a href="{{ route('password.request') }}"
              class="text-decoration-none small fw-semibold"
              style="color:#1e4d8c">
                Mot de passe oublié ?
            </a>
          </div>

          {{-- ══ BOUTON AVEC LOADER CSS ══
               Principe :
               - Le bouton a deux états visuels via CSS
               - Au submit, la classe "loading" est ajoutée sur le form
               - Le CSS affiche le spinner et cache le texte
          ══ --}}
          <button type="submit" class="btn-login w-100 mb-3" id="btnLogin">
            {{-- Texte normal --}}
            <span class="btn-login-text">
              <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
            </span>
            {{-- Spinner (visible uniquement en état loading) --}}
            <span class="btn-login-spinner">
              <span class="spinner"></span>
              Connexion…
            </span>
          </button>

          <button type="button"
                  class="btn btn-outline-secondary w-100 py-2"
                  data-bs-toggle="modal"
                  data-bs-target="#howItWorksModal"
                  style="border-color:#1e4d8c;color:#1e4d8c;border-radius:8px">
            <i class="bi bi-info-circle me-2"></i>Comment ça marche ?
          </button>

          <div class="text-center mt-4">
            <p class="small text-muted mb-0">
              Pas encore inscrit ?
              <a href="{{ route('parent.inscription.create') }}"
                 class="text-decoration-none fw-semibold" style="color:#1e4d8c">
                Inscrire mon enfant
              </a>
            </p>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="howItWorksModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold" style="color:#1e4d8c">Comment ça marche ?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-4">
          <div class="col-4 text-center">
            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-2" style="width:50px;height:50px">
              <span class="fw-bold" style="color:#1e4d8c">1</span>
            </div>
            <h6 class="small fw-bold">Inscription</h6>
            <p class="small text-muted">Remplissez le formulaire d'inscription</p>
          </div>
          <div class="col-4 text-center">
            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-2" style="width:50px;height:50px">
              <span class="fw-bold" style="color:#1e4d8c">2</span>
            </div>
            <h6 class="small fw-bold">Mot de passe</h6>
            <p class="small text-muted">Un mot de passe vous est communiqué</p>
          </div>
          <div class="col-4 text-center">
            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-2" style="width:50px;height:50px">
              <span class="fw-bold" style="color:#1e4d8c">3</span>
            </div>
            <h6 class="small fw-bold">Suivi</h6>
            <p class="small text-muted">Suivez le dossier de votre enfant</p>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-primary w-100"
                style="background:#1e4d8c;border:none;border-radius:8px"
                data-bs-dismiss="modal">Compris !</button>
      </div>
    </div>
  </div>
</div>

<style>
  /* ── Inputs ── */
  .form-control {
    border-radius: 8px; border: 1px solid #e0e0e0; padding: 12px;
    font-family: 'DM Sans', sans-serif;
  }
  .form-control:focus {
    border-color: #1e4d8c;
    box-shadow: 0 0 0 2px rgba(30,77,140,.1);
  }

  /* ══════════════════════════════════════════
     BOUTON LOGIN + LOADER CSS PUR
     Pas de JS nécessaire — fonctionne avec
     :has() CSS (Chrome 105+, Firefox 121+)
  ══════════════════════════════════════════ */
  .btn-login {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    padding: .65rem 1rem;
    background: #1e4d8c;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: background .2s, transform .15s;
    min-height: 48px;
  }
  .btn-login:hover { background: #153b6b; transform: translateY(-1px); }
  .btn-login:active { transform: translateY(0); }

  /* Texte normal — visible par défaut */
  .btn-login-text {
    display: flex; align-items: center;
    transition: opacity .15s;
  }

  /* Spinner — caché par défaut */
  .btn-login-spinner {
    display: none;
    align-items: center; gap: .5rem;
  }

  /* ── Spinner CSS ── */
  .spinner {
    width: 18px; height: 18px;
    border: 2px solid rgba(255,255,255,.35);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin .7s linear infinite;
    flex-shrink: 0;
  }
  @keyframes spin {
    to { transform: rotate(360deg); }
  }

  /* ══ ÉTAT LOADING ══
     Déclenché quand le form a la classe "loading"
     (ajoutée par le seul JS nécessaire — une ligne)
  ══ */
  .login-form.loading .btn-login-text    { display: none; }
  .login-form.loading .btn-login-spinner { display: flex; }
  .login-form.loading .btn-login         { opacity: .85; cursor: not-allowed; }

  /* Fallback CSS pur via :has() si JS ne marche pas du tout */
  .login-form:has(button:active) .btn-login-text    { opacity: .5; }
</style>

{{-- ══ UNE SEULE LIGNE DE JS ══ --}}
<script>
  document.querySelector('.login-form').addEventListener('submit', function() {
    this.classList.add('loading');
  });
</script>

@endsection