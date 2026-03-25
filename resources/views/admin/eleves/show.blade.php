@extends('layouts.app')

@section('title', $eleve->prenom . ' ' . $eleve->nom . ' — Admin')
@section('page_title', $eleve->prenom . ' ' . $eleve->nom)
@section('page_subtitle', 'Profil élève · ' . $eleve->inscriptions->count() . ' dossier(s)')

@push('styles')
<style>
  .eleve-banner {
    background: linear-gradient(115deg, #0D1B2A, #1A3A5C);
    border-radius: 14px; padding: 1.6rem 2rem;
    margin-bottom: 1.8rem; display: flex;
    align-items: center; gap: 1.4rem;
    position: relative; overflow: hidden; flex-wrap: wrap;
  }
  .eleve-banner::after { content:''; position:absolute; right:-40px; top:-40px; width:200px; height:200px; border-radius:50%; background:rgba(56,189,248,.06); pointer-events:none; }
  .eleve-photo { width:80px; height:80px; border-radius:50%; flex-shrink:0; border:3px solid rgba(255,255,255,.2); background:rgba(255,255,255,.1); display:flex; align-items:center; justify-content:center; font-size:2rem; color:#38BDF8; overflow:hidden; }
  .eleve-photo img { width:100%; height:100%; object-fit:cover; }
  .eleve-name { font-family:'Playfair Display',serif; color:#fff; font-size:1.4rem; margin:0 0 .25rem; }
  .eleve-meta { color:rgba(255,255,255,.5); font-size:.83rem; display:flex; flex-wrap:wrap; gap:.3rem 1.1rem; }
  .eleve-meta i { color:#38BDF8; margin-right:.3rem; }

  .section-card { background:#fff; border-radius:13px; border:1px solid #E2E8F0; box-shadow:0 3px 16px rgba(13,27,42,.06); margin-bottom:1.2rem; overflow:hidden; }
  .section-head { padding:.85rem 1.4rem; border-bottom:1px solid #E2E8F0; display:flex; align-items:center; gap:.6rem; background:#FAFBFC; }
  .section-head h5 { font-family:'Playfair Display',serif; font-size:.95rem; color:#0D1B2A; margin:0; }
  .section-head i  { color:#2563EB; }
  .section-body { padding:1.2rem 1.4rem; }

  .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:.5rem 2rem; }
  .info-item { padding:.42rem 0; border-bottom:1px solid #F1F5F9; }
  .info-item:last-child { border:none; }
  .info-item .lbl { color:#64748B; font-size:.74rem; font-weight:600; text-transform:uppercase; letter-spacing:.04em; margin-bottom:.15rem; }
  .info-item .val { color:#0D1B2A; font-size:.88rem; font-weight:500; }

  /* Timeline dossiers */
  .dossier-timeline { position:relative; padding-left:1.8rem; }
  .dossier-timeline::before { content:''; position:absolute; left:.55rem; top:0; bottom:0; width:2px; background:#E2E8F0; }
  .dossier-item { position:relative; margin-bottom:1rem; }
  .dossier-item::before { content:''; position:absolute; left:-1.55rem; top:1.1rem; width:12px; height:12px; border-radius:50%; border:3px solid #fff; z-index:1; }
  .dossier-item.validee::before  { background:#10B981; box-shadow:0 0 0 2px #10B981; }
  .dossier-item.en_attente::before { background:#F59E0B; box-shadow:0 0 0 2px #F59E0B; }
  .dossier-item.refusee::before  { background:#F43F5E; box-shadow:0 0 0 2px #F43F5E; }

  .dossier-card { background:#fff; border-radius:11px; border:1px solid #E2E8F0; overflow:hidden; }
  .dossier-card.latest { border-color:#2563EB; box-shadow:0 3px 14px rgba(37,99,235,.1); }
  .dossier-card-head { padding:.8rem 1.2rem; display:flex; align-items:center; gap:.7rem; flex-wrap:wrap; border-bottom:1px solid #F1F5F9; }
  .dossier-card-body { padding:.75rem 1.2rem; display:grid; grid-template-columns:repeat(3,1fr); gap:.4rem .8rem; }
  .di .lbl { color:#94A3B8; font-size:.72rem; }
  .di .val { color:#0D1B2A; font-size:.83rem; font-weight:600; }
  .dossier-card-foot { padding:.6rem 1.2rem; background:#FAFBFC; border-top:1px solid #F1F5F9; display:flex; justify-content:flex-end; gap:.5rem; }

  .annee-tag { font-family:'Playfair Display',serif; font-size:.92rem; font-weight:700; color:#0D1B2A; }
  .annee-tag.latest { color:#2563EB; }
  .current-badge { background:rgba(37,99,235,.1); color:#2563EB; border:1px solid rgba(37,99,235,.2); border-radius:20px; padding:.15rem .65rem; font-size:.72rem; font-weight:700; }
  .type-tag { font-size:.74rem; border-radius:20px; padding:.15rem .6rem; font-weight:600; }
  .type-nouvelle      { background:#F1F5F9; color:#475569; border:1px solid #E2E8F0; }
  .type-reinscription { background:rgba(124,58,237,.08); color:#7C3AED; border:1px solid rgba(124,58,237,.2); }
  .num-tag { font-family:monospace; font-size:.75rem; color:#94A3B8; background:#F8FAFC; border:1px solid #E2E8F0; border-radius:6px; padding:.1rem .5rem; }
</style>
@endpush

@section('content')

{{-- BANNER --}}
<div class="eleve-banner anim-up">
  <div class="eleve-photo">
    @if($eleve->photo)
      <img src="{{ asset('storage/'.$eleve->photo) }}" alt="photo"/>
    @else
      <i class="bi bi-person-fill"></i>
    @endif
  </div>
  <div class="flex-1">
    <div class="eleve-name">{{ $eleve->prenom }} {{ $eleve->nom }}</div>
    <div class="eleve-meta">
      <span><i class="bi bi-calendar3"></i>{{ \Carbon\Carbon::parse($eleve->date_naissance)->isoFormat('D MMMM YYYY') }}</span>
      <span><i class="bi bi-{{ $eleve->sexe === 'M' ? 'gender-male' : 'gender-female' }}"></i>{{ $eleve->sexe === 'M' ? 'Garçon' : 'Fille' }}</span>
      <span><i class="bi bi-flag"></i>{{ $eleve->nationalite ?? 'Béninoise' }}</span>
      <span><i class="bi bi-folder2"></i>{{ $eleve->inscriptions->count() }} dossier(s)</span>
    </div>
  </div>
  <div class="d-flex gap-2" style="position:relative;z-index:1">
    <a href="{{ route('admin.eleves.index') }}"
       class="btn-outline-gu" style="border-color:rgba(255,255,255,.2);color:#fff;padding:.45rem .95rem;font-size:.83rem">
      <i class="bi bi-arrow-left"></i> Retour
    </a>
  </div>
</div>

<div class="row g-4">

  {{-- GAUCHE : infos --}}
  <div class="col-lg-5">

    {{-- Infos élève --}}
    <div class="section-card anim-up">
      <div class="section-head">
        <i class="bi bi-backpack2-fill"></i>
        <h5>Informations personnelles</h5>
      </div>
      <div class="section-body">
        <div class="info-grid">
          <div class="info-item"><div class="lbl">Nom</div><div class="val">{{ $eleve->nom }}</div></div>
          <div class="info-item"><div class="lbl">Prénom</div><div class="val">{{ $eleve->prenom }}</div></div>
          <div class="info-item"><div class="lbl">Date de naissance</div><div class="val">{{ \Carbon\Carbon::parse($eleve->date_naissance)->isoFormat('D MMMM YYYY') }}</div></div>
          <div class="info-item"><div class="lbl">Lieu de naissance</div><div class="val">{{ $eleve->lieu_naissance ?? '—' }}</div></div>
          <div class="info-item"><div class="lbl">Sexe</div><div class="val">{{ $eleve->sexe === 'M' ? 'Masculin' : 'Féminin' }}</div></div>
          <div class="info-item"><div class="lbl">Nationalité</div><div class="val">{{ $eleve->nationalite ?? '—' }}</div></div>
          <div class="info-item"><div class="lbl">Groupe sanguin</div><div class="val">{{ $eleve->groupe_sanguin ?? '—' }}</div></div>
          <div class="info-item"><div class="lbl">N° acte naissance</div><div class="val" style="font-family:monospace;font-size:.82rem">{{ $eleve->numero_acte_naissance ?? '—' }}</div></div>
          @if($eleve->infos_medicales)
            <div class="info-item" style="grid-column:1/-1">
              <div class="lbl">Infos médicales</div>
              <div class="val" style="font-weight:400;color:#64748B">{{ $eleve->infos_medicales }}</div>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Infos scolaires --}}
    <div class="section-card anim-up">
      <div class="section-head">
        <i class="bi bi-journal-bookmark-fill"></i>
        <h5>Scolarité</h5>
      </div>
      <div class="section-body">
        <div class="info-grid">
          <div class="info-item"><div class="lbl">Niveau souhaité</div><div class="val">{{ $eleve->niveau_souhaite ?? '—' }}</div></div>
          <div class="info-item"><div class="lbl">Classe actuelle</div><div class="val">{{ $eleve->inscription?->classe?->nom ?? 'Non affecté' }}</div></div>
          @if($eleve->ancienne_ecole)
            <div class="info-item"><div class="lbl">Ancienne école</div><div class="val">{{ $eleve->ancienne_ecole }}</div></div>
            <div class="info-item"><div class="lbl">Dernière classe</div><div class="val">{{ $eleve->derniere_classe ?? '—' }}</div></div>
          @endif
        </div>
      </div>
    </div>

    {{-- Infos parent --}}
    <div class="section-card anim-up">
      <div class="section-head">
        <i class="bi bi-person-vcard-fill"></i>
        <h5>Parent / Tuteur</h5>
      </div>
      <div class="section-body">
        <div class="info-grid">
          <div class="info-item"><div class="lbl">Nom complet</div><div class="val">{{ $eleve->parent->civilite ?? '' }} {{ $eleve->parent->prenom }} {{ $eleve->parent->nom }}</div></div>
          <div class="info-item"><div class="lbl">Lien de parenté</div><div class="val">{{ $eleve->parent->lien_parente }}</div></div>
          <div class="info-item"><div class="lbl">Email</div><div class="val" style="font-size:.82rem">{{ $eleve->parent->user->email }}</div></div>
          <div class="info-item"><div class="lbl">Téléphone</div><div class="val">{{ $eleve->parent->telephone }}</div></div>
          <div class="info-item"><div class="lbl">Adresse</div><div class="val">{{ $eleve->parent->adresse }}</div></div>
          <div class="info-item"><div class="lbl">Ville</div><div class="val">{{ $eleve->parent->ville }}</div></div>
        </div>
      </div>
    </div>

  </div>

  {{-- DROITE : historique dossiers --}}
  <div class="col-lg-7">
    <div class="section-card anim-up">
      <div class="section-head">
        <i class="bi bi-clock-history"></i>
        <h5>Historique des inscriptions ({{ $eleve->inscriptions->count() }})</h5>
      </div>
      <div class="section-body">

        @php $derniereAnnee = $eleve->inscriptions->max('annee_scolaire'); @endphp

        @forelse($eleve->inscriptions as $insc)
          @php
            $isLatest = $insc->annee_scolaire === $derniereAnnee;
            $statut   = $insc->statut;
          @endphp

          <div class="dossier-item {{ $statut }}">
            <div class="dossier-card {{ $isLatest ? 'latest' : '' }}">

              {{-- Head --}}
              <div class="dossier-card-head">
                <span class="annee-tag {{ $isLatest ? 'latest' : '' }}">
                  {{ $insc->annee_scolaire }}
                </span>
                @if($isLatest)
                  <span class="current-badge">En cours</span>
                @endif
                <span class="type-tag {{ $insc->type === 'reinscription' ? 'type-reinscription' : 'type-nouvelle' }}">
                  {{ $insc->type === 'nouvelle' ? 'Nouvelle inscription' : 'Réinscription' }}
                </span>
                <span class="num-tag">{{ $insc->numero_dossier }}</span>
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

              {{-- Body --}}
              <div class="dossier-card-body">
                <div class="di">
                  <div class="lbl">Niveau</div>
                  <div class="val">{{ $insc->classe?->nom ?? $eleve->niveau_souhaite ?? '—' }}</div>
                </div>
                <div class="di">
                  <div class="lbl">Classe</div>
                  <div class="val">{{ $insc->classe?->nom ?? '—' }}</div>
                </div>
                <div class="di">
                  <div class="lbl">Soumis le</div>
                  <div class="val">{{ $insc->created_at->isoFormat('D MMM YYYY') }}</div>
                </div>
                <div class="di">
                  <div class="lbl">Documents</div>
                  <div class="val">{{ $insc->documents->count() }} fichier(s)</div>
                </div>
                @if($insc->validee_le)
                  <div class="di">
                    <div class="lbl">{{ $statut === 'refusee' ? 'Refusé le' : 'Validé le' }}</div>
                    <div class="val">{{ $insc->validee_le->isoFormat('D MMM YYYY') }}</div>
                  </div>
                @endif
                @if($insc->motif_refus)
                  <div class="di" style="grid-column:1/-1">
                    <div class="lbl" style="color:#F43F5E">Motif refus</div>
                    <div class="val" style="color:#9F1239;font-weight:400;font-size:.82rem">{{ $insc->motif_refus }}</div>
                  </div>
                @endif
              </div>

              {{-- Foot --}}
              <div class="dossier-card-foot">
                <a href="{{ route('admin.inscriptions.show', $insc->id) }}"
                   class="btn-primary-gu" style="padding:.34rem .85rem;font-size:.79rem">
                  <i class="bi bi-eye"></i> Voir le dossier
                </a>
              </div>

            </div>
          </div>

        @empty
          <div class="empty-state">
            <i class="bi bi-folder-x"></i>
            <p>Aucun dossier pour cet élève.</p>
          </div>
        @endforelse

      </div>
    </div>
  </div>

</div>

@endsection