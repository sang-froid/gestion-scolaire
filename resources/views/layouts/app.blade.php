{{--
|==========================================================================
| resources/views/layouts/app.blade.php
|==========================================================================
| Rôle : Structure HTML globale. Assemble sidebar + topbar + contenu.
| Chaque vue enfant fait @extends('layouts.app') et remplit @yield('content')
|==========================================================================
--}}
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title', 'EduGest — Gestion Scolaire')</title>

    {{-- ── Fonts ── --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap"
        rel="stylesheet" />

    {{-- ── Bootstrap 5 + Icons ── --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])


    <style>
        /* ── Variables globales ── */
        :root {
            --navy: #0D1B2A;
            --royal: #1A3A5C;
            --blue: #2563EB;
            --sky: #38BDF8;
            --gold: #F59E0B;
            --mint: #10B981;
            --rose: #F43F5E;
            --white: #FFFFFF;
            --slate: #64748B;
            --border: #E2E8F0;
            --light: #EFF6FF;
            --bg: #F1F5F9;
            --radius: 14px;
            --shadow: 0 4px 24px rgba(13, 27, 42, .09);
            --sidebar-w: 268px;
        }

        /* ── Reset de base ── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: var(--bg);
            color: var(--navy);
            min-height: 100vh;
        }

        /* ───────────────────────────────────────────
       SIDEBAR  (styles partagés — détails dans _sidebar.blade.php)
    ─────────────────────────────────────────── */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-w);
            background: linear-gradient(170deg, var(--navy) 0%, var(--royal) 100%);
            display: flex;
            flex-direction: column;
            z-index: 200;
            overflow: hidden;
            transition: transform .3s ease;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            bottom: -60px;
            right: -60px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(56, 189, 248, .05);
            pointer-events: none;
        }

        /* Logo */
        .sidebar-logo {
            padding: 1.8rem 1.6rem 1.4rem;
            border-bottom: 1px solid rgba(255, 255, 255, .07);
            flex-shrink: 0;
        }

        .logo-mark {
            width: 44px;
            height: 44px;
            border-radius: 11px;
            background: linear-gradient(135deg, var(--blue), var(--sky));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #fff;
            box-shadow: 0 5px 18px rgba(37, 99, 235, .4);
            margin-bottom: .7rem;
        }

        .school-name {
            color: #fff;
            font-size: 1.05rem;
            line-height: 1.3;
        }

        .school-role {
            color: var(--sky);
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-top: .18rem;
        }

        /* Navigation */
        .sidebar-nav {
            flex: 1;
            padding: 1.2rem 1rem;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, .1);
            border-radius: 4px;
        }

        .nav-section-label {
            color: rgba(255, 255, 255, .3);
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            padding: .3rem .6rem;
            margin: .9rem 0 .4rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .65rem .9rem;
            border-radius: 9px;
            color: rgba(255, 255, 255, .6);
            font-size: .88rem;
            font-weight: 500;
            text-decoration: none;
            transition: all .2s;
            margin-bottom: 2px;
            position: relative;
            border-left: 3px solid transparent;
        }

        .nav-item i {
            font-size: 1.05rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, .07);
            color: #fff;
        }

        .nav-item.active {
            background: linear-gradient(90deg, rgba(37, 99, 235, .35), rgba(37, 99, 235, .1));
            color: #fff;
            border-left-color: var(--sky);
        }

        * {
            font-family: 'Montserrat', sans-serif !important;
        }

        .nav-item.active i {
            color: var(--sky);
        }

        .nav-item-child {
            padding-left: 2.7rem;
            font-size: .84rem;
        }

        .nav-badge {
            margin-left: auto;
            background: var(--rose);
            color: #fff;
            font-size: .68rem;
            font-weight: 700;
            border-radius: 20px;
            padding: .12rem .5rem;
            min-width: 20px;
            text-align: center;
        }

        /* Footer sidebar */
        .sidebar-footer {
            padding: 1rem 1.2rem;
            border-top: 1px solid rgba(255, 255, 255, .07);
            flex-shrink: 0;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: .7rem;
            background: rgba(255, 255, 255, .06);
            border-radius: 10px;
            padding: .65rem .9rem;
        }

        .user-avatar-sm {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--blue), var(--sky));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .82rem;
            font-weight: 700;
            color: #fff;
        }

        .user-pill strong {
            display: block;
            color: #fff;
            font-size: .84rem;
            line-height: 1.3;
        }

        .user-pill span {
            color: rgba(255, 255, 255, .4);
            font-size: .74rem;
        }

        /* ───────────────────────────────────────────
       TOPBAR  (styles partagés)
    ─────────────────────────────────────────── */
        .topbar {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: .9rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 8px rgba(13, 27, 42, .05);
            flex-shrink: 0;
        }

        .topbar-title {
            font-size: 1.05rem;
            color: var(--navy);
            line-height: 1.2;
        }

        .topbar-sub {
            color: var(--slate);
            font-size: .78rem;
            margin-top: .1rem;
        }

        .topbar-icon {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            background: var(--light);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--slate);
            text-decoration: none;
            font-size: .95rem;
            transition: all .2s;
            position: relative;
        }

        .topbar-icon:hover {
            background: var(--blue);
            color: #fff;
            border-color: var(--blue);
        }

        .notif-dot {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--rose);
            border: 2px solid #fff;
        }

        .btn-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--blue), var(--sky));
            color: #fff;
            font-weight: 700;
            font-size: .85rem;
            border: none;
            cursor: pointer;
            flex-shrink: 0;
        }

        /* ───────────────────────────────────────────
       MAIN CONTENT
    ─────────────────────────────────────────── */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Zone de contenu de page */
        .page-body {
            padding: 2rem;
            flex: 1;
        }

        /* ───────────────────────────────────────────
       ALERTES FLASH  (session success / error)
    ─────────────────────────────────────────── */
        .alert-flash {
            display: flex;
            align-items: center;
            gap: .65rem;
            border-radius: 10px;
            padding: .85rem 1.1rem;
            font-size: .88rem;
            font-weight: 500;
            border: none;
            margin-bottom: 1.4rem;
            animation: fadeUp .35s ease both;
        }

        .alert-flash-success {
            background: rgba(16, 185, 129, .1);
            color: #065F46;
            border-left: 4px solid var(--mint);
        }

        .alert-flash-error {
            background: rgba(244, 63, 94, .1);
            color: #9F1239;
            border-left: 4px solid var(--rose);
        }

        .alert-flash-warning {
            background: rgba(245, 158, 11, .1);
            color: #92400E;
            border-left: 4px solid var(--gold);
        }

        /* ───────────────────────────────────────────
       COMPOSANTS RÉUTILISABLES (toutes les pages)
    ─────────────────────────────────────────── */

        /* Boutons */
        .btn-primary-gu {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            background: linear-gradient(135deg, var(--blue), #1D4ED8);
            color: #fff;
            border: none;
            border-radius: 9px;
            padding: .6rem 1.3rem;
            font-size: .88rem;
            font-weight: 700;
            box-shadow: 0 4px 16px rgba(37, 99, 235, .3);
            text-decoration: none;
            transition: all .25s;
            cursor: pointer;
        }

        .btn-primary-gu:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 22px rgba(37, 99, 235, .4);
            color: #fff;
        }

        .btn-outline-gu {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            border: 1.5px solid var(--border);
            border-radius: 9px;
            padding: .6rem 1.3rem;
            font-size: .88rem;
            font-weight: 600;
            color: var(--navy);
            background: transparent;
            text-decoration: none;
            transition: all .2s;
            cursor: pointer;
        }

        .btn-outline-gu:hover {
            border-color: var(--blue);
            color: var(--blue);
            background: var(--light);
        }

        .btn-danger-gu {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            background: rgba(244, 63, 94, .1);
            border: 1.5px solid rgba(244, 63, 94, .25);
            color: #9F1239;
            border-radius: 9px;
            padding: .6rem 1.3rem;
            font-size: .88rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s;
            cursor: pointer;
        }

        .btn-danger-gu:hover {
            background: var(--rose);
            color: #fff;
            border-color: var(--rose);
        }

        /* Badges statut */
        .badge-gu {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            border-radius: 20px;
            padding: .22rem .75rem;
            font-size: .75rem;
            font-weight: 700;
        }

        .badge-attente {
            background: rgba(245, 158, 11, .1);
            color: #B45309;
            border: 1px solid rgba(245, 158, 11, .25);
        }

        .badge-validee {
            background: rgba(16, 185, 129, .1);
            color: #065F46;
            border: 1px solid rgba(16, 185, 129, .25);
        }

        .badge-refusee {
            background: rgba(244, 63, 94, .1);
            color: #9F1239;
            border: 1px solid rgba(244, 63, 94, .25);
        }

        .badge-info {
            background: rgba(37, 99, 235, .1);
            color: #1E40AF;
            border: 1px solid rgba(37, 99, 235, .2);
        }

        /* Cards génériques */
        .card-gu {
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .card-gu-header {
            padding: 1rem 1.4rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-gu-header h5 {
            font-size: .98rem;
            color: var(--navy);
            margin: 0;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .card-gu-header h5 i {
            color: var(--blue);
        }

        .card-gu-body {
            padding: 1.2rem 1.4rem;
        }

        /* Section head (titre + lien) */
        .section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .section-head h5 {
            font-size: 1rem;
            color: var(--navy);
            margin: 0;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .section-head h5 i {
            color: var(--blue);
        }

        .link-sm {
            color: var(--blue);
            font-size: .82rem;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        .link-sm:hover {
            color: #1D4ED8;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 2.5rem 1rem;
            color: var(--slate);
        }

        .empty-state i {
            font-size: 2.4rem;
            opacity: .3;
            display: block;
            margin-bottom: .8rem;
        }

        .empty-state p {
            font-size: .88rem;
            margin: 0;
        }

        /* Animations globales */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .anim-up {
            animation: fadeUp .4s ease both;
        }

        .anim-up.delay-1 {
            animation-delay: .08s;
        }

        .anim-up.delay-2 {
            animation-delay: .16s;
        }

        .anim-up.delay-3 {
            animation-delay: .24s;
        }

        .anim-up.delay-4 {
            animation-delay: .32s;
        }

        /* ── Responsive ── */
        @media (max-width: 991px) {
            :root {
                --sidebar-w: 230px;
            }

            .page-body {
                padding: 1.4rem;
            }
        }

        @media (max-width: 767px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .page-body {
                padding: 1rem;
            }

            .topbar {
                padding: .8rem 1rem;
            }
        }
    </style>

    {{-- CSS spécifique à chaque vue (injecté via @push('styles')) --}}
    @stack('styles')
</head>

<body>

    {{-- ══ SIDEBAR — unique, conditionnel admin/parent ══ --}}
    @include('layouts.sidebar')

    {{-- ══ CONTENU PRINCIPAL ══ --}}
    <div class="main-content">

        {{-- ── TOPBAR — commune à toutes les pages ── --}}
        @include('layouts.topbar')

        {{-- ── ZONE DE CONTENU ── --}}
        <div class="page-body">

            {{-- Alertes flash session --}}
            @if (session('success'))
                <div class="alert-flash alert-flash-success anim-up">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                    <button onclick="this.parentElement.remove()"
                        style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;font-size:1.1rem">
                        &times;
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert-flash alert-flash-error anim-up">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    {{ session('error') }}
                    <button onclick="this.parentElement.remove()"
                        style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;font-size:1.1rem">
                        &times;
                    </button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert-flash alert-flash-warning anim-up">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    {{ session('warning') }}
                    <button onclick="this.parentElement.remove()"
                        style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;font-size:1.1rem">
                        &times;
                    </button>
                </div>
            @endif

            {{-- Contenu de la vue enfant (dashboard, inscription, etc.) --}}
            @yield('content')

        </div>{{-- /page-body --}}

    </div>{{-- /main-content --}}

    {{-- Bootstrap JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

    {{-- JS global : menu mobile --}}
    <script>
        // Toggle sidebar mobile
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.querySelector('.sidebar');
        if (menuToggle && sidebar) {
            menuToggle.addEventListener('click', () => sidebar.classList.toggle('open'));
        }
        // Fermer sidebar au clic extérieur (mobile)
        document.addEventListener('click', function(e) {
            if (sidebar && sidebar.classList.contains('open')) {
                if (!sidebar.contains(e.target) && e.target !== menuToggle) {
                    sidebar.classList.remove('open');
                }
            }
        });
    </script>

    {{-- JS spécifique à chaque vue (injecté via @push('scripts')) --}}
    @stack('scripts')

</body>

</html>
