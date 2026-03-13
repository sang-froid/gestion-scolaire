<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #1e4d8c;">
    <div class="container-fluid">

        {{-- Logo / Nom de l'école --}}
        <a class="navbar-brand fw-bold" href="#">
            🏫 Gestion Scolaire
        </a>

        {{-- Bouton mobile --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarTop">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarTop">
            {{-- Espace vide pour pousser les éléments à droite --}}
            <ul class="navbar-nav me-auto"></ul>

            {{-- Partie droite --}}
            <ul class="navbar-nav align-items-center">

                {{-- Notifications --}}
                <li class="nav-item me-2">
                    <a class="nav-link position-relative" href="#">
                        🔔
                        <span class="position-absolute top-0 start-100 translate-middle
                                     badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                            3
                        </span>
                    </a>
                </li>

                {{-- Menu utilisateur --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                       href="#" role="button" data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'User' }}&background=2563a8&color=fff&size=32"
                             class="rounded-circle" width="32" height="32" alt="avatar">
                        <span>{{ auth()->user()->name ?? 'Utilisateur' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Mon profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                Déconnexion
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}"
                                  method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>