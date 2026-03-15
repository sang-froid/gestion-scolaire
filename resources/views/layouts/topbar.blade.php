
<div class="topbar">

  {{-- ── GAUCHE : Titre de la page + bouton menu mobile ── --}}
  <div class="d-flex align-items-center gap-3">

    {{-- Bouton hamburger (mobile uniquement) --}}
    <button id="menuToggle"
            class="d-md-none"
            style="background:none;border:none;cursor:pointer;
                   color:var(--slate);font-size:1.3rem;padding:.2rem">
      <i class="bi bi-list"></i>
    </button>

    <div>
      <div class="topbar-title">@yield('page_title', 'Tableau de bord')</div>
      <div class="topbar-sub">@yield('page_subtitle', '')</div>
    </div>

  </div>

  {{-- ── DROITE : Actions ── --}}
  <div class="d-flex align-items-center gap-2">

    {{-- Cloche notifications --}}
    @if(auth()->user()->role === 'admin')
      @php $nb_notif = \App\Models\Inscription::where('statut','en_attente')->count(); @endphp
      <a href="{{ route('admin.notifications.index') }}" class="topbar-icon">
        <i class="bi bi-bell"></i>
        @if($nb_notif > 0)
          <span class="notif-dot"></span>
        @endif
      </a>
    @else
      @php
        $nb_notif = auth()->user()->parentModel
          ?->notifications()->whereNull('lu_le')->count() ?? 0;
      @endphp
      <a href="{{ route('parent.notifications.index') }}" class="topbar-icon">
        <i class="bi bi-bell"></i>
        @if($nb_notif > 0)
          <span class="notif-dot"></span>
        @endif
      </a>
    @endif

    {{-- Séparateur visuel --}}
    <div style="width:1px;height:24px;background:var(--border)"></div>

    {{-- ── Dropdown utilisateur ── --}}
    <div class="dropdown">
      <button class="btn-avatar dropdown-toggle border-0"
              data-bs-toggle="dropdown"
              aria-expanded="false">
        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
      </button>

      <ul class="dropdown-menu dropdown-menu-end border-0 rounded-3"
          style="min-width:220px;box-shadow:0 8px 30px rgba(13,27,42,.12);padding:.5rem">

        {{-- Infos utilisateur --}}
        <li class="px-3 py-2">
          <div style="font-weight:700;font-size:.9rem;color:var(--navy)">
            {{ auth()->user()->name }}
          </div>
          <div style="font-size:.78rem;color:var(--slate)">
            {{ auth()->user()->email }}
          </div>
          <div style="margin-top:.3rem">
            <span class="badge-gu badge-info" style="font-size:.7rem">
              {{ auth()->user()->role === 'admin' ? 'Administrateur' : 'Parent' }}
            </span>
          </div>
        </li>

        <li><hr class="dropdown-divider my-1" style="border-color:var(--border)"></li>

        {{-- Lien profil (optionnel) --}}
        {{--
        <li>
          <a class="dropdown-item rounded-2 d-flex align-items-center gap-2"
             href="#" style="font-size:.88rem">
            <i class="bi bi-person-circle"></i> Mon profil
          </a>
        </li>
        --}}

        {{-- Déconnexion --}}
        <li>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                    class="dropdown-item rounded-2 d-flex align-items-center gap-2 text-danger"
                    style="font-size:.88rem">
              <i class="bi bi-box-arrow-right"></i> Déconnexion
            </button>
          </form>
        </li>

      </ul>
    </div>
    {{-- /dropdown --}}

  </div>
  {{-- /droite --}}

</div>
{{-- /topbar --}}
