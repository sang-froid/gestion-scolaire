{{--
|==========================================================================
| resources/views/layouts/_sidebar.blade.php
|==========================================================================
| Rôle : Sidebar UNIQUE. Le menu change selon auth()->user()->role.
|         'admin'  → menu administration complet
|         'parent' → menu espace parent
|
| Inclus dans app.blade.php via @include('layouts._sidebar')
| Aucune variable à passer : tout vient de auth()->user()
|==========================================================================
--}}

<aside class="sidebar">

  {{-- ══ LOGO ══ --}}
  <div class="sidebar-logo">
    <div class="logo-mark">
      <i class="bi bi-mortarboard-fill"></i>
    </div>
    <div class="school-name">École Les Hirondelles</div>
    <div class="school-role">
      {{-- Label selon le rôle --}}
      @if(auth()->user()->role === 'admin')
        Administration
      @else
        Espace Parent
      @endif
    </div>
  </div>

  {{-- ══ NAVIGATION ══ --}}
  <nav class="sidebar-nav">

    {{-- ────────────────────────────────
         MENU ADMIN
    ──────────────────────────────── --}}
    @if(auth()->user()->role === 'admin')

      <p class="nav-section-label">Principal</p>

      <a href="{{ route('admin.dashboard') }}"
         class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2"></i>
        Tableau de bord
      </a>

      <a href="{{ route('admin.inscriptions.index') }}"
         class="nav-item {{ request()->routeIs('admin.inscriptions.*') ? 'active' : '' }}">
        <i class="bi bi-pencil-square"></i>
        Inscriptions
        {{-- Badge : dossiers en attente --}}
        @php $nb_attente = \App\Models\Inscription::where('statut', 'en_attente')->count(); @endphp
        @if($nb_attente > 0)
          <span class="nav-badge">{{ $nb_attente }}</span>
        @endif
      </a>

      <a href="{{ route('admin.eleves.index') }}"
         class="nav-item {{ request()->routeIs('admin.eleves.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i>
        Élèves
      </a>

      <a href="{{ route('admin.classes.index') }}"
         class="nav-item {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
        <i class="bi bi-collection"></i>
        Classes
      </a>

      <p class="nav-section-label">Communication</p>

      <a href="{{ route('admin.notifications.index') }}"
         class="nav-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
        <i class="bi bi-bell"></i>
        Notifications & Alertes
      </a>

      <p class="nav-section-label">Documents</p>

      <a href="{{ route('admin.eleves.index') }}?action=fiches"
         class="nav-item {{ request()->is('admin/eleves*') && request()->get('action') === 'fiches' ? 'active' : '' }}">
        <i class="bi bi-file-earmark-text"></i>
        Fiches d'inscription
      </a>

      <a href="{{ route('admin.eleves.index') }}?action=cartes"
         class="nav-item {{ request()->is('admin/eleves*') && request()->get('action') === 'cartes' ? 'active' : '' }}">
        <i class="bi bi-credit-card-2-front"></i>
        Cartes de scolarité
      </a>

    {{-- ────────────────────────────────
         MENU PARENT
    ──────────────────────────────── --}}
    @elseif(auth()->user()->role === 'parent')

      <p class="nav-section-label">Mon espace</p>

      <a href="{{ route('parent.dashboard') }}"
         class="nav-item {{ request()->routeIs('parent.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2"></i>
        Tableau de bord
      </a>

      <a href="{{ route('parent.inscription.create') }}"
         class="nav-item {{ request()->routeIs('parent.inscription.create') ? 'active' : '' }}">
        <i class="bi bi-plus-circle"></i>
        Nouvelle inscription
      </a>

      {{-- Un lien par enfant inscrit --}}
      @php
        $mesEleves = auth()->user()->parentModel?->eleves ?? collect();
      @endphp

      @if($mesEleves->isNotEmpty())
        <p class="nav-section-label">Mes enfants</p>
        @foreach($mesEleves as $eleve)
          <a href="{{ route('parent.inscription.show', $eleve->inscription->id ?? 0) }}"
             class="nav-item nav-item-child
                    {{ request()->is('parent/inscription/'.($eleve->inscription->id ?? '')) ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i>
            {{ $eleve->prenom }} {{ Str::upper(Str::limit($eleve->nom, 1, '')) }}.
            {{-- Badge statut --}}
            @if($eleve->inscription?->statut === 'en_attente')
              <span class="nav-badge" style="background:var(--gold)">!</span>
            @elseif($eleve->inscription?->statut === 'validee')
              <i class="bi bi-check-circle-fill ms-auto" style="color:var(--mint);font-size:.75rem"></i>
            @endif
          </a>
        @endforeach
      @endif

      <p class="nav-section-label">Informations</p>

      <a href="{{ route('parent.notifications.index') }}"
         class="nav-item {{ request()->routeIs('parent.notifications.*') ? 'active' : '' }}">
        <i class="bi bi-bell"></i>
        Mes notifications
        {{-- Badge : non lues --}}
        @php
          $nb_nonlues = auth()->user()->parentModel
            ?->notifications()
            ->whereNull('lu_le')
            ->count() ?? 0;
        @endphp
        @if($nb_nonlues > 0)
          <span class="nav-badge">{{ $nb_nonlues }}</span>
        @endif
      </a>

    @endif
    {{-- fin @if role --}}

  </nav>
  {{-- /sidebar-nav --}}

  {{-- ══ FOOTER UTILISATEUR ══ --}}
  <div class="sidebar-footer">
    <div class="user-pill">
      <div class="user-avatar-sm">
        {{-- Initiales nom + prénom --}}
        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
      </div>
      <div style="min-width:0">
        <strong style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block">
          {{ auth()->user()->name }}
        </strong>
        <span>
          {{ auth()->user()->role === 'admin' ? 'Administrateur' : 'Parent' }}
        </span>
      </div>
      {{-- Bouton déconnexion rapide --}}
      <form action="{{ route('logout') }}" method="POST" class="ms-auto">
        @csrf
        <button type="submit"
                title="Déconnexion"
                style="background:none;border:none;cursor:pointer;
                       color:rgba(255,255,255,.35);font-size:1rem;
                       padding:.2rem;transition:color .2s"
                onmouseover="this.style.color='var(--rose)'"
                onmouseout="this.style.color='rgba(255,255,255,.35)'">
          <i class="bi bi-box-arrow-right"></i>
        </button>
      </form>
    </div>
  </div>

</aside>
