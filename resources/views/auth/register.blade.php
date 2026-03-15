

@extends('layouts.auth')

@section('title', 'Inscription — EduGest')

@section('content')
<div class="container-fluid p-0">

  {{-- ══ SIDEBAR ÉTAPES ══ --}}
  <aside class="sidebar">
    <div class="sidebar-logo">
      <div class="logo-icon"><i class="bi bi-mortarboard-fill"></i></div>
      <div class="school-name">École Les Hirondelles</div>
      <div class="school-sub">EduGest — Gestion Scolaire</div>
    </div>

    <nav class="steps-nav">
      <p class="steps-title">Progression</p>

      <div class="step-item done" data-step="0">
        <div class="step-dot"><i class="bi bi-check-lg"></i></div>
        <div class="step-label">
          <strong>Démarrage</strong>
          <span>Formulaire ouvert</span>
        </div>
      </div>

      <div class="step-item active" data-step="1">
        <div class="step-dot">1</div>
        <div class="step-label">
          <strong>Infos Parent / Tuteur</strong>
          <span>Étape en cours</span>
        </div>
      </div>

      <div class="step-item pending" data-step="2">
        <div class="step-dot">2</div>
        <div class="step-label">
          <strong>Infos de l'Élève</strong>
          <span>Données scolaires</span>
        </div>
      </div>

      <div class="step-item pending" data-step="3">
        <div class="step-dot">3</div>
        <div class="step-label">
          <strong>Documents</strong>
          <span>Pièces justificatives</span>
        </div>
      </div>

      <div class="step-item pending" data-step="4">
        <div class="step-dot">4</div>
        <div class="step-label">
          <strong>Confirmation</strong>
          <span>Récapitulatif & envoi</span>
        </div>
      </div>
    </nav>

    <div class="sidebar-footer text-center">
      <p><i class="bi bi-shield-check me-1"></i>Vos données sont sécurisées et chiffrées.</p>
    </div>
  </aside>

  {{-- ══ MAIN ══ --}}
  <main class="main-content">

    <div class="page-header">
      <div class="year-badge">
        <i class="bi bi-calendar3"></i> Année scolaire {{ config('app.annee_scolaire', '2025-2026') }}
      </div>
      <h1>Formulaire d'Inscription</h1>
      <p>Complétez les 4 étapes pour inscrire votre enfant. Durée estimée : <strong>5 minutes</strong>.</p>
    </div>

    {{-- Barre de progression --}}
    <div class="progress-wrap">
      <div class="progress-bar-inner" id="progressBar" style="width:25%"></div>
    </div>

    {{-- ══ ERREURS SERVEUR ══ --}}
    @if($errors->any())
      <div class="alert-server" id="serverErrors">
        <i class="bi bi-exclamation-circle-fill"></i>
        <div>
          <strong>Veuillez corriger les erreurs suivantes :</strong>
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

    {{-- ══ FORMULAIRE UNIQUE ══
         enctype="multipart/form-data" obligatoire pour les fichiers
         action → route POST store()
    ══ --}}
    <form action="{{ route('parent.inscription.store') }}"
          method="POST"
          enctype="multipart/form-data"
          id="inscriptionForm"
          novalidate>

      @csrf

      <div class="form-card">

        {{-- ═══════════════════════════════════════
             ÉTAPE 1 : INFORMATIONS PARENT
        ═══════════════════════════════════════ --}}
        <div class="step-panel active" id="panel-1">
          <div class="card-header-custom">
            <div class="icon-wrap"><i class="bi bi-person-vcard"></i></div>
            <div>
              <h2>Informations du Parent / Tuteur</h2>
              <p>Coordonnées du responsable légal</p>
            </div>
            <span class="step-badge">Étape 1 / 4</span>
          </div>
          <div class="card-body-custom">

            <div class="info-banner">
              <i class="bi bi-info-circle-fill"></i>
              <p>
                Assurez-vous que l'<strong>adresse e-mail</strong> est correcte.
                Si vous n'avez pas encore de compte, il sera créé automatiquement et
                votre <strong>mot de passe</strong> vous sera communiqué.
              </p>
            </div>

            {{-- Civilité + Nom + Prénom --}}
            <div class="row g-3">
              <div class="col-md-3">
                <label class="form-label">Civilité</label>
                <select name="civilite" class="form-select @error('civilite') is-invalid @enderror">
                  <option value="">—</option>
                  <option value="M."  {{ old('civilite') === 'M.'  ? 'selected' : '' }}>M.</option>
                  <option value="Mme" {{ old('civilite') === 'Mme' ? 'selected' : '' }}>Mme</option>
                  <option value="Dr"  {{ old('civilite') === 'Dr'  ? 'selected' : '' }}>Dr</option>
                </select>
              </div>
              <div class="col-md-5">
                <label class="form-label">Nom <span class="required-star">*</span></label>
                <div class="input-icon-wrap">
                  <i class="bi bi-person field-icon"></i>
                  <input type="text"
                         name="parent_nom"
                         class="form-control @error('parent_nom') is-invalid @enderror"
                         value="{{ old('parent_nom') }}"
                         placeholder="ADOUNI"/>
                </div>
                @error('parent_nom')
                  <div class="invalid-msg">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-4">
                <label class="form-label">Prénom <span class="required-star">*</span></label>
                <input type="text"
                       name="parent_prenom"
                       class="form-control @error('parent_prenom') is-invalid @enderror"
                       value="{{ old('parent_prenom') }}"
                       placeholder="Kofi"/>
                @error('parent_prenom')
                  <div class="invalid-msg">{{ $message }}</div>
                @enderror
              </div>
            </div>

            {{-- Contact --}}
            <div class="section-divider">
              <div class="line"></div>
              <div class="label"><i class="bi bi-telephone"></i> Contact</div>
              <div class="line"></div>
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Adresse e-mail <span class="required-star">*</span></label>
                <div class="input-icon-wrap">
                  <i class="bi bi-envelope field-icon"></i>
                  <input type="email"
                         name="parent_email"
                         class="form-control @error('parent_email') is-invalid @enderror"
                         value="{{ old('parent_email') }}"
                         placeholder="kofi.adouni@email.com"/>
                </div>
                @error('parent_email')
                  <div class="invalid-msg">{{ $message }}</div>
                @enderror
                <div class="help-text">Votre mot de passe sera envoyé à cette adresse.</div>
              </div>
              <div class="col-md-6">
                <label class="form-label">Téléphone principal <span class="required-star">*</span></label>
                <div class="input-icon-wrap">
                  <i class="bi bi-phone field-icon"></i>
                  <input type="tel"
                         name="parent_telephone"
                         class="form-control @error('parent_telephone') is-invalid @enderror"
                         value="{{ old('parent_telephone') }}"
                         placeholder="+229 97 00 00 00"/>
                </div>
                @error('parent_telephone')
                  <div class="invalid-msg">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Téléphone secondaire</label>
                <div class="input-icon-wrap">
                  <i class="bi bi-phone field-icon"></i>
                  <input type="tel"
                         name="parent_telephone2"
                         class="form-control"
                         value="{{ old('parent_telephone2') }}"
                         placeholder="Optionnel"/>
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label">Lien de parenté <span class="required-star">*</span></label>
                <select name="lien_parente" class="form-select @error('lien_parente') is-invalid @enderror">
                  <option value="">Sélectionner…</option>
                  @foreach(['Père','Mère','Tuteur légal','Grand-parent','Autre'] as $lien)
                    <option value="{{ $lien }}" {{ old('lien_parente') === $lien ? 'selected' : '' }}>
                      {{ $lien }}
                    </option>
                  @endforeach
                </select>
                @error('lien_parente')
                  <div class="invalid-msg">{{ $message }}</div>
                @enderror
              </div>
            </div>

            {{-- Adresse --}}
            <div class="section-divider">
              <div class="line"></div>
              <div class="label"><i class="bi bi-geo-alt"></i> Adresse</div>
              <div class="line"></div>
            </div>

            <div class="row g-3">
              <div class="col-12">
                <label class="form-label">Adresse complète <span class="required-star">*</span></label>
                <div class="input-icon-wrap">
                  <i class="bi bi-house field-icon"></i>
                  <input type="text"
                         name="parent_adresse"
                         class="form-control @error('parent_adresse') is-invalid @enderror"
                         value="{{ old('parent_adresse') }}"
                         placeholder="Quartier, rue, numéro"/>
                </div>
                @error('parent_adresse')
                  <div class="invalid-msg">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-5">
                <label class="form-label">Ville <span class="required-star">*</span></label>
                <input type="text"
                       name="parent_ville"
                       class="form-control @error('parent_ville') is-invalid @enderror"
                       value="{{ old('parent_ville') }}"
                       placeholder="Cotonou"/>
                @error('parent_ville')
                  <div class="invalid-msg">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-4">
                <label class="form-label">Arrondissement</label>
                <input type="text"
                       name="parent_arrondissement"
                       class="form-control"
                       value="{{ old('parent_arrondissement') }}"
                       placeholder="ex: Akpakpa"/>
              </div>
              <div class="col-md-3">
                <label class="form-label">Code postal</label>
                <input type="text"
                       name="parent_code_postal"
                       class="form-control"
                       value="{{ old('parent_code_postal') }}"
                       placeholder="01 BP…"/>
              </div>
            </div>

            <div class="form-actions">
              <div class="text-muted" style="font-size:.82rem">
                <i class="bi bi-asterisk text-danger me-1" style="font-size:.7rem"></i>Champs obligatoires
              </div>
              <button type="button" class="btn-primary-custom" onclick="nextStep(1)">
                Continuer <i class="bi bi-arrow-right"></i>
              </button>
            </div>
          </div>
        </div>

        {{-- ═══════════════════════════════════════
             ÉTAPE 2 : INFORMATIONS ÉLÈVE
        ═══════════════════════════════════════ --}}
        <div class="step-panel" id="panel-2">
          <div class="card-header-custom">
            <div class="icon-wrap"><i class="bi bi-backpack2"></i></div>
            <div>
              <h2>Informations de l'Élève</h2>
              <p>Identité et données scolaires</p>
            </div>
            <span class="step-badge">Étape 2 / 4</span>
          </div>
          <div class="card-body-custom">

            <div class="row g-4 mb-2">
              {{-- Photo --}}
              <div class="col-md-3 text-center">
                <label class="form-label d-block text-center mb-2">Photo de l'élève</label>
                <div class="avatar-preview" id="avatarPreview">
                  <i class="bi bi-person"></i>
                </div>
                <label class="photo-upload" style="cursor:pointer">
                  <i class="bi bi-camera"></i>
                  <p><span>Choisir photo</span><br>
                  <small class="text-muted">JPG, PNG — max 2 Mo</small></p>
                  <input type="file"
                         name="eleve_photo"
                         accept="image/*"
                         class="d-none"
                         onchange="previewAvatar(this)"/>
                </label>
              </div>

              <div class="col-md-9">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label">Nom <span class="required-star">*</span></label>
                    <div class="input-icon-wrap">
                      <i class="bi bi-person field-icon"></i>
                      <input type="text"
                             name="eleve_nom"
                             class="form-control @error('eleve_nom') is-invalid @enderror"
                             value="{{ old('eleve_nom') }}"
                             placeholder="Nom de l'élève"/>
                    </div>
                    @error('eleve_nom')<div class="invalid-msg">{{ $message }}</div>@enderror
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Prénom(s) <span class="required-star">*</span></label>
                    <input type="text"
                           name="eleve_prenom"
                           class="form-control @error('eleve_prenom') is-invalid @enderror"
                           value="{{ old('eleve_prenom') }}"
                           placeholder="Prénom(s)"/>
                    @error('eleve_prenom')<div class="invalid-msg">{{ $message }}</div>@enderror
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Date de naissance <span class="required-star">*</span></label>
                    <div class="input-icon-wrap">
                      <i class="bi bi-calendar3 field-icon"></i>
                      <input type="date"
                             name="eleve_date_naissance"
                             class="form-control @error('eleve_date_naissance') is-invalid @enderror"
                             value="{{ old('eleve_date_naissance') }}"/>
                    </div>
                    @error('eleve_date_naissance')<div class="invalid-msg">{{ $message }}</div>@enderror
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Lieu de naissance <span class="required-star">*</span></label>
                    <input type="text"
                           name="eleve_lieu_naissance"
                           class="form-control @error('eleve_lieu_naissance') is-invalid @enderror"
                           value="{{ old('eleve_lieu_naissance') }}"
                           placeholder="Ville / Commune"/>
                    @error('eleve_lieu_naissance')<div class="invalid-msg">{{ $message }}</div>@enderror
                  </div>
                </div>
              </div>
            </div>

            {{-- Sexe + Nationalité --}}
            <div class="row g-3 mt-1">
              <div class="col-md-4">
                <label class="form-label">Sexe <span class="required-star">*</span></label>
                {{-- Champ hidden pour stocker la valeur --}}
                <input type="hidden" name="eleve_sexe" id="sexeInput" value="{{ old('eleve_sexe') }}"/>
                <div class="gender-group">
                  <div class="gender-btn {{ old('eleve_sexe') === 'M' ? 'selected' : '' }}"
                       onclick="selectGender(this,'M')">
                    <i class="bi bi-gender-male"></i>Masculin
                  </div>
                  <div class="gender-btn {{ old('eleve_sexe') === 'F' ? 'selected' : '' }}"
                       onclick="selectGender(this,'F')">
                    <i class="bi bi-gender-female"></i>Féminin
                  </div>
                </div>
                @error('eleve_sexe')<div class="invalid-msg">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-4">
                <label class="form-label">Nationalité</label>
                <input type="text"
                       name="eleve_nationalite"
                       class="form-control"
                       value="{{ old('eleve_nationalite', 'Béninoise') }}"
                       placeholder="Béninoise"/>
              </div>
              <div class="col-md-4">
                <label class="form-label">N° acte de naissance</label>
                <input type="text"
                       name="eleve_acte_naissance"
                       class="form-control"
                       value="{{ old('eleve_acte_naissance') }}"
                       placeholder="Optionnel"/>
              </div>
            </div>

            {{-- Scolarité --}}
            <div class="section-divider">
              <div class="line"></div>
              <div class="label"><i class="bi bi-journal-bookmark"></i> Scolarité</div>
              <div class="line"></div>
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Niveau souhaité <span class="required-star">*</span></label>
                <select name="niveau_souhaite"
                        class="form-select @error('niveau_souhaite') is-invalid @enderror">
                  <option value="">Sélectionner le niveau…</option>
                  <optgroup label="Maternelle">
                    @foreach(['Petite Section (PS)','Moyenne Section (MS)','Grande Section (GS)'] as $n)
                      <option value="{{ $n }}" {{ old('niveau_souhaite') === $n ? 'selected' : '' }}>{{ $n }}</option>
                    @endforeach
                  </optgroup>
                  <optgroup label="Primaire">
                    @foreach(['CP1 — 1ère année','CP2 — 2ème année','CE1 — 3ème année','CE2 — 4ème année','CM1 — 5ème année','CM2 — 6ème année'] as $n)
                      <option value="{{ $n }}" {{ old('niveau_souhaite') === $n ? 'selected' : '' }}>{{ $n }}</option>
                    @endforeach
                  </optgroup>
                </select>
                @error('niveau_souhaite')<div class="invalid-msg">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Ancienne école</label>
                <input type="text" name="ancienne_ecole" class="form-control"
                       value="{{ old('ancienne_ecole') }}"
                       placeholder="Établissement précédent"/>
              </div>
              <div class="col-md-4">
                <label class="form-label">Dernière classe</label>
                <input type="text" name="derniere_classe" class="form-control"
                       value="{{ old('derniere_classe') }}" placeholder="ex: CP1"/>
              </div>
              <div class="col-md-4">
                <label class="form-label">Résultat précédent</label>
                <select name="resultat_precedent" class="form-select">
                  <option value="">—</option>
                  @foreach(["Admis(e)","Ajourné(e)","1ère scolarisation"] as $r)
                    <option value="{{ $r }}" {{ old('resultat_precedent') === $r ? 'selected' : '' }}>{{ $r }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label">Groupe sanguin</label>
                <select name="groupe_sanguin" class="form-select">
                  <option value="">—</option>
                  @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $g)
                    <option value="{{ $g }}" {{ old('groupe_sanguin') === $g ? 'selected' : '' }}>{{ $g }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12">
                <label class="form-label">Informations médicales importantes</label>
                <textarea name="infos_medicales" class="form-control" rows="2"
                          placeholder="Allergies, traitements, besoins particuliers… (optionnel)">{{ old('infos_medicales') }}</textarea>
              </div>
            </div>

            <div class="form-actions">
              <button type="button" class="btn-outline-custom" onclick="prevStep(2)">
                <i class="bi bi-arrow-left"></i> Retour
              </button>
              <button type="button" class="btn-primary-custom" onclick="nextStep(2)">
                Continuer <i class="bi bi-arrow-right"></i>
              </button>
            </div>
          </div>
        </div>

        {{-- ═══════════════════════════════════════
             ÉTAPE 3 : DOCUMENTS
        ═══════════════════════════════════════ --}}
        <div class="step-panel" id="panel-3">
          <div class="card-header-custom">
            <div class="icon-wrap"><i class="bi bi-folder2-open"></i></div>
            <div>
              <h2>Pièces Justificatives</h2>
              <p>Documents requis pour la validation du dossier</p>
            </div>
            <span class="step-badge">Étape 3 / 4</span>
          </div>
          <div class="card-body-custom">

            <div class="info-banner">
              <i class="bi bi-info-circle-fill"></i>
              <p>Formats acceptés : <strong>PDF, JPG, PNG</strong>.
                 Taille max par fichier : <strong>5 Mo</strong>.
                 Les documents seront vérifiés par le secrétariat.</p>
            </div>

            <div class="row g-3">

              {{-- Acte de naissance (obligatoire) --}}
              <div class="col-12">
                <label class="form-label">
                  Extrait d'acte de naissance <span class="required-star">*</span>
                </label>
                <label class="file-upload-zone @error('doc_acte_naissance') border-danger @enderror"
                       id="zone-acte">
                  <div class="file-icon"><i class="bi bi-file-earmark-person"></i></div>
                  <div class="flex-1">
                    <p id="label-acte">Glisser-déposer ou <span style="color:var(--blue);font-weight:600">parcourir</span></p>
                    <span>PDF ou image — requis</span>
                  </div>
                  <input type="file" name="doc_acte_naissance" class="d-none"
                         accept=".pdf,.jpg,.jpeg,.png"
                         onchange="afficherFichier(this,'label-acte')"/>
                </label>
                @error('doc_acte_naissance')<div class="invalid-msg">{{ $message }}</div>@enderror
              </div>

              {{-- Bulletin --}}
              <div class="col-md-6">
                <label class="form-label">Bulletin scolaire (dernière année)</label>
                <label class="file-upload-zone" id="zone-bulletin">
                  <div class="file-icon"><i class="bi bi-journal-text"></i></div>
                  <div class="flex-1">
                    <p id="label-bulletin">Bulletins de notes</p>
                    <span>Optionnel — PDF / image</span>
                  </div>
                  <input type="file" name="doc_bulletin" class="d-none"
                         accept=".pdf,.jpg,.jpeg,.png"
                         onchange="afficherFichier(this,'label-bulletin')"/>
                </label>
              </div>

              {{-- Certificat médical --}}
              <div class="col-md-6">
                <label class="form-label">Certificat médical / Carnet de santé</label>
                <label class="file-upload-zone" id="zone-medical">
                  <div class="file-icon"><i class="bi bi-heart-pulse"></i></div>
                  <div class="flex-1">
                    <p id="label-medical">Santé de l'élève</p>
                    <span>Optionnel — PDF / image</span>
                  </div>
                  <input type="file" name="doc_certificat_med" class="d-none"
                         accept=".pdf,.jpg,.jpeg,.png"
                         onchange="afficherFichier(this,'label-medical')"/>
                </label>
              </div>

              {{-- Pièce d'identité parent (obligatoire) --}}
              <div class="col-md-6">
                <label class="form-label">
                  Pièce d'identité parent/tuteur <span class="required-star">*</span>
                </label>
                <label class="file-upload-zone @error('doc_piece_identite') border-danger @enderror"
                       id="zone-cni">
                  <div class="file-icon"><i class="bi bi-credit-card-2-front"></i></div>
                  <div class="flex-1">
                    <p id="label-cni">CNI / Passeport / Titre de séjour</p>
                    <span>Requis — PDF ou image</span>
                  </div>
                  <input type="file" name="doc_piece_identite" class="d-none"
                         accept=".pdf,.jpg,.jpeg,.png"
                         onchange="afficherFichier(this,'label-cni')"/>
                </label>
                @error('doc_piece_identite')<div class="invalid-msg">{{ $message }}</div>@enderror
              </div>

              {{-- Photo d'identité (obligatoire) --}}
              <div class="col-md-6">
                <label class="form-label">
                  Photo d'identité de l'élève <span class="required-star">*</span>
                </label>
                <label class="file-upload-zone @error('doc_photo_identite') border-danger @enderror"
                       id="zone-photo">
                  <div class="file-icon"><i class="bi bi-image"></i></div>
                  <div class="flex-1">
                    <p id="label-photo">Photo récente format passeport</p>
                    <span>JPG / PNG — requis</span>
                  </div>
                  <input type="file" name="doc_photo_identite" class="d-none"
                         accept=".jpg,.jpeg,.png"
                         onchange="afficherFichier(this,'label-photo')"/>
                </label>
                @error('doc_photo_identite')<div class="invalid-msg">{{ $message }}</div>@enderror
              </div>

            </div>

            <div class="form-actions">
              <button type="button" class="btn-outline-custom" onclick="prevStep(3)">
                <i class="bi bi-arrow-left"></i> Retour
              </button>
              <button type="button" class="btn-primary-custom" onclick="nextStep(3)">
                Continuer <i class="bi bi-arrow-right"></i>
              </button>
            </div>
          </div>
        </div>

        {{-- ═══════════════════════════════════════
             ÉTAPE 4 : CONFIRMATION
        ═══════════════════════════════════════ --}}
        <div class="step-panel" id="panel-4">
          <div class="card-header-custom" style="background:linear-gradient(110deg,#064E3B,#065F46)">
            <div class="icon-wrap" style="color:#34D399"><i class="bi bi-check2-circle"></i></div>
            <div>
              <h2>Récapitulatif & Confirmation</h2>
              <p>Vérifiez vos informations avant l'envoi</p>
            </div>
            <span class="step-badge" style="border-color:rgba(52,211,153,.3);color:#34D399">Étape 4 / 4</span>
          </div>
          <div class="card-body-custom">

            <div class="info-banner" style="border-left-color:#10B981;background:rgba(16,185,129,.06);border-color:rgba(16,185,129,.15)">
              <i class="bi bi-check-circle-fill" style="color:#10B981"></i>
              <p>Votre dossier est <strong>complet</strong>. Cliquez sur <strong>Soumettre</strong>
                 pour l'envoyer au secrétariat. Un e-mail de confirmation vous sera envoyé.</p>
            </div>

            {{-- Récap Parent --}}
            <div class="section-divider">
              <div class="line"></div>
              <div class="label"><i class="bi bi-person-vcard"></i> Parent / Tuteur</div>
              <div class="line"></div>
            </div>
            <div class="row g-2 mb-3">
              <div class="col-md-6">
                <div class="p-3 rounded-3" style="background:var(--light)">
                  <div class="info-row">
                    <span class="label-k">Nom complet</span>
                    <span class="label-v" id="recap-nom-parent">—</span>
                  </div>
                  <div class="info-row">
                    <span class="label-k">E-mail</span>
                    <span class="label-v" id="recap-email">—</span>
                  </div>
                  <div class="info-row mb-0">
                    <span class="label-k">Téléphone</span>
                    <span class="label-v" id="recap-tel">—</span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="p-3 rounded-3" style="background:var(--light)">
                  <div class="info-row">
                    <span class="label-k">Adresse</span>
                    <span class="label-v" id="recap-adresse">—</span>
                  </div>
                  <div class="info-row mb-0">
                    <span class="label-k">Lien de parenté</span>
                    <span class="label-v" id="recap-lien">—</span>
                  </div>
                </div>
              </div>
            </div>

            {{-- Récap Élève --}}
            <div class="section-divider">
              <div class="line"></div>
              <div class="label"><i class="bi bi-backpack2"></i> Élève</div>
              <div class="line"></div>
            </div>
            <div class="row g-2 mb-3">
              <div class="col-md-6">
                <div class="p-3 rounded-3" style="background:var(--light)">
                  <div class="info-row">
                    <span class="label-k">Nom complet</span>
                    <span class="label-v" id="recap-nom-eleve">—</span>
                  </div>
                  <div class="info-row">
                    <span class="label-k">Date de naissance</span>
                    <span class="label-v" id="recap-ddn">—</span>
                  </div>
                  <div class="info-row mb-0">
                    <span class="label-k">Sexe</span>
                    <span class="label-v" id="recap-sexe">—</span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="p-3 rounded-3" style="background:var(--light)">
                  <div class="info-row">
                    <span class="label-k">Niveau demandé</span>
                    <span class="label-v" id="recap-niveau">—</span>
                  </div>
                  <div class="info-row mb-0">
                    <span class="label-k">Ancienne école</span>
                    <span class="label-v" id="recap-ecole">—</span>
                  </div>
                </div>
              </div>
            </div>

            {{-- Récap Documents --}}
            <div class="section-divider">
              <div class="line"></div>
              <div class="label"><i class="bi bi-folder2-open"></i> Documents</div>
              <div class="line"></div>
            </div>
            <ul class="doc-checklist" id="recapDocs">
              {{-- Rempli par JS --}}
            </ul>

            {{-- Conditions --}}
            <div class="mt-3 p-3 rounded-3"
                 style="background:#FFFBEB;border:1px solid rgba(245,158,11,.25)">
              <div class="form-check">
                <input class="form-check-input" type="checkbox"
                       name="conditions" id="termsCheck" value="1"
                       {{ old('conditions') ? 'checked' : '' }}/>
                <label class="form-check-label" for="termsCheck"
                       style="font-size:.85rem;color:var(--navy)">
                  Je certifie que les informations fournies sont exactes et j'accepte le
                  <a href="#" style="color:var(--blue)">règlement intérieur de l'établissement</a>
                  et la <a href="#" style="color:var(--blue)">politique de confidentialité</a>.
                </label>
              </div>
              @error('conditions')
                <div class="invalid-msg mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-actions">
              <button type="button" class="btn-outline-custom" onclick="prevStep(4)">
                <i class="bi bi-arrow-left"></i> Retour
              </button>
              {{-- SUBMIT FINAL — envoie vraiment le formulaire --}}
              <button type="submit"
                      class="btn-primary-custom"
                      id="btnSubmit"
                      style="background:linear-gradient(135deg,#059669,#047857);box-shadow:0 6px 20px rgba(5,150,105,.35)">
                <i class="bi bi-send-check"></i> Soumettre le dossier
              </button>
            </div>

          </div>
        </div>

      </div>{{-- /form-card --}}
    </form>{{-- /form --}}

  </main>
</div>

{{-- ══ STYLES ══ --}}
<style>
  :root {
    --navy:#0D1B2A;--royal:#1A3A5C;--blue:#2563EB;--sky:#38BDF8;
    --gold:#F59E0B;--cream:#F8F5EF;--white:#FFFFFF;--slate:#64748B;
    --light:#EFF6FF;--border:#CBD5E1;--success:#10B981;--danger:#EF4444;
    --radius:14px;--shadow:0 20px 60px rgba(13,27,42,.12);
    --shadow-sm:0 4px 16px rgba(13,27,42,.08);
  }
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
  body{font-family:'DM Sans',sans-serif;background:var(--cream);min-height:100vh;overflow-x:hidden;}
  body::before{content:'';position:fixed;inset:0;background:radial-gradient(ellipse 80% 50% at 10% 20%,rgba(37,99,235,.08) 0%,transparent 60%),radial-gradient(ellipse 60% 40% at 90% 80%,rgba(56,189,248,.07) 0%,transparent 60%);pointer-events:none;z-index:0;}
  .sidebar{position:fixed;top:0;left:0;bottom:0;width:300px;background:linear-gradient(160deg,var(--navy) 0%,var(--royal) 100%);z-index:100;display:flex;flex-direction:column;overflow:hidden;}
  .sidebar::after{content:'';position:absolute;bottom:-80px;right:-80px;width:240px;height:240px;border-radius:50%;background:rgba(56,189,248,.06);pointer-events:none;}
  .sidebar-logo{padding:2.2rem 2rem 1.6rem;border-bottom:1px solid rgba(255,255,255,.08);}
  .school-name{font-family:'Playfair Display',serif;color:#fff;font-size:1.35rem;line-height:1.3;margin-top:.55rem;}
  .school-sub{color:var(--sky);font-size:.75rem;font-weight:500;letter-spacing:.08em;text-transform:uppercase;margin-top:.2rem;}
  .logo-icon{width:48px;height:48px;background:linear-gradient(135deg,var(--blue),var(--sky));border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#fff;box-shadow:0 6px 20px rgba(37,99,235,.4);}
  .steps-nav{padding:2rem;flex:1;}
  .steps-title{color:rgba(255,255,255,.45);font-size:.7rem;font-weight:600;letter-spacing:.12em;text-transform:uppercase;margin-bottom:1.4rem;}
  .step-item{display:flex;align-items:flex-start;gap:1rem;padding:.9rem 0;position:relative;cursor:pointer;transition:all .2s;}
  .step-item:not(:last-child)::after{content:'';position:absolute;left:19px;top:54px;width:2px;height:calc(100% - 14px);background:rgba(255,255,255,.1);}
  .step-item.active:not(:last-child)::after{background:rgba(37,99,235,.5);}
  .step-dot{width:38px;height:38px;flex-shrink:0;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.85rem;font-weight:700;transition:all .3s;}
  .step-item.done .step-dot{background:var(--success);color:#fff;box-shadow:0 4px 14px rgba(16,185,129,.4);}
  .step-item.active .step-dot{background:linear-gradient(135deg,var(--blue),var(--sky));color:#fff;box-shadow:0 4px 18px rgba(37,99,235,.5);}
  .step-item.pending .step-dot{background:rgba(255,255,255,.07);color:rgba(255,255,255,.3);border:2px solid rgba(255,255,255,.1);}
  .step-label{padding-top:.4rem;}
  .step-label strong{display:block;color:#fff;font-size:.88rem;font-weight:600;}
  .step-item.pending .step-label strong{color:rgba(255,255,255,.4);}
  .step-label span{color:rgba(255,255,255,.4);font-size:.77rem;}
  .step-item.active .step-label span{color:var(--sky);}
  .sidebar-footer{padding:1.5rem 2rem;border-top:1px solid rgba(255,255,255,.08);}
  .sidebar-footer p{color:rgba(255,255,255,.35);font-size:.76rem;}
  .main-content{margin-left:300px;min-height:100vh;padding:2.5rem 3rem;position:relative;z-index:1;}
  .page-header{margin-bottom:2.2rem;}
  .year-badge{display:inline-flex;align-items:center;gap:.5rem;background:rgba(37,99,235,.1);color:var(--blue);border:1px solid rgba(37,99,235,.2);border-radius:30px;padding:.3rem .9rem;font-size:.78rem;font-weight:600;letter-spacing:.04em;margin-bottom:.9rem;}
  .page-header h1{font-family:'Playfair Display',serif;font-size:2rem;color:var(--navy);line-height:1.2;}
  .page-header p{color:var(--slate);font-size:.92rem;margin-top:.4rem;}
  .progress-wrap{background:rgba(255,255,255,.8);border-radius:30px;padding:.25rem;margin-bottom:2rem;box-shadow:var(--shadow-sm);backdrop-filter:blur(8px);}
  .progress-bar-inner{height:8px;border-radius:30px;background:linear-gradient(90deg,var(--blue) 0%,var(--sky) 100%);transition:width .6s cubic-bezier(.4,0,.2,1);position:relative;}
  .progress-bar-inner::after{content:'';position:absolute;right:-1px;top:50%;transform:translateY(-50%);width:14px;height:14px;border-radius:50%;background:var(--sky);box-shadow:0 0 0 3px rgba(56,189,248,.3);}
  .form-card{background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow);overflow:hidden;}
  .card-header-custom{background:linear-gradient(110deg,var(--navy) 0%,var(--royal) 100%);padding:1.6rem 2rem;display:flex;align-items:center;gap:1rem;}
  .card-header-custom .icon-wrap{width:44px;height:44px;background:rgba(255,255,255,.12);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.25rem;color:var(--sky);}
  .card-header-custom h2{font-family:'Playfair Display',serif;color:#fff;font-size:1.15rem;margin:0;}
  .card-header-custom p{color:rgba(255,255,255,.55);font-size:.8rem;margin:.1rem 0 0;}
  .step-badge{margin-left:auto;background:rgba(255,255,255,.12);color:var(--sky);font-size:.78rem;font-weight:700;padding:.35rem .8rem;border-radius:20px;border:1px solid rgba(56,189,248,.3);}
  .card-body-custom{padding:2rem 2.2rem;}
  .section-divider{display:flex;align-items:center;gap:1rem;margin:2rem 0 1.5rem;}
  .section-divider .line{flex:1;height:1px;background:var(--border);}
  .section-divider .label{display:flex;align-items:center;gap:.5rem;color:var(--navy);font-size:.8rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;white-space:nowrap;}
  .section-divider .label i{color:var(--blue);font-size:1rem;}
  .form-label{font-size:.82rem;font-weight:600;color:var(--navy);margin-bottom:.45rem;letter-spacing:.02em;}
  .required-star{color:var(--danger);margin-left:3px;}
  .form-control,.form-select{border:1.5px solid var(--border);border-radius:10px;padding:.65rem 1rem;font-size:.9rem;font-family:'DM Sans',sans-serif;color:var(--navy);background:var(--white);transition:all .2s;box-shadow:none;}
  .form-control:focus,.form-select:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(37,99,235,.12);outline:none;}
  .form-control::placeholder{color:#A0AEC0;}
  .form-control.is-invalid,.form-select.is-invalid{border-color:var(--danger);}
  .input-icon-wrap{position:relative;}
  .input-icon-wrap .form-control{padding-left:2.6rem;}
  .input-icon-wrap .field-icon{position:absolute;left:.9rem;top:50%;transform:translateY(-50%);color:var(--slate);font-size:1rem;pointer-events:none;}
  .invalid-msg{color:var(--danger);font-size:.78rem;margin-top:.3rem;}
  .help-text{color:var(--slate);font-size:.78rem;margin-top:.3rem;}
  .alert-server{display:flex;align-items:flex-start;gap:.8rem;background:rgba(244,63,94,.08);border:1px solid rgba(244,63,94,.25);border-left:4px solid var(--danger);border-radius:10px;padding:1rem 1.2rem;margin-bottom:1.5rem;font-size:.86rem;color:#7F1D1D;}
  .alert-server i{font-size:1.1rem;flex-shrink:0;margin-top:.1rem;color:var(--danger);}
  .photo-upload{border:2px dashed var(--border);border-radius:12px;padding:1.8rem 1rem;text-align:center;cursor:pointer;transition:all .25s;background:var(--light);}
  .photo-upload:hover{border-color:var(--blue);background:rgba(37,99,235,.04);}
  .photo-upload i{font-size:2rem;color:var(--blue);display:block;margin-bottom:.5rem;}
  .photo-upload p{color:var(--slate);font-size:.84rem;margin:0;}
  .photo-upload span{color:var(--blue);font-weight:600;}
  .file-upload-zone{border:2px dashed var(--border);border-radius:12px;padding:1.4rem;cursor:pointer;transition:all .25s;display:flex;align-items:center;gap:1rem;}
  .file-upload-zone:hover{border-color:var(--blue);background:rgba(37,99,235,.03);}
  .file-upload-zone.file-ok{border-color:var(--success);background:rgba(16,185,129,.04);}
  .file-upload-zone .file-icon{width:48px;height:48px;flex-shrink:0;background:var(--light);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:var(--blue);}
  .file-upload-zone p{margin:0;color:var(--navy);font-size:.88rem;font-weight:500;}
  .file-upload-zone span{color:var(--slate);font-size:.8rem;}
  .gender-group{display:flex;gap:.8rem;}
  .gender-btn{flex:1;border:1.5px solid var(--border);border-radius:10px;padding:.6rem .5rem;text-align:center;cursor:pointer;transition:all .2s;font-size:.85rem;color:var(--slate);font-weight:500;user-select:none;}
  .gender-btn:hover{border-color:var(--blue);color:var(--blue);background:var(--light);}
  .gender-btn.selected{border-color:var(--blue);color:var(--blue);background:rgba(37,99,235,.07);font-weight:700;}
  .gender-btn i{display:block;font-size:1.3rem;margin-bottom:.3rem;}
  .info-banner{background:linear-gradient(135deg,rgba(37,99,235,.07),rgba(56,189,248,.07));border:1px solid rgba(37,99,235,.15);border-left:4px solid var(--blue);border-radius:10px;padding:1rem 1.2rem;display:flex;gap:.9rem;align-items:flex-start;margin-bottom:1.8rem;}
  .info-banner i{color:var(--blue);font-size:1.1rem;flex-shrink:0;margin-top:.1rem;}
  .info-banner p{margin:0;color:var(--navy);font-size:.84rem;line-height:1.6;}
  .info-banner strong{color:var(--blue);}
  .btn-primary-custom{background:linear-gradient(135deg,var(--blue),#1D4ED8);color:#fff;border:none;border-radius:10px;padding:.75rem 2rem;font-family:'DM Sans',sans-serif;font-size:.92rem;font-weight:700;box-shadow:0 6px 20px rgba(37,99,235,.35);transition:all .25s;display:inline-flex;align-items:center;gap:.5rem;}
  .btn-primary-custom:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(37,99,235,.45);color:#fff;}
  .btn-outline-custom{background:transparent;color:var(--navy);border:1.5px solid var(--border);border-radius:10px;padding:.75rem 2rem;font-family:'DM Sans',sans-serif;font-size:.92rem;font-weight:600;transition:all .25s;display:inline-flex;align-items:center;gap:.5rem;}
  .btn-outline-custom:hover{border-color:var(--blue);color:var(--blue);background:var(--light);}
  .form-actions{display:flex;justify-content:space-between;align-items:center;padding-top:1.8rem;border-top:1px solid var(--border);margin-top:2rem;}
  .doc-checklist{list-style:none;padding:0;}
  .doc-checklist li{display:flex;align-items:center;gap:.7rem;padding:.5rem 0;border-bottom:1px solid var(--border);font-size:.84rem;color:var(--navy);}
  .doc-checklist li:last-child{border:none;}
  .done-icon{color:var(--success);}
  .pending-icon{color:var(--border);}
  .info-row{display:flex;justify-content:space-between;margin-bottom:.6rem;}
  .info-row .label-k{color:var(--slate);font-size:.8rem;}
  .info-row .label-v{color:var(--navy);font-size:.82rem;font-weight:600;}
  .avatar-preview{width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--light),#DBEAFE);border:3px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:2rem;color:var(--slate);overflow:hidden;margin:0 auto 1rem;}
  .step-panel{display:none;}
  .step-panel.active{display:block;animation:fadeUp .35s ease both;}
  @keyframes fadeUp{from{opacity:0;transform:translateY(20px);}to{opacity:1;transform:translateY(0);}}
  @media(max-width:991px){.sidebar{width:240px;}.main-content{margin-left:240px;padding:1.5rem;}}
  @media(max-width:767px){.sidebar{display:none;}.main-content{margin-left:0;padding:1.2rem;}}
</style>

{{-- ══ SCRIPTS ══ --}}
<script>
  let currentStep = 1;
  const totalSteps = 4;
  const progressWidths = ['25%','50%','75%','100%'];

  // ── Navigation entre étapes ───────────────────────────────
  function nextStep(from) {
    if (!validerEtape(from)) return;
    if (from < totalSteps) {
      if (from === 3) remplirRecapitulatif(); // juste avant l'étape 4
      showStep(from + 1);
    }
  }

  function prevStep(from) {
    if (from > 1) showStep(from - 1);
  }

  function showStep(step) {
    document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('panel-' + step).classList.add('active');

    document.querySelectorAll('.step-item[data-step]').forEach(item => {
      const s = parseInt(item.dataset.step);
      item.classList.remove('active','done','pending');
      if (s === 0 || s < step)  item.classList.add('done');
      else if (s === step)       item.classList.add('active');
      else                       item.classList.add('pending');
    });

    document.getElementById('progressBar').style.width = progressWidths[step - 1];
    currentStep = step;
    window.scrollTo({top: 0, behavior: 'smooth'});
  }

  // ── Validation JS par étape ───────────────────────────────
  function validerEtape(step) {
    let ok = true;
    // Retirer anciens messages d'erreur JS
    document.querySelectorAll('.js-error').forEach(e => e.remove());

    if (step === 1) {
      ok = checkRequired('parent_nom',   'Le nom est obligatoire') && ok;
      ok = checkRequired('parent_prenom','Le prénom est obligatoire') && ok;
      ok = checkEmail  ('parent_email',  "L'e-mail est invalide") && ok;
      ok = checkRequired('parent_telephone','Le téléphone est obligatoire') && ok;
      ok = checkSelect ('lien_parente',  'Le lien de parenté est obligatoire') && ok;
      ok = checkRequired('parent_adresse','L\'adresse est obligatoire') && ok;
      ok = checkRequired('parent_ville', 'La ville est obligatoire') && ok;
    }

    if (step === 2) {
      ok = checkRequired('eleve_nom',  'Le nom de l\'élève est obligatoire') && ok;
      ok = checkRequired('eleve_prenom','Le prénom de l\'élève est obligatoire') && ok;
      ok = checkRequired('eleve_date_naissance','La date de naissance est obligatoire') && ok;
      ok = checkRequired('eleve_lieu_naissance','Le lieu de naissance est obligatoire') && ok;
      ok = checkSelect ('niveau_souhaite','Le niveau souhaité est obligatoire') && ok;
      // Sexe
      if (!document.getElementById('sexeInput').value) {
        showJsError('sexeInput','Le sexe est obligatoire');
        ok = false;
      }
    }

    if (step === 3) {
      ok = checkFile('doc_acte_naissance', "L'acte de naissance est obligatoire") && ok;
      ok = checkFile('doc_piece_identite', "La pièce d'identité est obligatoire") && ok;
      ok = checkFile('doc_photo_identite', "La photo d'identité est obligatoire") && ok;
    }

    return ok;
  }

  function checkRequired(name, msg) {
    const el = document.querySelector(`[name="${name}"]`);
    if (!el || !el.value.trim()) { showJsError(name, msg); return false; }
    return true;
  }

  function checkEmail(name, msg) {
    const el = document.querySelector(`[name="${name}"]`);
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!el || !re.test(el.value)) { showJsError(name, msg); return false; }
    return true;
  }

  function checkSelect(name, msg) {
    const el = document.querySelector(`[name="${name}"]`);
    if (!el || !el.value) { showJsError(name, msg); return false; }
    return true;
  }

  function checkFile(name, msg) {
    const el = document.querySelector(`[name="${name}"]`);
    if (!el || el.files.length === 0) { showJsError(name, msg); return false; }
    return true;
  }

  function showJsError(name, msg) {
    const el = document.querySelector(`[name="${name}"]`);
    if (!el) return;
    const div = document.createElement('div');
    div.className = 'invalid-msg js-error';
    div.textContent = msg;
    el.closest('.col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-9, .col-12, .col-md-4')
      ?.appendChild(div)
      || el.parentElement.appendChild(div);
    el.style.borderColor = 'var(--danger)';
  }

  // ── Sexe ─────────────────────────────────────────────────
  function selectGender(el, val) {
    document.querySelectorAll('.gender-btn').forEach(b => b.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('sexeInput').value = val;
  }

  // ── Prévisualisation photo ────────────────────────────────
  function previewAvatar(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = e => {
        const prev = document.getElementById('avatarPreview');
        prev.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;border-radius:50%"/>`;
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  // ── Nom du fichier sélectionné ────────────────────────────
  function afficherFichier(input, labelId) {
    const label = document.getElementById(labelId);
    if (input.files && input.files[0]) {
      label.textContent = '✓ ' + input.files[0].name;
      label.style.color = 'var(--success)';
      input.closest('.file-upload-zone').classList.add('file-ok');
    }
  }

  // ── Récapitulatif étape 4 ─────────────────────────────────
  function remplirRecapitulatif() {
    const g = name => document.querySelector(`[name="${name}"]`)?.value || '—';

    // Parent
    document.getElementById('recap-nom-parent').textContent =
      (g('civilite') + ' ' + g('parent_prenom') + ' ' + g('parent_nom')).trim();
    document.getElementById('recap-email').textContent   = g('parent_email');
    document.getElementById('recap-tel').textContent     = g('parent_telephone');
    document.getElementById('recap-adresse').textContent =
      g('parent_adresse') + ', ' + g('parent_ville');
    document.getElementById('recap-lien').textContent    = g('lien_parente');

    // Élève
    document.getElementById('recap-nom-eleve').textContent =
      g('eleve_prenom') + ' ' + g('eleve_nom').toUpperCase();
    document.getElementById('recap-ddn').textContent    = g('eleve_date_naissance');
    document.getElementById('recap-sexe').textContent   =
      g('eleve_sexe') === 'M' ? 'Masculin' : g('eleve_sexe') === 'F' ? 'Féminin' : '—';
    document.getElementById('recap-niveau').textContent = g('niveau_souhaite');
    document.getElementById('recap-ecole').textContent  = g('ancienne_ecole') || '—';

    // Documents
    const docs = [
      {name:'doc_acte_naissance', label:"Extrait d'acte de naissance", required:true},
      {name:'doc_bulletin',       label:"Bulletin scolaire",            required:false},
      {name:'doc_certificat_med', label:"Certificat médical",           required:false},
      {name:'doc_piece_identite', label:"Pièce d'identité parent",     required:true},
      {name:'doc_photo_identite', label:"Photo d'identité élève",      required:true},
    ];
    const ul = document.getElementById('recapDocs');
    ul.innerHTML = '';
    docs.forEach(d => {
      const el   = document.querySelector(`[name="${d.name}"]`);
      const ok   = el && el.files.length > 0;
      const li   = document.createElement('li');
      li.innerHTML = ok
        ? `<i class="bi bi-check-circle-fill done-icon"></i> ${d.label}`
        : `<i class="bi bi-dash-circle pending-icon"></i> ${d.label}
           <span class="text-muted ms-1" style="font-size:.78rem">
             ${d.required ? '(manquant — requis)' : '(non fourni — optionnel)'}
           </span>`;
      ul.appendChild(li);
    });
  }

  // ── Si erreurs Laravel → revenir à étape 1 ───────────────
  @if($errors->any())
    document.addEventListener('DOMContentLoaded', () => showStep(1));
  @endif
</script>

@endsection
