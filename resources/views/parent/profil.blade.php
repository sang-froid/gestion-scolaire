@extends('layouts.app')

@section('title', 'Mon Profil')
@section('page_title', 'Mon Profil')

@push('styles')
<style>
    * { font-family: 'Montserrat', sans-serif !important; }

    .profil-header {
        background: linear-gradient(115deg, #0D1B2A 0%, #1A3A5C 55%, #1e4d7e 100%);
        border-radius: 14px;
        padding: 2rem 2.2rem;
        margin-bottom: 1.8rem;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .profil-header::after {
        content: '';
        position: absolute;
        right: -40px; top: -40px;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: rgba(56,189,248,.07);
        pointer-events: none;
    }

    .profil-avatar-wrap {
        width: 80px; height: 80px;
        border-radius: 50%;
        background: rgba(255,255,255,.1);
        border: 3px solid rgba(255,255,255,.25);
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; color: #38BDF8;
        flex-shrink: 0;
    }

    .profil-header-info h2 {
        color: #fff;
        font-size: 1.4rem;
        margin: 0 0 .25rem;
    }

    .profil-header-info p {
        color: rgba(255,255,255,.6);
        font-size: .85rem;
        margin: 0;
    }

    .profil-badge {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        background: rgba(56,189,248,.15);
        color: #38BDF8;
        border-radius: 20px;
        padding: .25rem .75rem;
        font-size: .75rem;
        font-weight: 600;
        margin-top: .5rem;
    }

    /* Tabs */
    .profil-tabs {
        display: flex;
        gap: .5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .profil-tab {
        display: flex;
        align-items: center;
        gap: .5rem;
        padding: .6rem 1.2rem;
        border-radius: 10px;
        border: 1.5px solid #E2E8F0;
        background: #fff;
        color: #64748B;
        font-size: .85rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all .2s;
    }

    .profil-tab:hover {
        border-color: #1e4d8c;
        color: #1e4d8c;
        background: #EFF6FF;
    }

    .profil-tab.active {
        background: #1e4d8c;
        border-color: #1e4d8c;
        color: #fff;
    }

    /* Card section */
    .profil-card {
        background: #fff;
        border-radius: 14px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 24px rgba(13,27,42,.08);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .profil-card-head {
        padding: 1.1rem 1.6rem;
        border-bottom: 1px solid #E2E8F0;
        display: flex;
        align-items: center;
        gap: .75rem;
    }

    .profil-card-head-icon {
        width: 38px; height: 38px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .profil-card-head h5 {
        font-size: .97rem;
        font-weight: 700;
        color: #0D1B2A;
        margin: 0;
    }

    .profil-card-head p {
        font-size: .78rem;
        color: #64748B;
        margin: 0;
    }

    .profil-card-body {
        padding: 1.6rem;
    }

    /* Form styles */
    .form-label {
        font-size: .82rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: .4rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1.5px solid #E2E8F0;
        padding: .65rem .9rem;
        font-size: .88rem;
        color: #0D1B2A;
        transition: border-color .2s, box-shadow .2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #1e4d8c;
        box-shadow: 0 0 0 3px rgba(30,77,140,.1);
        outline: none;
    }

    .form-control.is-invalid {
        border-color: #F43F5E;
    }

    /* Bouton submit */
    .btn-save {
        background: #1e4d8c;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: .65rem 1.6rem;
        font-size: .88rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        transition: background .2s, transform .15s;
    }

    .btn-save:hover {
        background: #153b6b;
        transform: translateY(-1px);
    }

    /* Alerte succès inline */
    .alert-success-inline {
        display: flex;
        align-items: center;
        gap: .6rem;
        background: rgba(16,185,129,.1);
        border: 1px solid rgba(16,185,129,.25);
        border-left: 4px solid #10B981;
        border-radius: 10px;
        padding: .8rem 1rem;
        color: #065F46;
        font-size: .85rem;
        margin-bottom: 1.2rem;
    }

    /* Info readonly */
    .info-readonly {
        background: #F8FAFC;
        border-radius: 8px;
        padding: .65rem .9rem;
        font-size: .88rem;
        color: #0D1B2A;
        border: 1.5px solid #E2E8F0;
    }

    /* Séparateur section */
    .section-divider {
        height: 1px;
        background: #E2E8F0;
        margin: 1.2rem 0;
    }

    /* Password strength */
    .pwd-hint {
        font-size: .75rem;
        color: #94A3B8;
        margin-top: .3rem;
    }
</style>
@endpush

@section('content')

    {{-- ── HEADER PROFIL ── --}}
    <div class="profil-header anim-up">
        <div class="profil-avatar-wrap">
            <i class="bi bi-person-fill"></i>
        </div>
        <div class="profil-header-info">
            <h2>{{ $parent->prenom }} {{ $parent->nom }}</h2>
            <p>{{ $user->email }}</p>
            <div class="profil-badge">
                <i class="bi bi-person-check-fill"></i>
                Espace Parent
            </div>
        </div>
    </div>

    {{-- ── TABS ── --}}
    @php $tab = session('tab', request('tab', 'infos')); @endphp

    <div class="profil-tabs">
        <a href="?tab=infos"
           class="profil-tab {{ $tab === 'infos' ? 'active' : '' }}">
            <i class="bi bi-person-lines-fill"></i> Informations personnelles
        </a>
        <a href="?tab=contact"
           class="profil-tab {{ $tab === 'contact' ? 'active' : '' }}">
            <i class="bi bi-telephone-fill"></i> Contact & Adresse
        </a>
        <a href="?tab=securite"
           class="profil-tab {{ $tab === 'securite' ? 'active' : '' }}">
            <i class="bi bi-shield-lock-fill"></i> Sécurité
        </a>
    </div>

    {{-- ══════════════════════════════════════
         TAB : INFORMATIONS PERSONNELLES
    ══════════════════════════════════════ --}}
    @if($tab === 'infos')

        @if(session('success_infos'))
            <div class="alert-success-inline">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success_infos') }}
            </div>
        @endif

        <form method="POST" action="{{ route('parent.profil.infos') }}">
            @csrf
            @method('PUT')

            <div class="profil-card anim-up">
                <div class="profil-card-head">
                    <div class="profil-card-head-icon"
                         style="background:rgba(37,99,235,.1)">
                        <i class="bi bi-person-fill" style="color:#2563EB"></i>
                    </div>
                    <div>
                        <h5>Informations personnelles</h5>
                        <p>Modifiez vos informations d'identité</p>
                    </div>
                </div>
                <div class="profil-card-body">

                    <div class="row g-3">

                        {{-- Civilité --}}
                        <div class="col-12 col-md-3">
                            <label class="form-label">Civilité</label>
                            <select name="civilite" class="form-select">
                                <option value="">—</option>
                                <option value="M."  {{ old('civilite', $parent->civilite) === 'M.'  ? 'selected' : '' }}>M.</option>
                                <option value="Mme" {{ old('civilite', $parent->civilite) === 'Mme' ? 'selected' : '' }}>Mme</option>
                            </select>
                        </div>

                        {{-- Prénom --}}
                        <div class="col-12 col-md-5">
                            <label class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" name="prenom"
                                   class="form-control @error('prenom') is-invalid @enderror"
                                   value="{{ old('prenom', $parent->prenom) }}" required/>
                            @error('prenom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nom --}}
                        <div class="col-12 col-md-4">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom"
                                   class="form-control @error('nom') is-invalid @enderror"
                                   value="{{ old('nom', $parent->nom) }}" required/>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Lien de parenté --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label">Lien de parenté <span class="text-danger">*</span></label>
                            <select name="lien_parente"
                                    class="form-select @error('lien_parente') is-invalid @enderror">
                                @foreach(['Père','Mère','Tuteur','Tutrice','Grand-père','Grand-mère','Oncle','Tante','Autre'] as $lien)
                                    <option value="{{ $lien }}"
                                        {{ old('lien_parente', $parent->lien_parente) === $lien ? 'selected' : '' }}>
                                        {{ $lien }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lien_parente')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Compte (readonly) --}}
                        <div class="col-12 col-md-6">
                            <label class="form-label">Compte créé le</label>
                            <div class="info-readonly">
                                <i class="bi bi-calendar3 me-2 text-muted"></i>
                                {{ $user->created_at->isoFormat('D MMMM YYYY') }}
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn-save">
                    <i class="bi bi-check2-circle"></i> Enregistrer les modifications
                </button>
            </div>

        </form>

    {{-- ══════════════════════════════════════
         TAB : CONTACT & ADRESSE
    ══════════════════════════════════════ --}}
    @elseif($tab === 'contact')

        @if(session('success_infos'))
            <div class="alert-success-inline">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success_infos') }}
            </div>
        @endif

        @if(session('success_email'))
            <div class="alert-success-inline">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success_email') }}
            </div>
        @endif

        {{-- Email --}}
        <form method="POST" action="{{ route('parent.profil.email') }}">
            @csrf
            @method('PUT')

            <div class="profil-card anim-up">
                <div class="profil-card-head">
                    <div class="profil-card-head-icon"
                         style="background:rgba(16,185,129,.1)">
                        <i class="bi bi-envelope-fill" style="color:#10B981"></i>
                    </div>
                    <div>
                        <h5>Adresse e-mail</h5>
                        <p>Votre e-mail est utilisé pour vous connecter</p>
                    </div>
                </div>
                <div class="profil-card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-8">
                            <label class="form-label">E-mail <span class="text-danger">*</span></label>
                            <input type="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required/>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mb-4">
                <button type="submit" class="btn-save">
                    <i class="bi bi-check2-circle"></i> Mettre à jour l'e-mail
                </button>
            </div>
        </form>

        {{-- Téléphone & Adresse --}}
        <form method="POST" action="{{ route('parent.profil.infos') }}">
            @csrf
            @method('PUT')

            {{-- Champs cachés pour les champs obligatoires --}}
            <input type="hidden" name="prenom"       value="{{ $parent->prenom }}">
            <input type="hidden" name="nom"          value="{{ $parent->nom }}">
            <input type="hidden" name="lien_parente" value="{{ $parent->lien_parente }}">
            <input type="hidden" name="telephone"    value="{{ $parent->telephone }}">
            <input type="hidden" name="civilite"     value="{{ $parent->civilite }}">

            <div class="profil-card anim-up">
                <div class="profil-card-head">
                    <div class="profil-card-head-icon"
                         style="background:rgba(245,158,11,.1)">
                        <i class="bi bi-telephone-fill" style="color:#F59E0B"></i>
                    </div>
                    <div>
                        <h5>Téléphones</h5>
                        <p>Vos numéros de contact</p>
                    </div>
                </div>
                <div class="profil-card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Téléphone principal <span class="text-danger">*</span></label>
                            <input type="text" name="telephone"
                                   class="form-control @error('telephone') is-invalid @enderror"
                                   value="{{ old('telephone', $parent->telephone) }}" required/>
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Téléphone secondaire</label>
                            <input type="text" name="telephone_secondaire"
                                   class="form-control"
                                   value="{{ old('telephone_secondaire', $parent->telephone_secondaire) }}"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="profil-card anim-up">
                <div class="profil-card-head">
                    <div class="profil-card-head-icon"
                         style="background:rgba(124,58,237,.1)">
                        <i class="bi bi-geo-alt-fill" style="color:#7C3AED"></i>
                    </div>
                    <div>
                        <h5>Adresse</h5>
                        <p>Votre adresse de résidence</p>
                    </div>
                </div>
                <div class="profil-card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Adresse</label>
                            <input type="text" name="adresse"
                                   class="form-control"
                                   value="{{ old('adresse', $parent->adresse) }}"
                                   placeholder="Rue, quartier…"/>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Ville</label>
                            <input type="text" name="ville"
                                   class="form-control"
                                   value="{{ old('ville', $parent->ville) }}"/>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Arrondissement</label>
                            <input type="text" name="arrondissement"
                                   class="form-control"
                                   value="{{ old('arrondissement', $parent->arrondissement) }}"/>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Code postal</label>
                            <input type="text" name="code_postal"
                                   class="form-control"
                                   value="{{ old('code_postal', $parent->code_postal) }}"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn-save">
                    <i class="bi bi-check2-circle"></i> Enregistrer les modifications
                </button>
            </div>
        </form>

    {{-- ══════════════════════════════════════
         TAB : SÉCURITÉ
    ══════════════════════════════════════ --}}
    @elseif($tab === 'securite')

        @if(session('success_password'))
            <div class="alert-success-inline">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success_password') }}
            </div>
        @endif

        <form method="POST" action="{{ route('parent.profil.password') }}">
            @csrf
            @method('PUT')

            <div class="profil-card anim-up">
                <div class="profil-card-head">
                    <div class="profil-card-head-icon"
                         style="background:rgba(244,63,94,.1)">
                        <i class="bi bi-shield-lock-fill" style="color:#F43F5E"></i>
                    </div>
                    <div>
                        <h5>Changer le mot de passe</h5>
                        <p>Utilisez un mot de passe fort d'au moins 8 caractères</p>
                    </div>
                </div>
                <div class="profil-card-body">
                    <div class="row g-3">

                        <div class="col-12 col-md-8">
                            <label class="form-label">Mot de passe actuel <span class="text-danger">*</span></label>
                            <input type="password" name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   placeholder="••••••••" required/>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="section-divider"></div>
                        </div>

                        <div class="col-12 col-md-8">
                            <label class="form-label">Nouveau mot de passe <span class="text-danger">*</span></label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="••••••••" required/>
                            <div class="pwd-hint">
                                <i class="bi bi-info-circle me-1"></i>
                                Au moins 8 caractères
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-8">
                            <label class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation"
                                   class="form-control"
                                   placeholder="••••••••" required/>
                        </div>

                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn-save">
                    <i class="bi bi-lock-fill"></i> Modifier le mot de passe
                </button>
            </div>
        </form>

    @endif

@endsection