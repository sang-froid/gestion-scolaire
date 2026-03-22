@extends('layouts.app')

@section('title', 'Mon Espace — EduGest')
@section('page_title', 'Tableau de bord')

@push('styles')
    <style>
        * {
            font-family: 'Montserrat', sans-serif !important;
        }

        .welcome-banner {
            background: linear-gradient(115deg, #0D1B2A 0%, #1A3A5C 55%, #1e4d7e 100%);
            border-radius: 14px;
            padding: 1.8rem 2.2rem;
            margin-bottom: 1.8rem;
            position: relative;
            overflow: hidden;
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            right: -40px;
            top: -40px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(56, 189, 248, .07);
            pointer-events: none;
        }

        .welcome-banner h2 {
            color: #fff;
            font-size: 1.45rem;
            margin: 0 0 .3rem;
        }

        .welcome-banner p {
            color: rgba(255, 255, 255, .6);
            font-size: .88rem;
            margin: 0;
        }

        .welcome-date {
            position: absolute;
            right: 2.2rem;
            top: 50%;
            transform: translateY(-50%);
            text-align: right;
        }

        .welcome-date .day {

            font-size: 2.8rem;
            color: #fff;
            line-height: 1;
        }

        .welcome-date .mois {
            color: #38BDF8;
            font-size: .8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .stat-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 4px 24px rgba(13, 27, 42, .09);
            padding: 1.3rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform .2s, box-shadow .2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 28px rgba(13, 27, 42, .12);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }

        .stat-val {

            font-size: 1.85rem;
            color: #0D1B2A;
            line-height: 1;
        }

        .stat-label {
            color: #64748B;
            font-size: .8rem;
            margin-top: .2rem;
        }

        .eleve-card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 4px 24px rgba(13, 27, 42, .09);
            overflow: hidden;
            margin-bottom: 1rem;
            transition: transform .2s;
        }

        .eleve-card:hover {
            transform: translateY(-2px);
        }

        .eleve-head {
            background: linear-gradient(110deg, #0D1B2A, #1A3A5C);
            padding: 1.1rem 1.4rem;
            display: flex;
            align-items: center;
            gap: .9rem;
        }

        .eleve-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            flex-shrink: 0;
            border: 3px solid rgba(255, 255, 255, .2);
            background: rgba(255, 255, 255, .1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #38BDF8;
            overflow: hidden;
        }

        .eleve-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .eleve-nom {
            color: #fff;
            font-size: .97rem;
            margin: 0;
        }

        .eleve-classe {
            color: #38BDF8;
            font-size: .77rem;
        }

        .eleve-body {
            padding: 1rem 1.4rem;
        }

        .eleve-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .4rem 0;
            border-bottom: 1px solid #E2E8F0;
            font-size: .83rem;
        }

        .eleve-row:last-child {
            border: none;
        }

        .eleve-row .k {
            color: #64748B;
        }

        .eleve-row .v {
            color: #0D1B2A;
            font-weight: 600;
        }

        .eleve-foot {
            padding: .8rem 1.4rem;
            background: #FAFBFC;
            border-top: 1px solid #E2E8F0;
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
        }

        .echeance-card {
            background: linear-gradient(115deg, #7C3AED, #5B21B6);
            border-radius: 14px;
            padding: 1.4rem 1.6rem;
            color: #fff;
            position: relative;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .echeance-card::after {
            content: '';
            position: absolute;
            right: -30px;
            bottom: -30px;
            width: 130px;
            height: 130px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
            pointer-events: none;
        }

        .echeance-card h6 {
            color: rgba(255, 255, 255, .6);
            font-size: .72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: .4rem;
        }

        .echeance-amount {

            font-size: 1.65rem;
            margin: .2rem 0;
        }

        .echeance-sublabel {
            color: rgba(255, 255, 255, .65);
            font-size: .82rem;
        }

        .echeance-date {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: rgba(255, 255, 255, .15);
            border-radius: 20px;
            padding: .28rem .75rem;
            font-size: .77rem;
            margin-top: .65rem;
        }

        .action-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .75rem;
            margin-bottom: 1.5rem;
        }

        .action-btn {
            background: #fff;
            border: 1.5px solid #E2E8F0;
            border-radius: 11px;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .5rem;
            text-decoration: none;
            transition: all .2s;
            text-align: center;
        }

        .action-btn:hover {
            border-color: #2563EB;
            background: #EFF6FF;
            transform: translateY(-2px);
        }

        .action-btn i {
            font-size: 1.45rem;
            background: linear-gradient(135deg, #2563EB, #38BDF8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .action-btn span {
            color: #0D1B2A;
            font-size: .81rem;
            font-weight: 600;
        }

        .action-btn.disabled {
            opacity: .4;
            pointer-events: none;
        }

        .notif-list {
            border-radius: 14px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 4px 24px rgba(13, 27, 42, .09);
            overflow: hidden;
            background: #fff;
        }

        .notif-item {
            display: flex;
            align-items: flex-start;
            gap: .9rem;
            padding: .95rem 1.3rem;
            border-bottom: 1px solid #E2E8F0;
            transition: background .15s;
            cursor: pointer;
        }

        .notif-item:last-child {
            border: none;
        }

        .notif-item:hover {
            background: #FAFBFD;
        }

        .notif-item.unread {
            background: #EFF6FF;
        }

        .notif-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
        }

        .ni-pay {
            background: rgba(245, 158, 11, .12);
            color: #F59E0B;
        }

        .ni-info {
            background: rgba(37, 99, 235, .1);
            color: #2563EB;
        }

        .ni-alert {
            background: rgba(244, 63, 94, .1);
            color: #F43F5E;
        }

        .notif-title {
            font-size: .87rem;
            font-weight: 600;
            color: #0D1B2A;
            margin: 0 0 .18rem;
        }

        .notif-body {
            font-size: .8rem;
            color: #64748B;
            margin: 0 0 .25rem;
        }

        .notif-time {
            font-size: .74rem;
            color: #94A3B8;
        }

        .unread-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #2563EB;
            flex-shrink: 0;
            margin-top: .4rem;
        }

        @media(max-width:767px) {
            .welcome-date {
                display: none;
            }
        }
    </style>
