<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion Scolaire')</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        /* layout de base */
        body {
            background-color: #f4f6f9;
        }

        .sidebar {
            min-height: 100vh;
            width: 250px;
            background-color: #1a3a5c;
            position: fixed;
            top: 56px; /* hauteur navbar */
            left: 0;
            z-index: 100;
            overflow-y: auto;
        }

        .sidebar .nav-link {
            color: #c8d8e8;
            padding: 10px 20px;
            font-size: 0.9rem;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: #2563a8;
            border-left: 3px solid #60a5fa;
        }

        .sidebar .nav-section {
            color: #7aa3c8;
            font-size: 0.75rem;
            text-transform: uppercase;
            padding: 15px 20px 5px;
            letter-spacing: 1px;
        }

        .main-content {
            margin-left: 250px;
            margin-top: 56px;
            padding: 25px;
        }

        /* responsive — cacher sidebar sur mobile */
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Contenu principal --}}
    <div class="main-content">

        {{-- Fil d'ariane optionnel --}}
        @hasSection('breadcrumb')
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    @yield('breadcrumb')
                </ol>
            </nav>
        @endif

        {{-- Messages flash --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Contenu de la page --}}
        @yield('content')

    </div>

</body>
</html>