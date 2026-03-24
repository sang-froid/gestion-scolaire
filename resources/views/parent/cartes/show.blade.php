@extends('layouts.app')

@section('title', 'Carte scolaire')
@section('page_title', 'Carte scolaire')
@section('page_subtitle', $eleve->nom_complet)

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap');

.sc-wrapper {
    display: flex;
    gap: 28px;
    flex-wrap: wrap;
    align-items: flex-start;
}

/* ── CARTE (base) ── */
.sc-card {
    width: 413px;
    height: 270px;
    border-radius: 16px;
    overflow: hidden;
    font-family: 'DM Sans', sans-serif;
    box-shadow: 0 8px 32px rgba(13,27,42,.18);
    /* position relative + taille fixe = les enfants absolus s'y calent */
    position: relative;
}

/* ── Enfant qui remplit toute la carte ── */
.sc-card > * {
    position: absolute;
    inset: 0;           /* top:0 right:0 bottom:0 left:0 */
    width: 100%;
    height: 100%;
}

/* ─── RECTO ─── */
.sc-recto {
    background: #fff;
    display: flex;
    flex-direction: column;
}

.sc-recto-strip {
    background: linear-gradient(110deg, #0D1B2A 0%, #1A3A5C 100%);
    height: 62px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    padding: 0 16px;
    gap: 10px;
    position: relative;
    overflow: hidden;
}
.sc-recto-strip::before {
    content: '';
    position: absolute;
    right: -20px; top: -20px;
    width: 90px; height: 90px;
    border-radius: 50%;
    background: rgba(56,189,248,.08);
}

.sc-school-logo {
    width: 36px; height: 36px;
    border-radius: 8px;
    background: rgba(255,255,255,.15);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    overflow: hidden;
    border: 1.5px solid rgba(255,255,255,.2);
}
.sc-school-logo img { width: 100%; height: 100%; object-fit: cover; }
.sc-school-logo .bi { font-size: 1.2rem; color: rgba(255,255,255,.7); }

.sc-school-name { color: #fff; font-size: .82rem; font-weight: 600; line-height: 1.2; }
.sc-school-year { color: rgba(255,255,255,.55); font-size: .68rem; }

.sc-type-badge {
    margin-left: auto;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    color: rgba(255,255,255,.85);
    font-size: .6rem;
    letter-spacing: .8px;
    text-transform: uppercase;
    padding: 3px 8px;
    border-radius: 20px;
}

.sc-body {
    padding: 12px 16px;
    display: flex;
    gap: 14px;
    flex: 1;
    min-height: 0; /* évite que flex déborde */
}

.sc-photo-wrap {
    width: 72px; height: 84px;
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid #E2E8F0;
    background: #F1F5F9;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.sc-photo-wrap img { width: 100%; height: 100%; object-fit: cover; }
.sc-photo-wrap .bi { font-size: 2rem; color: #94A3B8; }

.sc-infos { flex: 1; min-width: 0; }
.sc-name {
    font-family: 'Playfair Display', serif;
    font-size: .95rem;
    color: #0D1B2A;
    font-weight: 700;
    line-height: 1.1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.sc-row       { display: flex; gap: 4px; align-items: baseline; font-size: .7rem; margin-top: 3px; }
.sc-row-label { color: #94A3B8; min-width: 58px; flex-shrink: 0; }
.sc-row-val   { color: #1E293B; font-weight: 500; }

.sc-footer {
    flex-shrink: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px 16px 8px;
    border-top: 1px dashed #E2E8F0;
}
.sc-valid { font-size: .6rem; color: #94A3B8; }
.sc-chip {
    background: #EFF6FF;
    color: #1D4ED8;
    font-size: .6rem;
    font-weight: 600;
    padding: 2px 10px;
    border-radius: 20px;
    letter-spacing: .5px;
    text-transform: uppercase;
}

/* ─── VERSO ─── */
.sc-verso {
    background: linear-gradient(155deg, #0D1B2A 0%, #1A3A5C 60%, #0E3460 100%);
    color: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 7px;
    text-align: center;
    overflow: hidden;
}
.sc-verso::before {
    content: '';
    position: absolute;
    left: -40px; bottom: -40px;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(56,189,248,.07);
    pointer-events: none;
}
.sc-verso::after {
    content: '';
    position: absolute;
    right: -20px; top: -20px;
    width: 100px; height: 100px;
    border-radius: 50%;
    background: rgba(255,255,255,.04);
    pointer-events: none;
}

.sc-verso-logo {
    width: 48px; height: 48px;
    border-radius: 12px;
    background: rgba(255,255,255,.12);
    border: 1.5px solid rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
    position: relative; /* passe au-dessus du ::before/::after */
    z-index: 1;
}
.sc-verso-logo img { width: 100%; height: 100%; object-fit: cover; }
.sc-verso-logo .bi { font-size: 1.4rem; color: rgba(255,255,255,.7); }

.sc-verso-school  { font-weight: 700; font-size: .88rem; letter-spacing: .5px; position: relative; z-index: 1; }
.sc-verso-tagline { font-size: .67rem; opacity: .55; position: relative; z-index: 1; }
.sc-verso-divider { width: 36px; height: 1px; background: rgba(255,255,255,.2); position: relative; z-index: 1; }
.sc-verso-contact { font-size: .64rem; opacity: .65; line-height: 1.9; position: relative; z-index: 1; }
</style>
@endpush


@section('content')

{{-- ── HEADER ── --}}
<div style="background:linear-gradient(115deg,#0D1B2A,#1A3A5C);border-radius:14px;
            padding:1.6rem 2rem;margin-bottom:1.8rem;position:relative;overflow:hidden"
     class="anim-up">
  <div style="position:absolute;right:-40px;top:-40px;width:180px;height:180px;
              border-radius:50%;background:rgba(56,189,248,.06)"></div>
  <h2 style="font-family:'Playfair Display',serif;color:#fff;font-size:1.35rem;margin:0 0 .25rem">
    Cartes scolaires
  </h2>
  <p style="color:rgba(255,255,255,.55);font-size:.87rem;margin:0">
    &nbsp; &nbsp;Cliquez sur télécharger pour avoir la carte au format PDF.
  </p>
</div>

<div class="card-gu anim-up">
<div class="card-body">

<div class="sc-wrapper">

    {{-- ──────────────── RECTO ──────────────── --}}
    <div class="sc-card">
    <div class="sc-recto">

         <div class="sc-recto-strip">
                <div class="sc-school-logo">
                    @php $logoEcole = config('app.logo_ecole'); @endphp
                    @if($logoEcole)
                        <img src="{{ asset($logoEcole) }}" alt="logo">
                    @else
                        <i class="bi bi-mortarboard-fill"></i>
                    @endif
                </div>
            <div>
                <div class="sc-school-name">{{ config('app.nom_ecole', 'École') }}</div>
                <div class="sc-school-year">{{ $eleve->inscription?->annee_scolaire ?? '—' }}</div>
            </div>
            <div class="sc-type-badge">Élève</div>
        </div>

        <div class="sc-body">
            <div class="sc-photo-wrap">
                @if($eleve->photo)
                    <img src="{{ asset('storage/'.$eleve->photo) }}" alt="photo">
                @else
                    <i class="bi bi-person-fill"></i>
                @endif
            </div>
            <div class="sc-infos">
                <div class="sc-name">{{ $eleve->nom }}</div>
                <div class="sc-name">{{ $eleve->prenom }}</div>
                <div class="sc-row">
                    <span class="sc-row-label">Matricule</span>
                    <span class="sc-row-val">{{ $eleve->matricule ?? '—' }}</span>
                </div>
                <div class="sc-row">
                    <span class="sc-row-label">Classe</span>
                    <span class="sc-row-val">{{ $eleve->inscription?->classe?->nom ?? '—' }}</span>
                </div>
                <div class="sc-row">
                    <span class="sc-row-label">Né(e) le</span>
                    <span class="sc-row-val">{{ $eleve->date_naissance?->format('d/m/Y') ?? '—' }}</span>
                </div>
                <div class="sc-row">
                    <span class="sc-row-label">Sexe</span>
                    <span class="sc-row-val">{{ $eleve->sexe ?? '—' }}</span>
                </div>
            </div>
        </div>

        <div class="sc-row">
            <span class="sc-row-label">Nom du parent :</span>
            <span class="sc-row-val">{{ $eleve->parent->nom_complet ?? '—' }}</span>
        </div>

        <div class="sc-footer">
            <span class="sc-valid">Valide pour l'année {{ $eleve->inscription?->annee_scolaire ?? '—' }}</span>
            <span class="sc-chip">Officiel</span>
        </div>

    </div>
    </div>


    {{-- ──────────────── VERSO ──────────────── --}}
    <div class="sc-card">
    <div class="sc-verso">

        <div class="sc-verso-logo">
            @if($logoEcole ?? false)
                <img src="{{ asset($logoEcole) }}" alt="logo">
            @else
                <i class="bi bi-mortarboard-fill"></i>
            @endif
        </div>

        <div class="sc-verso-school">{{ config('app.nom_ecole', 'Nom de l\'école') }}</div>
        <div class="sc-verso-divider"></div>
        <div class="sc-verso-tagline">Carte officielle d'identification scolaire</div>

        <div class="sc-verso-contact">
            <i class="bi bi-geo-alt-fill"></i> {{ config('app.adresse_ecole', 'Adresse de l\'école') }}<br>
            <i class="bi bi-telephone-fill"></i> {{ config('app.tel_ecole', '+XXX XX XX XX XX') }}<br>
            <i class="bi bi-envelope-fill"></i> {{ config('app.email_ecole', 'contact@ecole.bj') }}
        </div>

    </div>
    </div>

</div>

<a href="{{ route('parent.cartes.pdf', $eleve->id) }}" class="btn-primary-gu mt-4">
    <i class="bi bi-download"></i> Télécharger la carte PDF
</a>

</div>
</div>

@endsection