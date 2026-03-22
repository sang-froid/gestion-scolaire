@extends('layouts.app')

@section('title', 'Mes inscrits — EduGest')
@section('page_title', 'Mes inscrits')
@section('page_subtitle', $eleves->count() . ' enfant(s) enregistré(s)')

@push('styles')
<style>
  .eleve-card {
    background: #fff; border-radius: 16px;
    border: 1px solid #E2E8F0;
    box-shadow: 0 4px 24px rgba(13,27,42,.07);
    overflow: hidden; height: 100%;
    transition: transform .2s, box-shadow .2s;
  }
  .eleve-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(13,27,42,.13);
  }
  .eleve-card-head {
    background: linear-gradient(130deg, #0D1B2A, #1A3A5C);
    padding: 1.6rem;
    display: flex; flex-direction: column;
    align-items: center; text-align: center;
    position: relative; overflow: hidden;
  }
  .eleve-card-head::after {
    content:''; position:absolute; right:-30px; bottom:-30px;
    width:120px; height:120px; border-radius:50%;
    background:rgba(56,189,248,.06); pointer-events:none;
  }
  .eleve-photo {
    width: 72px; height: 72px; border-radius: 50%;
    border: 3px solid rgba(255,255,255,.25);
    background: rgba(255,255,255,.1);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.8rem; color: #38BDF8;
    overflow: hidden; margin-bottom: .8rem;
  }
  .eleve-photo img { width:100%; height:100%; object-fit:cover; }
  .eleve-nom  { font-family:'Playfair Display',serif; color:#fff; font-size:1.05rem; margin:0 0 .2rem; }
  .eleve-sexe { color:rgba(255,255,255,.5); font-size:.78rem; }

  .eleve-card-body { padding: 1.2rem 1.4rem; }
  .eleve-stat-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: .42rem 0; border-bottom: 1px solid #F1F5F9; font-size: .83rem;
  }
  .eleve-stat-row:last-child { border: none; }
  .eleve-stat-row .k { color: #64748B; }
  .eleve-stat-row .v { color: #0D1B2A; font-weight: 600; }

  .statut-pill { display:inline-flex; align-items:center; gap:.3rem; border-radius:20px; padding:.2rem .7rem; font-size:.74rem; font-weight:700; }
  .pill-attente { background:rgba(245,158,11,.1); color:#B45309; border:1px solid rgba(245,158,11,.25); }
  .pill-validee { background:rgba(16,185,129,.1); color:#065F46; border:1px solid rgba(16,185,129,.25); }
  .pill-refusee { background:rgba(244,63,94,.1);  color:#9F1239; border:1px solid rgba(244,63,94,.25); }
  .pill-aucun   { background:#F1F5F9; color:#94A3B8; border:1px solid #E2E8F0; }

  .eleve-card-foot {
    padding: .9rem 1.4rem; background: #FAFBFC;
    border-top: 1px solid #E2E8F0;
    display: flex; gap: .6rem; flex-wrap: wrap;
  }

  .nb-dossiers {
    display:inline-flex; align-items:center; justify-content:center;
    width:22px; height:22px; border-radius:50%;
    background:#EFF6FF; color:#2563EB;
    font-size:.72rem; font-weight:700;
    border:1px solid rgba(37,99,235,.2);
  }
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
    Mes inscrits
  </h2>
  <p style="color:rgba(255,255,255,.55);font-size:.87rem;margin:0">
    {{ $eleves->count() }} enfant(s) enregistré(s)
    &nbsp;·&nbsp; Cliquez sur un enfant pour voir ses dossiers
  </p>
</div>

{{-- ── GRILLE 3 PAR LIGNE ── --}}
{{-- row ici — les col sont DANS le forelse --}}
<div class="row g-4">

  @forelse($eleves as $eleve)
    @php
      $inscription   = $eleve->inscription; // année en cours
      $statut        = $inscription?->statut;
      $nbDossiers    = $eleve->inscriptions->count();

      // Calcul réinscription
      $derniereAnnee  = $eleve->inscriptions->max('annee_scolaire');
      $anneeSuivante  = null;
      $peutReinscrire = false;

      if ($derniereAnnee && $statut === 'validee') {
          $p              = explode('-', $derniereAnnee);
          $anneeSuivante  = $p[1] . '-' . ($p[1] + 1);
          $dejaReinscrit  = $eleve->inscriptions
              ->where('annee_scolaire', $anneeSuivante)
              ->whereIn('statut', ['en_attente', 'validee'])
              ->isNotEmpty();
          $peutReinscrire = !$dejaReinscrit;
      }
    @endphp

    {{-- col-md-4 = 3 par ligne sur md+, col-sm-6 = 2 par ligne sur sm --}}
    <div class="col-12 col-sm-6 col-md-4">
      <div class="eleve-card anim-up">

        {{-- Head --}}
        <div class="eleve-card-head">
          <div class="eleve-photo">
            @if($eleve->photo)
              <img src="{{ asset('storage/'.$eleve->photo) }}" alt="photo"/>
            @else
              <i class="bi bi-person-fill"></i>
            @endif
          </div>
          <div class="eleve-nom">{{ $eleve->prenom }} {{ $eleve->nom }}</div>
          <div class="eleve-sexe">
            <i class="bi bi-{{ $eleve->sexe === 'M' ? 'gender-male' : 'gender-female' }} me-1"></i>
            {{ $eleve->sexe === 'M' ? 'Garçon' : 'Fille' }}
          </div>
        </div>

        {{-- Body --}}
        <div class="eleve-card-body">
          <div class="eleve-stat-row">
            <span class="k"><i class="bi bi-calendar3 me-1"></i>Né(e) le</span>
            <span class="v">{{ \Carbon\Carbon::parse($eleve->date_naissance)->isoFormat('D MMM YYYY') }}</span>
          </div>
          <div class="eleve-stat-row">
            <span class="k"><i class="bi bi-layers me-1"></i>Niveau actuel</span>
            <span class="v">{{ $inscription?->classe?->nom ?? $eleve->niveau_souhaite ?? '—' }}</span>
          </div>
          <div class="eleve-stat-row">
            <span class="k"><i class="bi bi-folder2 me-1"></i>Dossiers</span>
            <span class="v d-flex align-items-center gap-1">
              <span class="nb-dossiers">{{ $nbDossiers }}</span>
              dossier(s)
            </span>
          </div>
          <div class="eleve-stat-row">
            <span class="k"><i class="bi bi-calendar-check me-1"></i>Statut actuel</span>
            <span class="v">
              @if($statut === 'validee')
                <span class="statut-pill pill-validee"><i class="bi bi-check2-circle"></i> Validée</span>
              @elseif($statut === 'en_attente')
                <span class="statut-pill pill-attente"><i class="bi bi-hourglass-split"></i> En attente</span>
              @elseif($statut === 'refusee')
                <span class="statut-pill pill-refusee"><i class="bi bi-x-circle"></i> Refusée</span>
              @else
                <span class="statut-pill pill-aucun">Aucune inscription</span>
              @endif
            </span>
          </div>
        </div>

        {{-- Footer --}}
        <div class="eleve-card-foot">
          <a href="{{ route('parent.eleves.show', $eleve->id) }}"
             class="btn-primary-gu flex-1 justify-content-center"
             style="padding:.5rem .9rem;font-size:.82rem">
            <i class="bi bi-folder2-open"></i> Voir les dossiers
          </a>
          @if($peutReinscrire)
            <a href="{{ route('parent.reinscription.create', $eleve->id) }}"
               class="btn-outline-gu"
               style="padding:.5rem .9rem;font-size:.82rem;border-color:#059669;color:#047857">
              <i class="bi bi-arrow-repeat"></i> Réinscrire
            </a>
          @endif
        </div>

      </div>
    </div>

  @empty
    <div class="col-12">
      <div class="card-gu">
        <div class="empty-state">
          <i class="bi bi-people"></i>
          <p>Aucun enfant enregistré pour le moment.</p>
          <a href="{{ route('parent.inscription.create') }}" class="btn-primary-gu mt-3">
            <i class="bi bi-plus-circle"></i> Inscrire mon enfant
          </a>
        </div>
      </div>
    </div>
  @endforelse

</div>{{-- /row --}}

@endsection