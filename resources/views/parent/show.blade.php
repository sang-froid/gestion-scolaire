@extends('layouts.app')

@section('title', $eleve->prenom . ' — Dossiers')
@section('page_title', $eleve->prenom . ' ' . $eleve->nom)
@section('page_subtitle', $inscriptions->count() . ' dossier(s) · Historique complet')

@push('styles')
<style>
  /* ── Header élève ── */
  .eleve-header {
    background: linear-gradient(115deg, #0D1B2A, #1A3A5C);
    border-radius: 14px; padding: 1.5rem 2rem;
    margin-bottom: 1.8rem;
    display: flex; align-items: center; gap: 1.4rem;
    position: relative; overflow: hidden;
  }
  .eleve-header::after {
    content:''; position:absolute; right:-40px; top:-40px;
    width:200px; height:200px; border-radius:50%;
    background:rgba(56,189,248,.06); pointer-events:none;
  }
  .eleve-header-photo {
    width:70px; height:70px; border-radius:50%; flex-shrink:0;
    border:3px solid rgba(255,255,255,.2);
    background:rgba(255,255,255,.1);
    display:flex; align-items:center; justify-content:center;
    font-size:1.7rem; color:#38BDF8; overflow:hidden;
  }
  .eleve-header-photo img { width:100%; height:100%; object-fit:cover; }
  .eleve-header h2 { font-family:'Playfair Display',serif; color:#fff; font-size:1.3rem; margin:0 0 .25rem; }
  .eleve-header p  { color:rgba(255,255,255,.55); font-size:.83rem; margin:0; }

  /* ── Timeline verticale des dossiers ── */
  .dossiers-timeline { position:relative; padding-left:2rem; }
  .dossiers-timeline::before {
    content:''; position:absolute; left:.6rem; top:0; bottom:0;
    width:2px; background:#E2E8F0;
  }

  .dossier-item {
    position:relative; margin-bottom:1.4rem;
  }
  .dossier-item::before {
    content:''; position:absolute;
    left:-1.72rem; top:1.2rem;
    width:14px; height:14px; border-radius:50%;
    background:#2563EB; border:3px solid #fff;
    box-shadow:0 0 0 2px #2563EB;
    z-index:1;
  }
  .dossier-item.validee::before  { background:#10B981; box-shadow:0 0 0 2px #10B981; }
  .dossier-item.en_attente::before { background:#F59E0B; box-shadow:0 0 0 2px #F59E0B; }
  .dossier-item.refusee::before  { background:#F43F5E; box-shadow:0 0 0 2px #F43F5E; }

  /* ── Carte dossier ── */
  .dossier-card {
    background:#fff; border-radius:12px;
    border:1px solid #E2E8F0;
    box-shadow:0 3px 16px rgba(13,27,42,.06);
    overflow:hidden;
  }
  .dossier-card.is-latest {
    border-color:#2563EB;
    box-shadow:0 4px 20px rgba(37,99,235,.12);
  }

  .dossier-card-head {
    padding:.9rem 1.3rem;
    display:flex; align-items:center; gap:.8rem;
    border-bottom:1px solid #F1F5F9;
    flex-wrap:wrap;
  }
  .annee-tag {
    font-family:'Playfair Display',serif;
    font-size:.95rem; color:#0D1B2A; font-weight:600;
  }
  .annee-tag.latest { color:#2563EB; }
  .current-badge {
    background:rgba(37,99,235,.1); color:#2563EB;
    border:1px solid rgba(37,99,235,.2);
    border-radius:20px; padding:.15rem .65rem;
    font-size:.72rem; font-weight:700;
  }
  .type-badge {
    font-size:.74rem; border-radius:20px; padding:.15rem .65rem;
    font-weight:600;
  }
  .type-nouvelle      { background:#F1F5F9; color:#475569; border:1px solid #E2E8F0; }
  .type-reinscription { background:rgba(124,58,237,.08); color:#7C3AED; border:1px solid rgba(124,58,237,.2); }
  .dossier-numero {
    font-family:monospace; font-size:.76rem;
    color:#94A3B8; background:#F8FAFC;
    border:1px solid #E2E8F0; border-radius:6px;
    padding:.12rem .5rem;
  }

  .dossier-card-body {
    padding:.85rem 1.3rem;
    display:grid; grid-template-columns:repeat(3,1fr);
    gap:.4rem .8rem;
  }
  @media(max-width:600px){ .dossier-card-body{ grid-template-columns:1fr 1fr; } }
  .di .lbl { color:#94A3B8; font-size:.73rem; }
  .di .val { color:#0D1B2A; font-size:.83rem; font-weight:600; }

  .dossier-card-foot {
    padding:.7rem 1.3rem; background:#FAFBFC;
    border-top:1px solid #F1F5F9;
    display:flex; align-items:center;
    justify-content:space-between; gap:.5rem; flex-wrap:wrap;
  }
</style>
@endpush

@section('content')

{{-- ── HEADER ÉLÈVE ── --}}
<div class="eleve-header anim-up">
  <div class="eleve-header-photo">
    @if($eleve->photo)
      <img src="{{ asset('storage/'.$eleve->photo) }}" alt="photo"/>
    @else
      <i class="bi bi-person-fill"></i>
    @endif
  </div>
  <div class="flex-1">
    <h2>{{ $eleve->prenom }} {{ $eleve->nom }}</h2>
    <p>
      <i class="bi bi-calendar3 me-1"></i>
      Né(e) le {{ \Carbon\Carbon::parse($eleve->date_naissance)->isoFormat('D MMMM YYYY') }}
      &nbsp;·&nbsp;
      <i class="bi bi-folder2 me-1"></i>
      {{ $inscriptions->count() }} dossier(s)
    </p>
  </div>

  {{-- Actions rapides en haut --}}
  <div class="d-flex gap-2 flex-wrap">
    @if($anneeSuivante && !$dejaReinscrit && $inscriptions->where('statut','validee')->isNotEmpty())
      <a href="{{ route('parent.reinscription.create', $eleve->id) }}"
         class="btn-primary-gu"
         style="padding:.5rem 1.1rem;font-size:.84rem;
                background:linear-gradient(135deg,#059669,#047857);
                box-shadow:0 4px 14px rgba(5,150,105,.3)">
        <i class="bi bi-arrow-repeat"></i> Réinscrire ({{ $anneeSuivante }})
      </a>
    @elseif($dejaReinscrit)
      <span class="btn-outline-gu"
            style="padding:.5rem 1.1rem;font-size:.84rem;
                   border-color:#10B981;color:#065F46;cursor:default">
        <i class="bi bi-check2-circle"></i> Réinscrit {{ $anneeSuivante }}
      </span>
    @endif
    <a href="{{ route('parent.list') }}"
       class="btn-outline-gu"
       style="padding:.5rem 1rem;font-size:.84rem;
              background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.2);color:#fff">
      <i class="bi bi-arrow-left"></i> Retour
    </a>
  </div>
</div>

{{-- ── TIMELINE DES DOSSIERS ── --}}
@if($inscriptions->isEmpty())
  <div class="card-gu">
    <div class="empty-state">
      <i class="bi bi-folder-x"></i>
      <p>Aucun dossier pour cet élève.</p>
      <a href="{{ route('parent.inscription.create') }}" class="btn-primary-gu mt-3">
        <i class="bi bi-plus-circle"></i> Inscrire maintenant
      </a>
    </div>
  </div>
@else
  <div class="dossiers-timeline">
    @foreach($inscriptions as $inscription)
      @php
        $isLatest = $inscription->annee_scolaire === $derniereAnnee;
        $statut   = $inscription->statut;
        $classe   = $inscription->classe;
      @endphp

      <div class="dossier-item {{ $statut }}">
        <div class="dossier-card {{ $isLatest ? 'is-latest' : '' }}">

          {{-- HEAD ── --}}
          <div class="dossier-card-head">
            <span class="annee-tag {{ $isLatest ? 'latest' : '' }}">
              {{ $inscription->annee_scolaire }}
            </span>
            @if($isLatest)
              <span class="current-badge">En cours</span>
            @endif
            <span class="type-badge {{ $inscription->type === 'reinscription' ? 'type-reinscription' : 'type-nouvelle' }}">
              {{ $inscription->type === 'nouvelle' ? 'Nouvelle inscription' : 'Réinscription' }}
            </span>
            <span class="dossier-numero">{{ $inscription->numero_dossier }}</span>
            <div class="ms-auto">
              @if($statut === 'validee')
                <span class="badge-gu badge-validee"><i class="bi bi-check2-circle"></i> Validée</span>
              @elseif($statut === 'en_attente')
                <span class="badge-gu badge-attente"><i class="bi bi-hourglass-split"></i> En attente</span>
              @else
                <span class="badge-gu badge-refusee"><i class="bi bi-x-circle"></i> Refusée</span>
              @endif
            </div>
          </div>

          {{-- BODY ── --}}
          <div class="dossier-card-body">
            <div class="di">
              <div class="lbl"><i class="bi bi-layers me-1"></i>Niveau</div>
              <div class="val">{{ $classe?->nom ?? $eleve->niveau_souhaite ?? '—' }}</div>
            </div>
            <div class="di">
              <div class="lbl"><i class="bi bi-clock-history me-1"></i>Soumis le</div>
              <div class="val">{{ $inscription->created_at->isoFormat('D MMM YYYY') }}</div>
            </div>
            <div class="di">
              <div class="lbl"><i class="bi bi-collection me-1"></i>Classe</div>
              <div class="val">{{ $classe?->nom ?? '—' }}</div>
            </div>
            <div class="di">
              <div class="lbl"><i class="bi bi-folder2 me-1"></i>Documents</div>
              <div class="val">{{ $inscription->documents->count() }} fichier(s)</div>
            </div>
            @if($inscription->validee_le)
            <div class="di">
              <div class="lbl"><i class="bi bi-calendar-check me-1"></i>Validé le</div>
              <div class="val">{{ $inscription->validee_le->isoFormat('D MMM YYYY') }}</div>
            </div>
            @endif
            @if($inscription->motif_refus)
            <div class="di" style="grid-column:1/-1">
              <div class="lbl" style="color:#F43F5E"><i class="bi bi-exclamation-circle me-1"></i>Motif refus</div>
              <div class="val" style="color:#9F1239;font-weight:400">{{ $inscription->motif_refus }}</div>
            </div>
            @endif
          </div>

          {{-- FOOTER ── --}}
          <div class="dossier-card-foot">
            <div style="font-size:.76rem;color:#94A3B8">
              @if($isLatest)
                <i class="bi bi-star-fill me-1" style="color:#F59E0B"></i>Dossier le plus récent
              @else
                <i class="bi bi-clock-history me-1"></i>Archivé
              @endif
            </div>
            <div class="d-flex gap-2 flex-wrap">

              {{-- Voir le détail --}}
              <a href="{{ route('parent.inscription.show', $inscription->id) }}"
                 class="btn-primary-gu" style="padding:.36rem .85rem;font-size:.78rem">
                <i class="bi bi-eye"></i> Voir le dossier
              </a>

              @if($statut === 'validee')
                <a href="{{ route('parent.inscription.show', $inscription->id) }}?dl=fiche"
                   class="btn-outline-gu" style="padding:.36rem .85rem;font-size:.78rem">
                  <i class="bi bi-download"></i> Fiche PDF
                </a>
                <a href="{{ route('parent.inscription.show', $inscription->id) }}?dl=carte"
                   class="btn-outline-gu" style="padding:.36rem .85rem;font-size:.78rem">
                  <i class="bi bi-credit-card-2-front"></i> Carte
                </a>
              @endif

              @if($statut === 'refusee' && $isLatest)
                <a href="{{ route('parent.inscription.create') }}"
                   class="btn-outline-gu"
                   style="padding:.36rem .85rem;font-size:.78rem;
                          border-color:#F43F5E;color:#9F1239">
                  <i class="bi bi-pencil-square"></i> Soumettre à nouveau
                </a>
              @endif

            </div>
          </div>

        </div>
      </div>

    @endforeach
  </div>
@endif

@endsection
