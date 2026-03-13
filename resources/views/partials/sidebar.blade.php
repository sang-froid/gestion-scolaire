<div class="sidebar">
    <ul class="nav flex-column pt-2">

        {{-- Dashboard --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}"
               href="{{ route('dashboard') }}">
                📊 Tableau de bord
            </a>
        </li>

        {{-- Section Inscriptions --}}
        <li class="nav-section">Inscriptions</li>

        <li class="nav-item">
            <a class="nav-link {{ request()->is('inscriptions*') ? 'active' : '' }}"
               href="#">
                📝 Nouvelle inscription
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->is('reinscriptions*') ? 'active' : '' }}"
               href="#">
                🔄 Réinscriptions
            </a>
        </li>

        {{-- Section Classes --}}
        <li class="nav-section">Classes</li>

        <li class="nav-item">
            <a class="nav-link {{ request()->is('classes*') ? 'active' : '' }}"
               href="#">
                🏫 Gestion des classes
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->is('affectations*') ? 'active' : '' }}"
               href="#">
                👥 Affectations
            </a>
        </li>

        {{-- Section Documents --}}
        <li class="nav-section">Documents</li>

        <li class="nav-item">
            <a class="nav-link" href="#">
                📄 Fiches d'inscription
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#">
                🪪 Cartes de scolarité
            </a>
        </li>

        {{-- Section Notifications --}}
        <li class="nav-section">Notifications</li>

        <li class="nav-item">
            <a class="nav-link" href="#">
                📧 Envoyer un message
            </a>
        </li>

    </ul>
</div>