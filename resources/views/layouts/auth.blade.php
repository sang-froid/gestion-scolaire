<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion Scolaire')</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light">

    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">

                {{-- Logo / Titre --}}
                <div class="text-center mb-4">
                    <h3 class="fw-bold" style="color: #1e4d8c;">🏫 Gestion Scolaire</h3>
                    <p class="text-muted">Plateforme de gestion des établissements</p>
                </div>

                {{-- Carte centrale --}}
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        @yield('content')
                    </div>
                </div>

                {{-- Liens bas de page --}}
                <div class="text-center mt-3 text-muted small">
                    @yield('auth-links')
                </div>

            </div>
        </div>
    </div>

</body>
</html>