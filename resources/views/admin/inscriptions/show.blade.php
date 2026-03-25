@extends('layouts.app')

@section('title', 'Dossier #' . $inscription->numero_dossier . ' — Admin')
@section('page_title', 'Dossier d\'inscription')
@section('page_subtitle', '#' . $inscription->numero_dossier)

@push('styles')
<style>
  /* Header dossier */
  .dossier-header {
    background: linear-gradient(115deg, #0D1B2A, #1A3A5C);
    border-radius: 14px; padding: 1.6rem 2rem;
    margin-bottom: 1.8rem; display: flex;
    align-items: center; gap: 1.4rem;
    position: relative; overflow: hidden; flex-wrap: wrap;
  }
  .dossier-header::after { content:''; position:absolute; right:-40px; top:-40px; width:200px; height:200px; border-radius:50%; background:rgba(56,189,248,.06); pointer-events:none; }
  .dossier-photo { width:72px; height:72px; border-radius:50%; flex-shrink:0; border:3px solid rgba(255,255,255,.2); background:rgba(255,255,255,.1); display:flex; align-items:center; justify-content:center; font-size:1.7rem; color:#38BDF8; overflow:hidden; }
  .dossier-photo img { width:100%; height:100%; object-fit:cover; }
  .dossier-name { font-family:'Playfair Display',serif; color:#fff; font-size:1.3rem; margin:0 0 .25rem; }
  .dossier-meta { color:rgba(255,255,255,.5); font-size:.83rem; display:flex; flex-wrap:wrap; gap:.4rem 1.2rem; }
  .dossier-meta i { color:#38BDF8; margin-right:.3rem; }

  /* Section card */
  .section-card { background:#fff; border-radius:13px; border:1px solid #E2E8F0; box-shadow:0 3px 16px rgba(13,27,42,.06); margin-bottom:1.2rem; overflow:hidden; }
  .section-head { padding:.85rem 1.4rem; border-bottom:1px solid #E2E8F0; display:flex; align-items:center; gap:.6rem; background:#FAFBFC; }
  .section-head h5 { font-family:'Playfair Display',serif; font-size:.95rem; color:#0D1B2A; margin:0; }
  .section-head i  { color:#2563EB; }
  .section-body { padding:1.2rem 1.4rem; }

  /* Info grid */
  .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:.5rem 2rem; }
  .info-item { padding:.42rem 0; border-bottom:1px solid #F1F5F9; }
  .info-item:last-child { border:none; }
  .info-item .lbl { color:#64748B; font-size:.74rem; font-weight:600; text-transform:uppercase; letter-spacing:.04em; margin-bottom:.15rem; }
  .info-item .val { color:#0D1B2A; font-size:.88rem; font-weight:500; }
  .info-item .val.mono { font-family:monospace; }

  /* Action cards */
  .action-card {
    border-radius:12px; padding:1.2rem 1.4rem; margin-bottom:1rem;
    border:1px solid; position:relative; overflow:hidden;
  }
  .action-card.ac-valide { background:rgba(16,185,129,.05); border-color:rgba(16,185,129,.25); }
  .action-card.ac-refus  { background:rgba(244,63,94,.04);  border-color:rgba(244,63,94,.2); }
  .action-card.ac-classe { background:rgba(37,99,235,.05);  border-color:rgba(37,99,235,.2); }
  .action-card h6 { font-weight:700; font-size:.9rem; margin-bottom:.5rem; }
  .ac-valide h6 { color:#065F46; }
  .ac-refus  h6 { color:#9F1239; }
  .ac-classe h6 { color:#1E40AF; }

  /* Select classe */
  .select-classe { border:1.5px solid #E2E8F0; border-radius:9px; padding:.55rem .9rem; font-size:.87rem; width:100%; margin-bottom:.7rem; }
  .select-classe:focus { border-color:#2563EB; outline:none; }

  /* Documents */
  .doc-item { display:flex; align-items:center; gap:.8rem; padding:.65rem 0; border-bottom:1px solid #F1F5F9; }
  .doc-item:last-child { border:none; }
  .doc-ico { width:36px; height:36px; border-radius:9px; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:1rem; }
  .ico-ok   { background:rgba(16,185,129,.1); color:#10B981; }
  .ico-miss { background:#F1F5F9; color:#94A3B8; }
  .doc-name { font-size:.84rem; font-weight:600; color:#0D1B2A; }
  .doc-sub  { font-size:.76rem; color:#94A3B8; }

  /* Motif refus */
  .motif-refus { background:rgba(244,63,94,.05); border:1px solid rgba(244,63,94,.2); border-left:4px solid #F43F5E; border-radius:10px; padding:1rem 1.2rem; margin-bottom:1rem; }
  .motif-refus .titre { color:#9F1239; font-weight:700; font-size:.88rem; margin-bottom:.3rem; }
  .motif-refus p { color:#7F1D1D; font-size:.85rem; margin:0; }

  textarea { border:1.5px solid #E2E8F0; border-radius:9px; padding:.6rem .9rem; font-size:.87rem; width:100%; resize:vertical; }
  textarea:focus { border-color:#F43F5E; outline:none; }
</style>
@endpush

@section('content')

{{-- ALERTES --}}
@if(session('success'))
  <div class="alert-gu alert-success mb-3">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div class="alert-gu alert-danger mb-3">
    <i class="bi bi-x-circle-fill"></i> {{ session('error') }}
  </div>
@endif

{{-- HEADER DOSSIER --}}
<div class="dossier-header anim-up">
  <div class="dossier-photo">
    @if($inscription->eleve->photo)
      <img src="{{ asset('storage/'.$inscription->eleve->photo) }}" alt=""/>
    @else
      <i class="bi bi-person-fill"></i>
    @endif
  </div>
  <div class="flex-1">
    <div class="dossier-name">{{ $inscription->eleve->prenom }} {{ $inscription->eleve->nom }}</div>
    <div class="dossier-meta">
      <span><i class="bi bi-folder2"></i>{{ $inscription->numero_dossier }}</span>
      <span><i class="bi bi-calendar3"></i>{{ $inscription->annee_scolaire }}</span>
      <span><i class="bi bi-layers"></i>{{ $inscription->eleve->niveau_souhaite }}</span>
      <span><i class="bi bi-clock"></i>Soumis le {{ $inscription->created_at->isoFormat('D MMM YYYY') }}</span>
    </div>
  </div>
  <div class="d-flex align-items-center gap-2" style="position:relative;z-index:1">
    @if($inscription->statut === 'validee')
      <span class="badge-gu badge-validee px-3 py-2"><i class="bi bi-check2-circle me-1"></i>Validée</span>
    @elseif($inscription->statut === 'en_attente')
      <span class="badge-gu badge-attente px-3 py-2"><i class="bi bi-hourglass-split me-1"></i>En attente</span>
    @else
      <span class="badge-gu badge-refusee px-3 py-2"><i class="bi bi-x-circle me-1"></i>Refusée</span>
    @endif
    <a href="{{ route('admin.inscriptions.index') }}"
       class="btn-outline-gu" style="border-color:rgba(255,255,255,.2);color:#fff;padding:.4rem .9rem;font-size:.82rem">
      <i class="bi bi-arrow-left"></i> Retour
    </a>
  </div>
</div>

{{-- MOTIF REFUS --}}
@if($inscription->statut === 'refusee' && $inscription->motif_refus)
  <div class="motif-refus anim-up">
    <div class="titre"><i class="bi bi-exclamation-circle-fill me-1"></i>Motif du refus</div>
    <p>{{ $inscription->motif_refus }}</p>
  </div>
@endif

<div class="row g-4">

  {{-- GAUCHE : infos --}}
  <div class="col-lg-7">

    {{-- Infos élève --}}
    <div class="section-card anim-up">
      <div class="section-head">
        <i class="bi bi-backpack2-fill"></i>
        <h5>Informations de l'élève</h5>
      </div>
      <div class="section-body">
        <div class="info-grid">
          <div class="info-item"><div class="lbl">Nom complet</div><div class="val">{{ $inscription->eleve->prenom }} {{ $inscription->eleve->nom }}</div></div>
          <div class="info-item"><div class="lbl">Date de naissance</div><div class="val">{{ \Carbon\Carbon::parse($inscription->eleve->date_naissance)->isoFormat('D MMMM YYYY') }}</div></div>
          <div class="info-item"><div class="lbl">Lieu de naissance</div><div class="val">{{ $inscription->eleve->lieu_naissance ?? '—' }}</div></div>
          <div class="info-item"><div class="lbl">Sexe</div><div class="val">{{ $inscription->eleve->sexe === 'M' ? 'Masculin' : 'Féminin' }}</div></div>
          <div class="info-item"><div class="lbl">Nationalité</div><div class="val">{{ $inscription->eleve->nationalite ?? '—' }}</div></div>
          <div class="info-item"><div class="lbl">Groupe sanguin</div><div class="val">{{ $inscription->eleve->groupe_sanguin ?? '—' }}</div></div>
          <div class="info-item"><div class="lbl">Niveau souhaité</div><div class="val">{{ $inscription->eleve->niveau_souhaite }}</div></div>
          <div class="info-item"><div class="lbl">Classe affectée</div><div class="val">{{ $inscription->classe?->nom ?? 'Non attribuée' }}</div></div>
          @if($inscription->eleve->ancienne_ecole)
          <div class="info-item"><div class="lbl">Ancienne école</div><div class="val">{{ $inscription->eleve->ancienne_ecole }}</div></div>
          <div class="info-item"><div class="lbl">Dernière classe</div><div class="val">{{ $inscription->eleve->derniere_classe ?? '—' }}</div></div>
          @endif
          @if($inscription->eleve->infos_medicales)
          <div class="info-item" style="grid-column:1/-1"><div class="lbl">Informations médicales</div><div class="val">{{ $inscription->eleve->infos_medicales }}</div></div>
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
          <div class="info-item"><div class="lbl">Nom complet</div><div class="val">{{ $inscription->eleve->parent->civilite ?? '' }} {{ $inscription->eleve->parent->prenom }} {{ $inscription->eleve->parent->nom }}</div></div>
          <div class="info-item"><div class="lbl">Lien de parenté</div><div class="val">{{ $inscription->eleve->parent->lien_parente }}</div></div>
          <div class="info-item"><div class="lbl">Email</div><div class="val">{{ $inscription->eleve->parent->user->email }}</div></div>
          <div class="info-item"><div class="lbl">Téléphone</div><div class="val">{{ $inscription->eleve->parent->telephone }}</div></div>
          <div class="info-item"><div class="lbl">Adresse</div><div class="val">{{ $inscription->eleve->parent->adresse }}</div></div>
          <div class="info-item"><div class="lbl">Ville</div><div class="val">{{ $inscription->eleve->parent->ville }}</div></div>
        </div>
      </div>
    </div>

    {{-- Documents --}}
    <div class="section-card anim-up">
      <div class="section-head">
        <i class="bi bi-folder2-open"></i>
        <h5>Documents ({{ $inscription->documents->count() }})</h5>
      </div>
      <div class="section-body">
        @php
          $liste = [
            'acte_naissance'        => ['Acte de naissance',       true,  'bi-file-earmark-person'],
            'piece_identite_parent' => ["Pièce d'identité parent", true,  'bi-credit-card-2-front'],
            'photo_identite'        => ["Photo d'identité",         true,  'bi-image'],
            'bulletin'              => ['Bulletin scolaire',         false, 'bi-journal-text'],
            'certificat_medical'    => ['Certificat médical',        false, 'bi-heart-pulse'],
          ];
          $parType = $inscription->documents->keyBy('type');
        @endphp
        @foreach($liste as $type => [$label, $requis, $icon])
          @php $doc = $parType->get($type); @endphp
          <div class="doc-item">
            <div class="doc-ico {{ $doc ? 'ico-ok' : 'ico-miss' }}">
              <i class="bi {{ $doc ? 'bi-check-circle-fill' : $icon }}"></i>
            </div>
            <div class="flex-1">
              <div class="doc-name">{{ $label }}</div>
              <div class="doc-sub">{{ $doc ? $doc->nom_fichier : 'Non fourni' }}</div>
            </div>
            @if($doc)
              <a href="{{ asset('storage/'.$doc->chemin_fichier) }}" target="_blank"
                 class="btn-outline-gu" style="padding:.28rem .65rem;font-size:.76rem">
                <i class="bi bi-eye"></i> Voir
              </a>
            @else
              <span style="font-size:.72rem;font-weight:600;
                {{ $requis ? 'color:#9F1239' : 'color:#94A3B8' }}">
                {{ $requis ? 'Requis' : 'Optionnel' }}
              </span>
            @endif
          </div>
        @endforeach
      </div>
    </div>

  </div>

  {{-- DROITE : actions admin --}}
  <div class="col-lg-5">

    {{-- Récapitulatif --}}
    <div class="section-card anim-up">
      <div class="section-head">
        <i class="bi bi-info-circle-fill"></i>
        <h5>Récapitulatif</h5>
      </div>
      <div class="section-body">
        <div class="info-item"><div class="lbl">N° dossier</div><div class="val mono">{{ $inscription->numero_dossier }}</div></div>
        <div class="info-item"><div class="lbl">Type</div><div class="val">{{ $inscription->type === 'nouvelle' ? 'Nouvelle inscription' : 'Réinscription' }}</div></div>
        <div class="info-item"><div class="lbl">Année scolaire</div><div class="val">{{ $inscription->annee_scolaire }}</div></div>
        <div class="info-item"><div class="lbl">Soumis le</div><div class="val">{{ $inscription->created_at->isoFormat('D MMMM YYYY [à] HH[h]mm') }}</div></div>
        @if($inscription->validee_le)
        <div class="info-item"><div class="lbl">Traité le</div><div class="val">{{ $inscription->validee_le->isoFormat('D MMMM YYYY') }}</div></div>
        @endif
        @if($inscription->validePar)
        <div class="info-item"><div class="lbl">Traité par</div><div class="val">{{ $inscription->validePar->name }}</div></div>
        @endif
      </div>
    </div>

    {{-- ══ ACTIONS ADMIN ══ --}}
    @if($inscription->statut === 'en_attente')

      {{-- Valider --}}
      <div class="action-card ac-valide anim-up">
        <h6><i class="bi bi-check2-circle me-2"></i>Valider le dossier</h6>
        <p style="font-size:.84rem;color:#047857;margin-bottom:.9rem;line-height:1.5">
          Approuver ce dossier et informer le parent par email.
        </p>
        <form action="{{ route('admin.inscriptions.valider', $inscription->id) }}" method="POST">
          @csrf
          <button type="submit"
                  class="btn-primary-gu w-100 justify-content-center"
                  style="background:linear-gradient(135deg,#059669,#047857);box-shadow:0 4px 14px rgba(5,150,105,.3)"
                  onclick="return confirm('Valider le dossier #{{ $inscription->numero_dossier }} ? Un email sera envoyé au parent.')">
            <i class="bi bi-check2-circle"></i> Valider l'inscription
          </button>
        </form>
      </div>

      {{-- Refuser --}}
      <div class="action-card ac-refus anim-up">
        <h6><i class="bi bi-x-circle me-2"></i>Refuser le dossier</h6>
        <form action="{{ route('admin.inscriptions.refuser', $inscription->id) }}" method="POST">
          @csrf
          <textarea name="motif_refus" rows="3"
                    placeholder="Motif du refus (obligatoire) — sera communiqué au parent…"
                    style="border-color:rgba(244,63,94,.3);margin-bottom:.7rem"
                    required>{{ old('motif_refus') }}</textarea>
          @error('motif_refus')
            <div style="color:#F43F5E;font-size:.78rem;margin-bottom:.5rem">{{ $message }}</div>
          @enderror
          <button type="submit"
                  class="btn-primary-gu w-100 justify-content-center"
                  style="background:linear-gradient(135deg,#F43F5E,#E11D48);box-shadow:0 4px 14px rgba(244,63,94,.3)">
            <i class="bi bi-x-circle"></i> Refuser le dossier
          </button>
        </form>
      </div>

    @endif

    {{-- Affecter à une classe (si validé) --}}
    @if($inscription->statut === 'validee')
      <div class="action-card ac-classe anim-up">
        <h6><i class="bi bi-collection me-2"></i>
          {{ $inscription->classe ? 'Modifier la classe' : 'Affecter à une classe' }}
        </h6>
        @if($inscription->classe)
          <p style="font-size:.83rem;color:#1E40AF;margin-bottom:.8rem">
            Actuellement : <strong>{{ $inscription->classe->nom }}</strong>
          </p>
        @endif
        @if($classes->count() > 0)
          <form action="{{ route('admin.inscriptions.affecter', $inscription->id) }}" method="POST">
            @csrf
            <select name="classe_id" class="select-classe" required>
              <option value="">Choisir une classe…</option>
              @foreach($classes as $classe)
                <option value="{{ $classe->id }}"
                  {{ $inscription->classe_id == $classe->id ? 'selected' : '' }}>
                  {{ $classe->nom }} — {{ $classe->niveau }}
                  ({{ $classe->nb_eleves ?? 0 }}/{{ $classe->capacite_max }} élèves)
                </option>
              @endforeach
            </select>
            @error('classe_id')
              <div style="color:#F43F5E;font-size:.78rem;margin-bottom:.5rem">{{ $message }}</div>
            @enderror
            <button type="submit"
                    class="btn-primary-gu w-100 justify-content-center"
                    style="font-size:.86rem">
              <i class="bi bi-collection"></i> Affecter la classe
            </button>
          </form>
        @else
          <p style="font-size:.83rem;color:#64748B">
            <i class="bi bi-exclamation-circle me-1"></i>
            Aucune classe disponible.
            <a href="{{ route('admin.classes.create') }}" style="color:#2563EB">Créer une classe</a>
          </p>
        @endif
      </div>
    @endif

    {{-- Dossier déjà traité --}}
    @if($inscription->statut === 'refusee')
      <div class="section-card anim-up">
        <div class="section-body" style="text-align:center;padding:1.5rem">
          <i class="bi bi-x-circle-fill" style="font-size:2.5rem;color:#F43F5E;opacity:.4;display:block;margin-bottom:.5rem"></i>
          <p style="color:#64748B;font-size:.88rem">Ce dossier a été refusé. Aucune action possible.</p>
        </div>
      </div>
    @endif

  </div>
</div>

@endsection
