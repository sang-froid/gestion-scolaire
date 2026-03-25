@extends('layouts.app')

@section('title', 'Inscriptions — Admin EduGest')
@section('page_title', 'Gestion des inscriptions')
@section('page_subtitle', 'Année scolaire ' . $annee)

@push('styles')
<style>
  /* ── Header ── */
  .page-banner {
    background: linear-gradient(115deg, #0D1B2A, #1A3A5C);
    border-radius: 14px; padding: 1.5rem 2rem;
    margin-bottom: 1.8rem; display: flex;
    align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 1rem; position: relative; overflow: hidden;
  }
  .page-banner::after { content:''; position:absolute; right:-40px; top:-40px; width:180px; height:180px; border-radius:50%; background:rgba(56,189,248,.06); pointer-events:none; }
  .page-banner h2 { font-family:'Playfair Display',serif; color:#fff; font-size:1.25rem; margin:0 0 .2rem; }
  .page-banner p  { color:rgba(255,255,255,.5); font-size:.84rem; margin:0; }

  /* ── Stats chips ── */
  .stat-chip {
    display: inline-flex; align-items: center; gap: .4rem;
    background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15);
    border-radius: 20px; padding: .35rem .9rem;
    font-size: .82rem; font-weight: 600; color: #fff;
    position: relative; z-index: 1;
  }

  /* ── Filtres ── */
  .filter-bar { display:flex; align-items:center; gap:.5rem; margin-bottom:1.4rem; flex-wrap:wrap; }
  .filter-chip {
    display:inline-flex; align-items:center; gap:.4rem;
    padding:.38rem .95rem; border-radius:20px; font-size:.82rem; font-weight:600;
    border:1.5px solid #E2E8F0; background:#fff; color:#64748B;
    text-decoration:none; transition:all .2s;
  }
  .filter-chip:hover  { border-color:#2563EB; color:#2563EB; background:#EFF6FF; }
  .filter-chip.active { border-color:#2563EB; color:#2563EB; background:#EFF6FF; }
  .filter-chip.fc-att.active { border-color:#F59E0B; color:#B45309; background:rgba(245,158,11,.08); }
  .filter-chip.fc-val.active { border-color:#10B981; color:#065F46; background:rgba(16,185,129,.07); }
  .filter-chip.fc-ref.active { border-color:#F43F5E; color:#9F1239; background:rgba(244,63,94,.07); }
  .chip-count { display:inline-flex; align-items:center; justify-content:center; width:18px; height:18px; border-radius:50%; background:#2563EB; color:#fff; font-size:.68rem; font-weight:700; }
  .fc-att .chip-count { background:#F59E0B; }
  .fc-val .chip-count { background:#10B981; }
  .fc-ref .chip-count { background:#F43F5E; }

  /* ── Recherche ── */
  .search-wrap { position:relative; }
  .search-wrap input { border:1.5px solid #E2E8F0; border-radius:10px; padding:.5rem 1rem .5rem 2.4rem; font-size:.86rem; width:260px; transition:border-color .2s; }
  .search-wrap input:focus { border-color:#2563EB; outline:none; box-shadow:0 0 0 3px rgba(37,99,235,.08); }
  .search-wrap i { position:absolute; left:.8rem; top:50%; transform:translateY(-50%); color:#94A3B8; }

  /* ── Table ── */
  .inscr-table { width:100%; border-collapse:collapse; }
  .inscr-table th { background:#F8FAFC; padding:.75rem 1rem; text-align:left; font-size:.75rem; font-weight:700; color:#64748B; text-transform:uppercase; letter-spacing:.05em; border-bottom:2px solid #E2E8F0; white-space:nowrap; }
  .inscr-table td { padding:.8rem 1rem; font-size:.85rem; color:#0D1B2A; border-bottom:1px solid #F1F5F9; vertical-align:middle; }
  .inscr-table tr:last-child td { border:none; }
  .inscr-table tr:hover td { background:#FAFBFD; }

  .eleve-cell { display:flex; align-items:center; gap:.75rem; }
  .eleve-avatar {
    width:36px; height:36px; border-radius:50%; flex-shrink:0;
    background:linear-gradient(135deg,#1A3A5C,#2563EB);
    display:flex; align-items:center; justify-content:center;
    font-size:.8rem; font-weight:700; color:#fff; overflow:hidden;
  }
  .eleve-avatar img { width:100%; height:100%; object-fit:cover; }
  .eleve-name { font-weight:600; font-size:.86rem; }
  .eleve-sub  { font-size:.75rem; color:#64748B; }

  .num-badge { font-family:monospace; font-size:.75rem; color:#2563EB; background:rgba(37,99,235,.08); border:1px solid rgba(37,99,235,.15); border-radius:6px; padding:.1rem .5rem; }

  /* ── Actions inline ── */
  .action-btns { display:flex; gap:.4rem; }

  /* ── Pagination ── */
  .pagi-wrap { display:flex; justify-content:space-between; align-items:center; padding:1rem 1.4rem; border-top:1px solid #E2E8F0; font-size:.83rem; color:#64748B; }
</style>
@endpush

@section('content')

{{-- HEADER --}}
<div class="page-banner anim-up">
  <div>
    <h2><i class="bi bi-folder2-open me-2" style="color:#38BDF8"></i>Gestion des inscriptions</h2>
    <p>{{ $stats['tous'] }} dossier(s) · Année {{ $annee }}</p>
  </div>
  <div class="d-flex gap-2 flex-wrap" style="position:relative;z-index:1">
    <span class="stat-chip"><i class="bi bi-hourglass-split"></i> {{ $stats['en_attente'] }} en attente</span>
    <span class="stat-chip" style="border-color:rgba(16,185,129,.3)"><i class="bi bi-check2-circle"></i> {{ $stats['validee'] }} validées</span>
  </div>
</div>

{{-- ALERTES --}}
@if(session('success'))
  <div class="alert-gu alert-success mb-3">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div class="alert-gu alert-danger mb-3">
    <i class="bi bi-x-circle-fill"></i> {{ session('error') }}
  </div>
@endif

{{-- FILTRES + RECHERCHE --}}
<div class="filter-bar">
  <a href="{{ route('admin.inscriptions.index') }}"
     class="filter-chip {{ $statut === 'tous' ? 'active' : '' }}">
    Tous <span class="chip-count">{{ $stats['tous'] }}</span>
  </a>
  <a href="{{ route('admin.inscriptions.index', ['statut' => 'en_attente']) }}"
     class="filter-chip fc-att {{ $statut === 'en_attente' ? 'active' : '' }}">
    <i class="bi bi-hourglass-split"></i> En attente
    <span class="chip-count">{{ $stats['en_attente'] }}</span>
  </a>
  <a href="{{ route('admin.inscriptions.index', ['statut' => 'validee']) }}"
     class="filter-chip fc-val {{ $statut === 'validee' ? 'active' : '' }}">
    <i class="bi bi-check2-circle"></i> Validées
    <span class="chip-count">{{ $stats['validee'] }}</span>
  </a>
  <a href="{{ route('admin.inscriptions.index', ['statut' => 'refusee']) }}"
     class="filter-chip fc-ref {{ $statut === 'refusee' ? 'active' : '' }}">
    <i class="bi bi-x-circle"></i> Refusées
    <span class="chip-count">{{ $stats['refusee'] }}</span>
  </a>

  {{-- Recherche --}}
  <form method="GET" action="{{ route('admin.inscriptions.index') }}" class="ms-auto">
    <input type="hidden" name="statut" value="{{ $statut }}"/>
    <div class="search-wrap">
      <i class="bi bi-search"></i>
      <input type="text" name="search" value="{{ $search }}"
             placeholder="Rechercher un élève…"/>
    </div>
  </form>
</div>

{{-- TABLE --}}
<div class="card-gu anim-up">
  <div style="overflow-x:auto">
    <table class="inscr-table">
      <thead>
        <tr>
          <th>Élève</th>
          <th>N° Dossier</th>
          <th>Niveau souhaité</th>
          <th>Type</th>
          <th>Statut</th>
          <th>Classe</th>
          <th>Soumis le</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($inscriptions as $insc)
          <tr>
            {{-- Élève --}}
            <td>
              <div class="eleve-cell">
                <div class="eleve-avatar">
                  @if($insc->eleve->photo)
                    <img src="{{ asset('storage/'.$insc->eleve->photo) }}" alt=""/>
                  @else
                    {{ strtoupper(substr($insc->eleve->prenom, 0, 1)) }}
                  @endif
                </div>
                <div>
                  <div class="eleve-name">{{ $insc->eleve->prenom }} {{ $insc->eleve->nom }}</div>
                  <div class="eleve-sub">
                    {{ $insc->eleve->parent->prenom ?? '' }} {{ $insc->eleve->parent->nom ?? '' }}
                  </div>
                </div>
              </div>
            </td>

            {{-- N° dossier --}}
            <td><span class="num-badge">{{ $insc->numero_dossier }}</span></td>

            {{-- Niveau --}}
            <td style="font-size:.83rem">{{ $insc->eleve->niveau_souhaite }}</td>

            {{-- Type --}}
            <td>
              <span style="font-size:.76rem;font-weight:600;
                @if($insc->type === 'reinscription') color:#7C3AED @else color:#64748B @endif">
                {{ $insc->type === 'reinscription' ? '↻ Réinscription' : '✦ Nouvelle' }}
              </span>
            </td>

            {{-- Statut --}}
            <td>
              @if($insc->statut === 'validee')
                <span class="badge-gu badge-validee"><i class="bi bi-check2-circle"></i> Validée</span>
              @elseif($insc->statut === 'en_attente')
                <span class="badge-gu badge-attente"><i class="bi bi-hourglass-split"></i> En attente</span>
              @else
                <span class="badge-gu badge-refusee"><i class="bi bi-x-circle"></i> Refusée</span>
              @endif
            </td>

            {{-- Classe --}}
            <td style="font-size:.83rem">
              {{ $insc->classe?->nom ?? '—' }}
            </td>

            {{-- Date --}}
            <td style="font-size:.79rem;color:#94A3B8">
              {{ $insc->created_at->isoFormat('D MMM YYYY') }}
            </td>

            {{-- Actions --}}
            <td>
              <div class="action-btns">
                {{-- Voir le dossier --}}
                <a href="{{ route('admin.inscriptions.show', $insc->id) }}"
                   class="btn-primary-gu" style="padding:.32rem .75rem;font-size:.78rem"
                   title="Voir le dossier">
                  <i class="bi bi-eye"></i>
                </a>

                {{-- Valider (seulement si en attente) --}}
                @if($insc->statut === 'en_attente')
                  <form action="{{ route('admin.inscriptions.valider', $insc->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="btn-outline-gu"
                            style="padding:.32rem .75rem;font-size:.78rem;border-color:#10B981;color:#065F46"
                            title="Valider"
                            onclick="return confirm('Valider le dossier #{{ $insc->numero_dossier }} ?')">
                      <i class="bi bi-check-lg"></i>
                    </button>
                  </form>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center py-5" style="color:#94A3B8">
              <i class="bi bi-folder-x d-block mb-2" style="font-size:2rem;opacity:.3"></i>
              Aucun dossier trouvé.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  @if($inscriptions->hasPages())
    <div class="pagi-wrap">
      <span>{{ $inscriptions->firstItem() }}–{{ $inscriptions->lastItem() }} sur {{ $inscriptions->total() }}</span>
      {{ $inscriptions->links() }}
    </div>
  @endif
</div>

@endsection
