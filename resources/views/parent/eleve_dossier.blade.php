@extends('layouts.app')

@section('title', 'Dossier #' . $inscription->numero_dossier . ' — Gestion Scolaire')
@section('page_title', 'Dossier d\'inscription')
@section('page_subtitle', 'N° ' . $inscription->numero_dossier)

@push('styles')
    <style>
        .dossier-header {
            background: linear-gradient(115deg, #0D1B2A 0%, #1A3A5C 100%);
            border-radius: 14px;
            padding: 1.8rem 2rem;
            margin-bottom: 1.8rem;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .dossier-header::after {
            content: '';
            position: absolute;
            right: -40px;
            top: -40px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(56, 189, 248, .06);
            pointer-events: none;
        }

        .dossier-numero {
            font-family: 'Playfair Display', serif;
            color: #fff;
            font-size: 1.5rem;
            margin: 0 0 .3rem;
        }

        .dossier-meta {
            color: rgba(255, 255, 255, .55);
            font-size: .84rem;
            display: flex;
            flex-wrap: wrap;
            gap: .4rem 1.2rem;
        }

        .dossier-meta i {
            color: #38BDF8;
            margin-right: .3rem;
        }

        .eleve-photo-wrap {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            flex-shrink: 0;
            border: 4px solid rgba(255, 255, 255, .2);
            overflow: hidden;
            background: rgba(255, 255, 255, .1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #38BDF8;
        }

        .eleve-photo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .section-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 4px 20px rgba(13, 27, 42, .07);
            margin-bottom: 1.2rem;
            overflow: hidden;
        }

        .section-card-head {
            padding: .9rem 1.4rem;
            border-bottom: 1px solid #E2E8F0;
            display: flex;
            align-items: center;
            gap: .6rem;
            background: #FAFBFC;
        }

        .section-card-head h5 {
            font-family: 'Playfair Display', serif;
            font-size: .95rem;
            color: #0D1B2A;
            margin: 0;
        }

        .section-card-head i {
            color: #2563EB;
            font-size: 1rem;
        }

        .section-card-body {
            padding: 1.2rem 1.4rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .5rem 2rem;
        }

        .info-item {
            padding: .45rem 0;
            border-bottom: 1px solid #F1F5F9;
        }

        .info-item:last-child {
            border: none;
        }

        .info-item .lbl {
            color: #64748B;
            font-size: .75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: .18rem;
        }

        .info-item .val {
            color: #0D1B2A;
            font-size: .88rem;
            font-weight: 500;
        }

        .info-item .val.mono {
            font-family: monospace;
        }

        /* Timeline */
        .timeline {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .tl-step {
            flex: 1;
            text-align: center;
            position: relative;
            padding-top: 2.5rem;
        }

        .tl-step::before {
            content: '';
            position: absolute;
            top: 15px;
            left: calc(-50% + 15px);
            right: calc(50% + 15px);
            height: 3px;
            background: #E2E8F0;
        }

        .tl-step:first-child::before {
            display: none;
        }

        .tl-step.done::before {
            background: #10B981;
        }

        .tl-step.active::before {
            background: linear-gradient(90deg, #10B981, #2563EB);
        }

        .tl-dot {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            z-index: 1;
        }

        .tl-step.done .tl-dot {
            background: #10B981;
            color: #fff;
        }

        .tl-step.active .tl-dot {
            background: #2563EB;
            color: #fff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .15);
        }

        .tl-step.pending .tl-dot {
            background: #E2E8F0;
            color: #94A3B8;
        }

        .tl-label {
            font-size: .76rem;
            font-weight: 600;
            color: #94A3B8;
            margin-top: .2rem;
        }

        .tl-step.done .tl-label {
            color: #10B981;
        }

        .tl-step.active .tl-label {
            color: #2563EB;
        }

        .tl-date {
            font-size: .7rem;
            color: #CBD5E1;
        }

        /* Documents */
        .doc-item {
            display: flex;
            align-items: center;
            gap: .9rem;
            padding: .7rem 0;
            border-bottom: 1px solid #F1F5F9;
        }

        .doc-item:last-child {
            border: none;
        }

        .doc-ico {
            width: 38px;
            height: 38px;
            border-radius: 9px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
        }

        .ico-ok {
            background: rgba(16, 185, 129, .1);
            color: #10B981;
        }

        .ico-miss {
            background: rgba(100, 116, 139, .1);
            color: #94A3B8;
        }

        .doc-name {
            font-size: .85rem;
            font-weight: 600;
            color: #0D1B2A;
        }

        .doc-sub {
            font-size: .75rem;
            color: #94A3B8;
        }

        .badge-req {
            background: rgba(244, 63, 94, .08);
            color: #9F1239;
            border: 1px solid rgba(244, 63, 94, .2);
            border-radius: 20px;
            padding: .1rem .55rem;
            font-size: .7rem;
            font-weight: 700;
        }

        .badge-opt {
            background: #F1F5F9;
            color: #64748B;
            border: 1px solid #E2E8F0;
            border-radius: 20px;
            padding: .1rem .55rem;
            font-size: .7rem;
            font-weight: 700;
        }

        .actions-bar {
            display: flex;
            gap: .7rem;
            flex-wrap: wrap;
            padding: 1rem 1.4rem;
            background: #FAFBFC;
            border-top: 1px solid #E2E8F0;
        }

        .motif-refus {
            background: rgba(244, 63, 94, .05);
            border: 1px solid rgba(244, 63, 94, .2);
            border-left: 4px solid #F43F5E;
            border-radius: 10px;
            padding: 1rem 1.2rem;
            margin-bottom: 1rem;
        }

        .motif-refus strong {
            color: #9F1239;
            font-size: .88rem;
        }

        .motif-refus p {
            color: #7F1D1D;
            font-size: .85rem;
            margin: .3rem 0 0;
        }
    </style>
@endpush

@section('content')

    {{-- ── HEADER ── --}}
    <div class="dossier-header anim-up">
        <div class="eleve-photo-wrap">
            @if ($inscription->eleve->photo)
                <img src="{{ asset('storage/' . $inscription->eleve->photo) }}" alt="photo" />
            @else
                <i class="bi bi-person-fill"></i>
            @endif
        </div>
        <div class="flex-1">
            <div class="dossier-numero">{{ $inscription->eleve->prenom }} {{ $inscription->eleve->nom }}</div>
            <div class="dossier-meta">
                <span><i class="bi bi-folder2"></i>{{ $inscription->numero_dossier }}</span>
                <span><i class="bi bi-calendar3"></i>{{ $inscription->annee_scolaire }}</span>
                <span><i class="bi bi-layers"></i>{{ $inscription->eleve->niveau_souhaite }}</span>
                <span><i class="bi bi-clock"></i>Soumis le {{ $inscription->created_at->isoFormat('D MMM YYYY') }}</span>
            </div>
        </div>
        <div>
            @if ($inscription->statut === 'validee')
                <span class="badge-gu badge-validee px-3 py-2"><i class="bi bi-check2-circle me-1"></i>Validée</span>
            @elseif($inscription->statut === 'en_attente')
                <span class="badge-gu badge-attente px-3 py-2"><i class="bi bi-hourglass-split me-1"></i>En attente</span>
            @else
                <span class="badge-gu badge-refusee px-3 py-2"><i class="bi bi-x-circle me-1"></i>Refusée</span>
            @endif
        </div>
    </div>

    {{-- ── MOTIF REFUS ── --}}
    @if ($inscription->statut === 'refusee' && $inscription->motif_refus)
        <div class="motif-refus anim-up">
            <strong><i class="bi bi-exclamation-circle-fill me-1"></i>Motif du refus</strong>
            <p>{{ $inscription->motif_refus }}</p>
        </div>
    @endif

    {{-- ── TIMELINE ── --}}
    <div class="section-card anim-up">
        <div class="section-card-head">
            <i class="bi bi-signpost-split"></i>
            <h5>Suivi du dossier</h5>
        </div>
        <div class="section-card-body">
            <ul class="timeline">
                <li class="tl-step done">
                    <div class="tl-dot"><i class="bi bi-check-lg"></i></div>
                    <div class="tl-label">Soumis</div>
                    <div class="tl-date">{{ $inscription->created_at->isoFormat('D MMM') }}</div>
                </li>
                <li class="tl-step {{ in_array($inscription->statut, ['validee', 'refusee']) ? 'done' : 'active' }}">
                    <div class="tl-dot">
                        @if (in_array($inscription->statut, ['validee', 'refusee']))
                            <i class="bi bi-check-lg"></i>
                        @else
                            <i class="bi bi-hourglass-split"></i>
                        @endif
                    </div>
                    <div class="tl-label">En vérification</div>
                    <div class="tl-date">Secrétariat</div>
                </li>
                <li
                    class="tl-step {{ $inscription->statut === 'validee' ? 'done' : ($inscription->statut === 'refusee' ? 'done' : 'pending') }}">
                    <div class="tl-dot">
                        @if ($inscription->statut === 'validee')
                            <i class="bi bi-check-lg"></i>
                        @elseif($inscription->statut === 'refusee')
                            <i class="bi bi-x-lg"></i>
                        @else
                            <i class="bi bi-circle"></i>
                        @endif
                    </div>
                    <div class="tl-label">{{ $inscription->statut === 'refusee' ? 'Refusée' : 'Validation' }}</div>
                    <div class="tl-date">{{ $inscription->validee_le?->isoFormat('D MMM') ?? '—' }}</div>
                </li>
                <li
                    class="tl-step {{ $inscription->statut === 'validee' && $inscription->classe ? 'done' : ($inscription->statut === 'validee' ? 'active' : 'pending') }}">
                    <div class="tl-dot">
                        @if ($inscription->statut === 'validee' && $inscription->classe)
                            <i class="bi bi-check-lg"></i>
                        @else
                            <i class="bi bi-circle"></i>
                        @endif
                    </div>
                    <div class="tl-label">Classe affectée</div>
                    <div class="tl-date">{{ $inscription->classe?->nom ?? '—' }}</div>
                </li>
            </ul>
        </div>
    </div>

    {{-- ── CONTENU PRINCIPAL ── --}}
    <div class="row g-4">

        {{-- GAUCHE --}}
        <div class="col-lg-7">

            {{-- Infos élève --}}
            <div class="section-card anim-up">
                <div class="section-card-head">
                    <i class="bi bi-backpack2-fill"></i>
                    <h5>Informations de l'élève</h5>
                </div>
                <div class="section-card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="lbl">Nom complet</div>
                            <div class="val">{{ $inscription->eleve->prenom }} {{ $inscription->eleve->nom }}</div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Date de naissance</div>
                            <div class="val">
                                {{ \Carbon\Carbon::parse($inscription->eleve->date_naissance)->isoFormat('D MMMM YYYY') }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Lieu de naissance</div>
                            <div class="val">{{ $inscription->eleve->lieu_naissance ?? '—' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Sexe</div>
                            <div class="val">{{ $inscription->eleve->sexe === 'M' ? 'Masculin' : 'Féminin' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Nationalité</div>
                            <div class="val">{{ $inscription->eleve->nationalite ?? '—' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Groupe sanguin</div>
                            <div class="val">{{ $inscription->eleve->groupe_sanguin ?? '—' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Niveau souhaité</div>
                            <div class="val">{{ $inscription->eleve->niveau_souhaite }}</div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Classe affectée</div>
                            <div class="val">{{ $inscription->classe?->nom ?? 'Non attribuée' }}</div>
                        </div>
                        @if ($inscription->eleve->ancienne_ecole)
                            <div class="info-item">
                                <div class="lbl">Ancienne école</div>
                                <div class="val">{{ $inscription->eleve->ancienne_ecole }}</div>
                            </div>
                            <div class="info-item">
                                <div class="lbl">Dernière classe</div>
                                <div class="val">{{ $inscription->eleve->derniere_classe ?? '—' }}</div>
                            </div>
                        @endif
                        @if ($inscription->eleve->infos_medicales)
                            <div class="info-item" style="grid-column:1/-1">
                                <div class="lbl">Informations médicales</div>
                                <div class="val">{{ $inscription->eleve->infos_medicales }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Infos parent --}}
            <div class="section-card anim-up">
                <div class="section-card-head">
                    <i class="bi bi-person-vcard-fill"></i>
                    <h5>Parent / Tuteur</h5>
                </div>
                <div class="section-card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="lbl">Nom complet</div>
                            <div class="val">
                                {{ $inscription->eleve->parent->civilite ?? '' }}
                                {{ $inscription->eleve->parent->prenom }}
                                {{ $inscription->eleve->parent->nom }}
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Lien de parenté</div>
                            <div class="val">{{ $inscription->eleve->parent->lien_parente }}</div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Email</div>
                            <div class="val">{{ $inscription->eleve->parent->user->email }}</div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Téléphone</div>
                            <div class="val">{{ $inscription->eleve->parent->telephone }}</div>
                        </div>
                        <div class="info-item">
                            <div class="lbl">Adresse</div>
                            <div class="val">{{ $inscription->eleve->parent->adresse }},
                                {{ $inscription->eleve->parent->ville }}</div>
                        </div>
                        @if ($inscription->eleve->parent->telephone_secondaire)
                            <div class="info-item">
                                <div class="lbl">Tél. secondaire</div>
                                <div class="val">{{ $inscription->eleve->parent->telephone_secondaire }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- DROITE --}}
        <div class="col-lg-5">

            {{-- Récap dossier + actions --}}
            <div class="section-card anim-up">
                <div class="section-card-head">
                    <i class="bi bi-info-circle-fill"></i>
                    <h5>Récapitulatif</h5>
                </div>
                <div class="section-card-body">
                    <div class="info-item">
                        <div class="lbl">Numéro de dossier</div>
                        <div class="val mono">{{ $inscription->numero_dossier }}</div>
                    </div>
                    <div class="info-item">
                        <div class="lbl">Type</div>
                        <div class="val">
                            {{ $inscription->type === 'nouvelle' ? 'Nouvelle inscription' : 'Réinscription' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="lbl">Année scolaire</div>
                        <div class="val">{{ $inscription->annee_scolaire }}</div>
                    </div>
                    <div class="info-item">
                        <div class="lbl">Soumis le</div>
                        <div class="val">{{ $inscription->created_at->isoFormat('D MMMM YYYY [à] HH[h]mm') }}</div>
                    </div>
                    @if ($inscription->validee_le)
                        <div class="info-item">
                            <div class="lbl">Validé le</div>
                            <div class="val">{{ $inscription->validee_le->isoFormat('D MMMM YYYY') }}</div>
                        </div>
                    @endif
                </div>
                <div class="actions-bar">
                    @if ($inscription->statut === 'validee')

                        <a href="{{ route('parent.inscription.fiche', $inscription->id) }}" class="btn-outline-gu"
                            style="padding:.42rem .9rem;font-size:.8rem">
                            <i class="bi bi-download"></i> Fiche PDF
                        </a>

                        @if ($inscription->classe_id)
                            <a href="{{ route('parent.inscription.carte', $inscription->id) }}" class="btn-outline-gu"
                                style="padding:.42rem .9rem;font-size:.8rem">
                                <i class="bi bi-credit-card-2-front"></i> Carte
                            </a>
                        @endif
                    @else
                        <span style="font-size:.81rem;color:#64748B">
                            <i class="bi bi-info-circle me-1"></i>
                            Documents disponibles après validation.
                        </span>
                    @endif {{-- ← manquait --}}

                    <a href="{{ route('parent.dashboard') }}" class="btn-outline-gu ms-auto"
                        style="font-size:.83rem;padding:.48rem 1rem">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>

                </div> {{-- ← fermeture actions-bar --}}
            </div>

            {{-- Documents --}}
            <div class="section-card anim-up">
                <div class="section-card-head">
                    <i class="bi bi-folder2-open"></i>
                    <h5>Documents ({{ $inscription->documents->count() }})</h5>
                </div>
                <div class="section-card-body">
                    @php
                        $liste = [
                            'acte_naissance' => ['Acte de naissance', true, 'bi-file-earmark-person'],
                            'piece_identite_parent' => ["Pièce d'identité parent", true, 'bi-credit-card-2-front'],
                            'photo_identite' => ["Photo d'identité", true, 'bi-image'],
                            'bulletin' => ['Bulletin scolaire', false, 'bi-journal-text'],
                            'certificat_medical' => ['Certificat médical', false, 'bi-heart-pulse'],
                        ];
                        $parType = $inscription->documents->keyBy('type');
                    @endphp

                    @foreach ($liste as $type => [$label, $requis, $icon])
                        @php $doc = $parType->get($type); @endphp
                        <div class="doc-item">
                            <div class="doc-ico {{ $doc ? 'ico-ok' : 'ico-miss' }}">
                                <i class="bi {{ $doc ? 'bi-check-circle-fill' : $icon }}"></i>
                            </div>
                            <div class="flex-1">
                                <div class="doc-name">{{ $label }}</div>
                                <div class="doc-sub">
                                    {{ $doc ? $doc->nom_fichier . ' — ' . $doc->taille_formatee : 'Non fourni' }}
                                </div>
                            </div>
                            @if ($doc)
                                <a href="{{ $doc->url }}" target="_blank" class="btn-outline-gu"
                                    style="padding:.28rem .65rem;font-size:.75rem">
                                    <i class="bi bi-eye"></i>
                                </a>
                            @else
                                <span class="{{ $requis ? 'badge-req' : 'badge-opt' }}">
                                    {{ $requis ? 'Requis' : 'Optionnel' }}
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

@endsection