@endpush

@section('content')

    {{-- ── BANNIÈRE MOT DE PASSE GÉNÉRÉ (première connexion) ── --}}
    @if (session('mdp_info'))
        <div class="alert alert-dismissible mb-4 d-flex align-items-start gap-3"
            style="background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);
              border-left:4px solid #F59E0B;border-radius:12px;padding:1.1rem 1.4rem">
            <i class="bi bi-key-fill fs-5" style="color:#F59E0B;flex-shrink:0;margin-top:.1rem"></i>
            <div>
                <strong style="color:#92400E">Votre compte a été créé automatiquement</strong><br>
                <span style="font-size:.87rem;color:#78350F">
                    Conservez ces identifiants : <strong>{{ session('mdp_info') }}</strong>
                </span>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── ALERTE SUCCÈS ── --}}
    {{-- @if (session('success'))
        <div class="alert alert-dismissible mb-3 d-flex align-items-center gap-2"
            style="background:rgba(16,185,129,.1);border-left:4px solid #10B981;
              border-radius:10px;padding:.85rem 1.1rem;color:#065F46;border:1px solid rgba(16,185,129,.2)">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif --}}

    {{-- ── WELCOME BANNER ── --}}
    <div class="welcome-banner anim-up">
        <h2>Bonjour, {{ $parent->prenom ?? 'Parent' }} !</h2>
        <p>Suivez les inscriptions de vos enfants et restez informé en temps réel.</p>
        <a href="{{ route('parent.inscription.create') }}" class="btn-primary-gu mt-3">
            <i class="bi bi-plus-circle"></i> Inscrire un enfant
        </a>
        <div class="welcome-date">
            <div class="day">{{ now()->format('d') }}</div>
            <div class="mois">{{ now()->isoFormat('MMMM YYYY') }}</div>
        </div>
    </div>

    {{-- ── STAT CARDS ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card anim-up">
                <div class="stat-icon" style="background:rgba(37,99,235,.1)">
                    <i class="bi bi-people-fill" style="color:#2563EB"></i>
                </div>
                <div>
                    <div class="stat-val">{{ $eleves->count() }}</div>
                    <div class="stat-label">Enfant(s)</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card anim-up delay-1">
                <div class="stat-icon" style="background:rgba(245,158,11,.1)">
                    <i class="bi bi-hourglass-split" style="color:#F59E0B"></i>
                </div>
                <div>
                    <div class="stat-val">
                        {{ $eleves->filter(fn($e) => $e->inscription?->statut === 'en_attente')->count() }}
                    </div>
                    <div class="stat-label">En attente</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card anim-up delay-2">
                <div class="stat-icon" style="background:rgba(16,185,129,.1)">
                    <i class="bi bi-check-circle-fill" style="color:#10B981"></i>
                </div>
                <div>
                    <div class="stat-val">
                        {{ $eleves->filter(fn($e) => $e->inscription?->statut === 'validee')->count() }}
                    </div>
                    <div class="stat-label">Validé(s)</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card anim-up delay-3">
                <div class="stat-icon" style="background:rgba(244,63,94,.1)">
                    <i class="bi bi-bell-fill" style="color:#F43F5E"></i>
                </div>
                <div>
                    <div class="stat-val">{{ $notifications->whereNull('lu_le')->count() }}</div>
                    <div class="stat-label">Notification(s)</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── LIGNE PRINCIPALE ── --}}
    <div class="row g-4">

    <div class="col-12">
        <div class="section-head">
            <h5><i class="bi bi-backpack2-fill"></i> Mes inscrits</h5>
        </div>

        {{-- GRID DES CARTES --}}
        <div class="row g-4">

            @forelse($eleves as $eleve)
                @php 
                    $statut = $eleve->inscription?->statut ?? 'en_attente'; 
                @endphp

                {{-- ✅ 3 PAR LIGNE --}}
                <div class="col-md-6 col-lg-4">
                    <div class="eleve-card anim-up h-100 d-flex flex-column">

                        {{-- HEADER --}}
                        <div class="eleve-head">
                            <div class="eleve-avatar">
                                @if ($eleve->photo)
                                    <img src="{{ asset('storage/' . $eleve->photo) }}" alt="Photo" />
                                @else
                                    <i class="bi bi-person-fill"></i>
                                @endif
                            </div>

                            <div class="flex-1">
                                <div class="eleve-nom">
                                    {{ $eleve->prenom }} {{ $eleve->nom }}
                                </div>
                                <div class="eleve-classe">
                                    {{ $eleve->inscription?->classe?->nom ?? 'Classe non attribuée' }}
                                    — {{ $eleve->inscription?->annee_scolaire ?? config('app.annee_scolaire', '2025-2026') }}
                                </div>
                            </div>

                            <div class="ms-auto">
                                @if ($statut === 'validee')
                                    <span class="badge-gu badge-validee">
                                        <i class="bi bi-check2-circle"></i> Validée
                                    </span>
                                @elseif($statut === 'en_attente')
                                    <span class="badge-gu badge-attente">
                                        <i class="bi bi-hourglass-split"></i> En attente
                                    </span>
                                @else
                                    <span class="badge-gu badge-refusee">
                                        <i class="bi bi-x-circle"></i> Refusée
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- BODY --}}
                        <div class="eleve-body flex-grow-1">

                            <div class="eleve-row">
                                <span class="k">
                                    <i class="bi bi-calendar3 me-1"></i>Date de naissance
                                </span>
                                <span class="v">
                                    {{ \Carbon\Carbon::parse($eleve->date_naissance)->isoFormat('D MMMM YYYY') }}
                                </span>
                            </div>

                            <div class="eleve-row">
                                <span class="k">
                                    <i class="bi bi-layers me-1"></i>Niveau
                                </span>
                                <span class="v">
                                    {{ $eleve->inscription?->classe?->niveau ?? ($eleve->niveau_souhaite ?? '—') }}
                                </span>
                            </div>

                            <div class="eleve-row">
                                <span class="k">
                                    <i class="bi bi-folder me-1"></i>N° dossier
                                </span>
                                <span class="v">
                                    {{ $eleve->inscription?->numero_dossier ?? '—' }}
                                </span>
                            </div>

                            <div class="eleve-row">
                                <span class="k">
                                    <i class="bi bi-clock-history me-1"></i>Soumis le
                                </span>
                                <span class="v">
                                    {{ $eleve->inscription?->created_at?->isoFormat('D MMM YYYY') ?? '—' }}
                                </span>
                            </div>

                        </div>

                        {{-- FOOTER --}}
                        <div class="eleve-foot mt-auto">

                            <a href="{{ route('parent.inscription.show', $eleve->inscription->id ?? 0) }}"
                               class="btn-primary-gu"
                               style="padding:.42rem .9rem;font-size:.8rem">
                                <i class="bi bi-eye"></i> Voir le dossier
                            </a>

                            @if ($statut === 'validee')
                                <a href="{{ route('parent.inscription.show', $eleve->inscription->id) }}?dl=fiche"
                                   class="btn-outline-gu"
                                   style="padding:.42rem .9rem;font-size:.8rem">
                                    <i class="bi bi-download"></i> Fiche PDF
                                </a>

                                <a href="{{ route('parent.inscription.show', $eleve->inscription->id) }}?dl=carte"
                                   class="btn-outline-gu"
                                   style="padding:.42rem .9rem;font-size:.8rem">
                                    <i class="bi bi-credit-card-2-front"></i> Carte
                                </a>

                            @elseif($statut === 'en_attente')
                                <span class="btn-outline-gu"
                                      style="padding:.42rem .9rem;font-size:.8rem;
                                             border-color:#F59E0B;color:#B45309;cursor:default">
                                    <i class="bi bi-hourglass-split"></i> En attente
                                </span>
                            @else
                                <span class="btn-outline-gu"
                                      style="padding:.42rem .9rem;font-size:.8rem;
                                             border-color:#F43F5E;color:#9F1239;cursor:default">
                                    <i class="bi bi-x-circle"></i> Dossier refusé
                                </span>
                            @endif

                        </div>

                    </div>
                </div>

            @empty
                <div class="col-12">
                    <div class="card-gu">
                        <div class="empty-state text-center">
                            <i class="bi bi-backpack2"></i>
                            <p>Aucun enfant inscrit pour le moment.</p>

                            <a href="{{ route('parent.inscription.create') }}" class="btn-primary-gu mt-3">
                                <i class="bi bi-plus-circle"></i> Inscrire mon enfant
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse

        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.notif-item.unread').forEach(item => {
            item.addEventListener('click', function() {
                const id = this.dataset.id;
                this.classList.remove('unread');
                this.querySelector('.unread-dot')?.remove();
                fetch(`/parent/notifications/${id}/lire`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                });
            });
        });
    </script>
@endpush
