@extends('layouts.app')

@section('title', 'Réinscription — ' . $eleve->prenom . ' ' . $eleve->nom)
@section('page_title', 'Réinscription')
@section('page_subtitle', $eleve->prenom . ' ' . $eleve->nom . ' — ' . $anneeCourante)

@push('styles')
<style>
  /* ── Header élève ── */
  .eleve-banner {
    background: linear-gradient(115deg, #0D1B2A 0%, #1A3A5C 100%);
    border-radius: 14px; padding: 1.5rem 2rem;
    margin-bottom: 1.8rem; display: flex;
    align-items: center; gap: 1.2rem;
    position: relative; overflow: hidden;
  }
  .eleve-banner::after {
    content:''; position:absolute; right:-40px; top:-40px;
    width:180px; height:180px; border-radius:50%;
    background:rgba(56,189,248,.06); pointer-events:none;
  }
  .eleve-banner-photo {
    width:65px; height:65px; border-radius:50%; flex-shrink:0;
    border:3px solid rgba(255,255,255,.2); overflow:hidden;
    background:rgba(255,255,255,.1);
    display:flex; align-items:center; justify-content:center;
    font-size:1.5rem; color:#38BDF8;
  }
  .eleve-banner-photo img { width:100%; height:100%; object-fit:cover; }
  .eleve-banner h2 { font-family:'Playfair Display',serif; color:#fff; font-size:1.2rem; margin:0 0 .25rem; }
  .eleve-banner p  { color:rgba(255,255,255,.55); font-size:.82rem; margin:0; }
  .eleve-banner p span { color:#38BDF8; font-weight:600; }

  /* ── Champ lecture seule ── */
  .field-readonly {
    background: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 10px;
    padding: .65rem 1rem;
    font-size: .9rem;
    color: #64748B;
    cursor: not-allowed;
    display: flex; align-items: center; gap: .5rem;
  }
  .field-readonly i { color: #CBD5E1; }
  .readonly-badge {
    display: inline-flex; align-items: center; gap: .3rem;
    background: #F1F5F9; color: #64748B;
    border: 1px solid #E2E8F0; border-radius: 20px;
    padding: .15rem .6rem; font-size: .7rem; font-weight: 600;
    margin-left: auto;
  }

  /* ── Section card ── */
  .form-section {
    background: #fff; border-radius: 14px;
    border: 1px solid #E2E8F0;
    box-shadow: 0 4px 20px rgba(13,27,42,.07);
    margin-bottom: 1.2rem; overflow: hidden;
  }
  .form-section-head {
    background: linear-gradient(110deg, #0D1B2A, #1A3A5C);
    padding: 1rem 1.6rem;
    display: flex; align-items: center; gap: .8rem;
  }
  .form-section-head h5 { font-family:'Playfair Display',serif; color:#fff; font-size:1rem; margin:0; }
  .form-section-head i  { color:#38BDF8; font-size:1.1rem; }
  .form-section-head .badge-modif {
    margin-left:auto; background:rgba(56,189,248,.15);
    color:#38BDF8; border:1px solid rgba(56,189,248,.25);
    border-radius:20px; padding:.2rem .75rem;
    font-size:.72rem; font-weight:600;
  }
  .form-section-head .badge-lock {
    margin-left:auto; background:rgba(255,255,255,.08);
    color:rgba(255,255,255,.4); border:1px solid rgba(255,255,255,.12);
    border-radius:20px; padding:.2rem .75rem;
    font-size:.72rem; font-weight:600;
  }
  .form-section-body { padding: 1.6rem 2rem; }

  /* ── Inputs ── */
  .form-label { font-size:.82rem; font-weight:600; color:#0D1B2A; margin-bottom:.45rem; letter-spacing:.02em; }
  .required-star { color:#EF4444; margin-left:3px; }
  .form-control, .form-select {
    border: 1.5px solid #E2E8F0; border-radius: 10px;
    padding: .65rem 1rem; font-size: .9rem;
    font-family: 'DM Sans', sans-serif; color: #0D1B2A;
    transition: all .2s;
  }
  .form-control:focus, .form-select:focus {
    border-color: #2563EB;
    box-shadow: 0 0 0 3px rgba(37,99,235,.1);
    outline: none;
  }
  .form-control.is-invalid, .form-select.is-invalid { border-color: #EF4444; }
  .invalid-msg { color:#EF4444; font-size:.78rem; margin-top:.3rem; }
  .help-text   { color:#64748B; font-size:.78rem; margin-top:.3rem; }

  /* ── Photo upload ── */
  .photo-preview {
    width:80px; height:80px; border-radius:50%;
    border:3px solid #E2E8F0; overflow:hidden;
    background:linear-gradient(135deg,#EFF6FF,#DBEAFE);
    display:flex; align-items:center; justify-content:center;
    font-size:1.8rem; color:#64748B; margin:0 auto .8rem;
  }
  .photo-preview img { width:100%; height:100%; object-fit:cover; }
  .photo-upload-btn {
    border:2px dashed #E2E8F0; border-radius:10px;
    padding:1rem; text-align:center; cursor:pointer;
    transition:all .25s; background:#F8FAFC;
  }
  .photo-upload-btn:hover { border-color:#2563EB; background:#EFF6FF; }
  .photo-upload-btn i   { font-size:1.4rem; color:#2563EB; display:block; margin-bottom:.3rem; }
  .photo-upload-btn p   { color:#64748B; font-size:.82rem; margin:0; }
  .photo-upload-btn span{ color:#2563EB; font-weight:600; }

  /* ── File zone ── */
  .file-zone {
    border:2px dashed #E2E8F0; border-radius:10px;
    padding:1rem 1.2rem; cursor:pointer;
    transition:all .25s; display:flex; align-items:center; gap:.9rem;
  }
  .file-zone:hover { border-color:#2563EB; background:rgba(37,99,235,.02); }
  .file-zone.file-ok { border-color:#10B981; background:rgba(16,185,129,.03); }
  .file-zone .fz-icon {
    width:42px; height:42px; border-radius:9px; flex-shrink:0;
    background:#EFF6FF; display:flex; align-items:center;
    justify-content:center; font-size:1.2rem; color:#2563EB;
  }
  .file-zone p    { margin:0; color:#0D1B2A; font-size:.86rem; font-weight:500; }
  .file-zone span { color:#64748B; font-size:.78rem; }

  /* ── Info banner ── */
  .info-banner {
    background:linear-gradient(135deg,rgba(37,99,235,.06),rgba(56,189,248,.06));
    border:1px solid rgba(37,99,235,.15); border-left:4px solid #2563EB;
    border-radius:10px; padding:.9rem 1.1rem;
    display:flex; gap:.8rem; align-items:flex-start;
    margin-bottom:1.6rem;
  }
  .info-banner i { color:#2563EB; font-size:1rem; flex-shrink:0; margin-top:.1rem; }
  .info-banner p { margin:0; color:#0D1B2A; font-size:.83rem; line-height:1.6; }
  .info-banner strong { color:#2563EB; }

  /* ── Actions ── */
  .form-actions {
    display:flex; justify-content:space-between; align-items:center;
    padding-top:1.6rem; border-top:1px solid #E2E8F0; margin-top:1.8rem;
  }

  /* Alertes erreurs serveur */
  .alert-server {
    display:flex; align-items:flex-start; gap:.8rem;
    background:rgba(244,63,94,.07); border:1px solid rgba(244,63,94,.2);
    border-left:4px solid #F43F5E; border-radius:10px;
    padding:.9rem 1.1rem; margin-bottom:1.4rem; font-size:.86rem; color:#7F1D1D;
  }
  .alert-server i { color:#F43F5E; flex-shrink:0; margin-top:.1rem; }
</style>
@endpush

@section('content')

{{-- ── HEADER ÉLÈVE ── --}}
<div class="eleve-banner anim-up">
  <div class="eleve-banner-photo">
    @if($eleve->photo)
      <img src="{{ asset('storage/'.$eleve->photo) }}" alt="photo"/>
    @else
      <i class="bi bi-person-fill"></i>
    @endif
  </div>
  <div>
    <h2>Réinscription de {{ $eleve->prenom }} {{ $eleve->nom }}</h2>
    <p>
      Dernière inscription :
      <span>{{ $eleve->inscription?->classe?->nom ?? $eleve->niveau_souhaite }}</span>
      — Année scolaire cible : <span>{{ $anneeCourante }}</span>
    </p>
  </div>
</div>

{{-- ── ERREURS SERVEUR ── --}}
@if($errors->any())
  <div class="alert-server">
    <i class="bi bi-exclamation-circle-fill"></i>
    <div>
      <strong>Veuillez corriger les erreurs :</strong>
      <ul class="mb-0 mt-1 ps-3">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  </div>
@endif

@if(session('error'))
  <div class="alert-server">
    <i class="bi bi-x-circle-fill"></i>
    <span>{{ session('error') }}</span>
  </div>
@endif

{{-- ══ FORMULAIRE ══ --}}
<form action="{{ route('parent.reinscription.store', $eleve->id) }}"
      method="POST"
      enctype="multipart/form-data"
      id="reinscriptionForm"
      novalidate>
  @csrf

  {{-- ═══════════════════════════════════════
       SECTION 1 : INFOS ÉLÈVE (mixte)
  ═══════════════════════════════════════ --}}
  <div class="form-section anim-up">
    <div class="form-section-head">
      <i class="bi bi-backpack2-fill"></i>
      <h5>Informations de l'élève</h5>
      <span class="badge-modif"><i class="bi bi-pencil me-1"></i>Partiellement modifiable</span>
    </div>
    <div class="form-section-body">

      <div class="info-banner">
        <i class="bi bi-info-circle-fill"></i>
        <p>
          Les champs <strong>grisés</strong> (nom, prénom, date de naissance, sexe)
          ne sont pas modifiables. Pour une correction, contactez le secrétariat.
        </p>
      </div>

      <div class="row g-4 mb-3">

        {{-- Photo --}}
        <div class="col-md-3 text-center">
          <label class="form-label d-block">Photo</label>
          <div class="photo-preview" id="photoPreview">
            @if($eleve->photo)
              <img src="{{ asset('storage/'.$eleve->photo) }}" id="photoImg" alt="photo"/>
            @else
              <i class="bi bi-person" id="photoIcon"></i>
            @endif
          </div>
          <label class="photo-upload-btn">
            <i class="bi bi-camera"></i>
            <p><span>Changer la photo</span><br>
            <small class="text-muted">JPG/PNG — max 2 Mo</small></p>
            <input type="file" name="eleve_photo" accept="image/*"
                   class="d-none" onchange="previewPhoto(this)"/>
          </label>
        </div>

        <div class="col-md-9">
          <div class="row g-3">

            {{-- NOM — lecture seule --}}
            <div class="col-md-6">
              <label class="form-label d-flex align-items-center">
                Nom
                <span class="readonly-badge ms-2"><i class="bi bi-lock-fill"></i> Non modifiable</span>
              </label>
              <div class="field-readonly">
                <i class="bi bi-person"></i>
                {{ $eleve->nom }}
              </div>
            </div>

            {{-- PRÉNOM — lecture seule --}}
            <div class="col-md-6">
              <label class="form-label d-flex align-items-center">
                Prénom
                <span class="readonly-badge ms-2"><i class="bi bi-lock-fill"></i> Non modifiable</span>
              </label>
              <div class="field-readonly">
                <i class="bi bi-person"></i>
                {{ $eleve->prenom }}
              </div>
            </div>

            {{-- DATE DE NAISSANCE — lecture seule --}}
            <div class="col-md-6">
              <label class="form-label d-flex align-items-center">
                Date de naissance
                <span class="readonly-badge ms-2"><i class="bi bi-lock-fill"></i> Non modifiable</span>
              </label>
              <div class="field-readonly">
                <i class="bi bi-calendar3"></i>
                {{ \Carbon\Carbon::parse($eleve->date_naissance)->isoFormat('D MMMM YYYY') }}
              </div>
            </div>

            {{-- SEXE — lecture seule --}}
            <div class="col-md-6">
              <label class="form-label d-flex align-items-center">
                Sexe
                <span class="readonly-badge ms-2"><i class="bi bi-lock-fill"></i> Non modifiable</span>
              </label>
              <div class="field-readonly">
                <i class="bi {{ $eleve->sexe === 'M' ? 'bi-gender-male' : 'bi-gender-female' }}"></i>
                {{ $eleve->sexe === 'M' ? 'Masculin' : 'Féminin' }}
              </div>
            </div>

          </div>
        </div>
      </div>

      {{-- Ligne 2 : champs modifiables --}}
      <div class="row g-3">

        {{-- NIVEAU — modifiable (obligatoire) --}}
        <div class="col-md-6">
          <label class="form-label">
            Niveau souhaité <span class="required-star">*</span>
          </label>
          <select name="niveau_souhaite"
                  class="form-select @error('niveau_souhaite') is-invalid @enderror">
            <option value="">Sélectionner le niveau…</option>
            <optgroup label="Maternelle">
              @foreach(['Petite Section (PS)','Moyenne Section (MS)','Grande Section (GS)'] as $n)
                <option value="{{ $n }}"
                  {{ old('niveau_souhaite', $eleve->niveau_souhaite) === $n ? 'selected' : '' }}>
                  {{ $n }}
                </option>
              @endforeach
            </optgroup>
            <optgroup label="Primaire">
              @foreach(['CP1 — 1ère année','CP2 — 2ème année','CE1 — 3ème année','CE2 — 4ème année','CM1 — 5ème année','CM2 — 6ème année'] as $n)
                <option value="{{ $n }}"
                  {{ old('niveau_souhaite', $eleve->niveau_souhaite) === $n ? 'selected' : '' }}>
                  {{ $n }}
                </option>
              @endforeach
            </optgroup>
          </select>
          @error('niveau_souhaite')<div class="invalid-msg">{{ $message }}</div>@enderror
          <div class="help-text">
            Dernière classe : <strong>{{ $eleve->inscription?->classe?->nom ?? $eleve->niveau_souhaite ?? '—' }}</strong>
          </div>
        </div>

        {{-- GROUPE SANGUIN — modifiable --}}
        <div class="col-md-3">
          <label class="form-label">Groupe sanguin</label>
          <select name="groupe_sanguin" class="form-select">
            <option value="">—</option>
            @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $g)
              <option value="{{ $g }}"
                {{ old('groupe_sanguin', $eleve->groupe_sanguin) === $g ? 'selected' : '' }}>
                {{ $g }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- INFOS MÉDICALES — modifiable --}}
        <div class="col-12">
          <label class="form-label">Informations médicales</label>
          <textarea name="infos_medicales" class="form-control" rows="2"
                    placeholder="Allergies, traitements, besoins particuliers… (optionnel)">{{ old('infos_medicales', $eleve->infos_medicales) }}</textarea>
        </div>

      </div>
    </div>
  </div>

  {{-- ═══════════════════════════════════════
       SECTION 2 : INFOS PARENT (modifiables)
  ═══════════════════════════════════════ --}}
  <div class="form-section anim-up">
    <div class="form-section-head">
      <i class="bi bi-person-vcard"></i>
      <h5>Coordonnées du parent</h5>
      <span class="badge-modif"><i class="bi bi-pencil me-1"></i>Modifiable</span>
    </div>
    <div class="form-section-body">

      <div class="row g-3">

        {{-- NOM PARENT — lecture seule --}}
        <div class="col-md-6">
          <label class="form-label d-flex align-items-center">
            Nom complet
            <span class="readonly-badge ms-2"><i class="bi bi-lock-fill"></i> Non modifiable</span>
          </label>
          <div class="field-readonly">
            <i class="bi bi-person"></i>
            {{ $parent->civilite ?? '' }} {{ $parent->prenom }} {{ $parent->nom }}
          </div>
        </div>

        {{-- EMAIL — lecture seule --}}
        <div class="col-md-6">
          <label class="form-label d-flex align-items-center">
            Email
            <span class="readonly-badge ms-2"><i class="bi bi-lock-fill"></i> Non modifiable</span>
          </label>
          <div class="field-readonly">
            <i class="bi bi-envelope"></i>
            {{ Auth::user()->email }}
          </div>
        </div>

        {{-- TÉLÉPHONE — modifiable --}}
        <div class="col-md-6">
          <label class="form-label">Téléphone principal <span class="required-star">*</span></label>
          <input type="tel" name="parent_telephone"
                 class="form-control @error('parent_telephone') is-invalid @enderror"
                 value="{{ old('parent_telephone', $parent->telephone) }}"
                 placeholder="+229 97 00 00 00"/>
          @error('parent_telephone')<div class="invalid-msg">{{ $message }}</div>@enderror
        </div>

        {{-- TÉLÉPHONE 2 — modifiable --}}
        <div class="col-md-6">
          <label class="form-label">Téléphone secondaire</label>
          <input type="tel" name="parent_telephone2"
                 class="form-control"
                 value="{{ old('parent_telephone2', $parent->telephone_secondaire) }}"
                 placeholder="Optionnel"/>
        </div>

        {{-- ADRESSE — modifiable --}}
        <div class="col-12">
          <label class="form-label">Adresse <span class="required-star">*</span></label>
          <input type="text" name="parent_adresse"
                 class="form-control @error('parent_adresse') is-invalid @enderror"
                 value="{{ old('parent_adresse', $parent->adresse) }}"
                 placeholder="Quartier, rue, numéro"/>
          @error('parent_adresse')<div class="invalid-msg">{{ $message }}</div>@enderror
        </div>

        {{-- VILLE --}}
        <div class="col-md-5">
          <label class="form-label">Ville <span class="required-star">*</span></label>
          <input type="text" name="parent_ville"
                 class="form-control @error('parent_ville') is-invalid @enderror"
                 value="{{ old('parent_ville', $parent->ville) }}"
                 placeholder="Cotonou"/>
          @error('parent_ville')<div class="invalid-msg">{{ $message }}</div>@enderror
        </div>

        {{-- ARRONDISSEMENT --}}
        <div class="col-md-4">
          <label class="form-label">Arrondissement</label>
          <input type="text" name="parent_arrondissement"
                 class="form-control"
                 value="{{ old('parent_arrondissement', $parent->arrondissement) }}"
                 placeholder="ex: Akpakpa"/>
        </div>

      </div>
    </div>
  </div>

  {{-- ═══════════════════════════════════════
       SECTION 3 : DOCUMENTS (optionnels)
  ═══════════════════════════════════════ --}}
  <div class="form-section anim-up">
    <div class="form-section-head">
      <i class="bi bi-folder2-open"></i>
      <h5>Documents</h5>
      <span class="badge-lock">Tous optionnels en réinscription</span>
    </div>
    <div class="form-section-body">

      <div class="info-banner">
        <i class="bi bi-info-circle-fill"></i>
        <p>
          Les documents déjà fournis lors de l'inscription initiale sont conservés.
          Uploadez de nouveaux fichiers uniquement si vos documents ont <strong>expiré ou changé</strong>.
        </p>
      </div>

      <div class="row g-3">

        @php
          $docsConfig = [
            'doc_bulletin'          => ['Bulletin scolaire (dernière année)', 'bi-journal-text',         'label-bulletin'],
            'doc_acte_naissance'    => ["Acte de naissance",                  'bi-file-earmark-person',  'label-acte'],
            'doc_certificat_med'    => ['Certificat médical',                 'bi-heart-pulse',          'label-med'],
            'doc_piece_identite'    => ["Pièce d'identité parent",            'bi-credit-card-2-front',  'label-cni'],
            'doc_photo_identite'    => ["Photo d'identité élève",             'bi-image',                'label-photo'],
          ];
        @endphp

        @foreach($docsConfig as $name => [$label, $icon, $labelId])
          <div class="col-md-6">
            <label class="form-label">{{ $label }}</label>
            <label class="file-zone" id="zone-{{ $loop->index }}">
              <div class="fz-icon"><i class="bi {{ $icon }}"></i></div>
              <div class="flex-1">
                <p id="{{ $labelId }}">Glisser ou <span>parcourir</span></p>
                <span>PDF / image — optionnel</span>
              </div>
              <input type="file" name="{{ $name }}" class="d-none"
                     accept=".pdf,.jpg,.jpeg,.png"
                     onchange="afficherFichier(this, '{{ $labelId }}', 'zone-{{ $loop->index }}')"/>
            </label>
          </div>
        @endforeach

      </div>
    </div>
  </div>

  {{-- ═══════════════════════════════════════
       CONDITIONS + SUBMIT
  ═══════════════════════════════════════ --}}
  <div class="form-section anim-up">
    <div class="form-section-body">

      <div class="p-3 rounded-3 mb-3"
           style="background:#FFFBEB;border:1px solid rgba(245,158,11,.25)">
        <div class="form-check">
          <input class="form-check-input" type="checkbox"
                 name="conditions" id="conditionsCheck" value="1"
                 {{ old('conditions') ? 'checked' : '' }}/>
          <label class="form-check-label" for="conditionsCheck"
                 style="font-size:.85rem;color:#0D1B2A">
            Je certifie que les informations sont exactes et je confirme la réinscription de
            <strong>{{ $eleve->prenom }} {{ $eleve->nom }}</strong>
            pour l'année <strong>{{ $anneeCourante }}</strong>.
          </label>
        </div>
        @error('conditions')
          <div class="invalid-msg mt-1">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-actions">
        <a href="{{ route('parent.dashboard') }}" class="btn-outline-gu">
          <i class="bi bi-arrow-left"></i> Annuler
        </a>
        <button type="submit" class="btn-primary-gu" id="btnSubmit"
                style="background:linear-gradient(135deg,#059669,#047857);
                       box-shadow:0 5px 18px rgba(5,150,105,.35)">
          <i class="bi bi-send-check"></i> Confirmer la réinscription
        </button>
      </div>

    </div>
  </div>

</form>

@endsection

@push('scripts')
<script>
  // Prévisualisation photo
  function previewPhoto(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = e => {
        const preview = document.getElementById('photoPreview');
        preview.innerHTML = `<img src="${e.target.result}"
          style="width:100%;height:100%;object-fit:cover;border-radius:50%"/>`;
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  // Afficher nom fichier sélectionné
  function afficherFichier(input, labelId, zoneId) {
    if (input.files && input.files[0]) {
      document.getElementById(labelId).innerHTML =
        '<i class="bi bi-check-circle-fill text-success me-1"></i>' + input.files[0].name;
      document.getElementById(zoneId).classList.add('file-ok');
    }
  }

  // Validation avant soumission
  document.getElementById('reinscriptionForm').addEventListener('submit', function(e) {
    const niveau = document.querySelector('[name="niveau_souhaite"]');
    const cond   = document.getElementById('conditionsCheck');

    let ok = true;

    if (!niveau.value) {
      niveau.style.borderColor = '#EF4444';
      niveau.focus();
      ok = false;
    }
    if (!cond.checked) {
      cond.closest('.form-check').style.outline = '2px solid #EF4444';
      ok = false;
    }

    if (!ok) e.preventDefault();
  });
</script>
@endpush
