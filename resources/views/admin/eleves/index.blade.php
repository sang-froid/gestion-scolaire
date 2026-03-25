@extends('layouts.app')

@section('title', 'Élèves — Admin EduGest')
@section('page_title', 'Gestion des élèves')
@section('page_subtitle', $eleves->total() . ' élève(s) enregistré(s)')

@push('styles')
<style>
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

  /* Filtres + recherche */
  .filter-bar { display:flex; align-items:center; gap:.5rem; margin-bottom:1.4rem; flex-wrap:wrap; }
  .search-wrap { position:relative; }
  .search-wrap input { border:1.5px solid #E2E8F0; border-radius:10px; padding:.5rem 1rem .5rem 2.4rem; font-size:.86rem; width:240px; transition:border-color .2s; }
  .search-wrap input:focus { border-color:#2563EB; outline:none; box-shadow:0 0 0 3px rgba(37,99,235,.08); }
  .search-wrap i { position:absolute; left:.8rem; top:50%; transform:translateY(-50%); color:#94A3B8; }
  .select-filter { border:1.5px solid #E2E8F0; border-radius:10px; padding:.48rem .9rem; font-size:.84rem; color:#64748B; background:#fff; }
  .select-filter:focus { border-color:#2563EB; outline:none; }

  /* Table */
  .eleves-table { width:100%; border-collapse:collapse; }
  .eleves-table th { background:#F8FAFC; padding:.75rem 1rem; text-align:left; font-size:.75rem; font-weight:700; color:#64748B; text-transform:uppercase; letter-spacing:.05em; border-bottom:2px solid #E2E8F0; white-space:nowrap; }
  .eleves-table td { padding:.8rem 1rem; font-size:.85rem; color:#0D1B2A; border-bottom:1px solid #F1F5F9; vertical-align:middle; }
  .eleves-table tr:last-child td { border:none; }
  .eleves-table tr:hover td { background:#FAFBFD; }

  .eleve-cell { display:flex; align-items:center; gap:.75rem; }
  .eleve-avatar {
    width:38px; height:38px; border-radius:50%; flex-shrink:0;
    background:linear-gradient(135deg,#1A3A5C,#2563EB);
    display:flex; align-items:center; justify-content:center;
    font-size:.85rem; font-weight:700; color:#fff; overflow:hidden;
  }
  .eleve-avatar img { width:100%; height:100%; object-fit:cover; }
  .eleve-name { font-weight:600; font-size:.87rem; color:#0D1B2A; }
  .eleve-sub  { font-size:.75rem; color:#64748B; }

  .classe-tag {
    display:inline-flex; align-items:center; gap:.3rem;
    background:rgba(37,99,235,.08); color:#2563EB;
    border:1px solid rgba(37,99,235,.18); border-radius:20px;
    padding:.15rem .65rem; font-size:.76rem; font-weight:600;
  }
  .classe-tag.non-affecte { background:#F1F5F9; color:#94A3B8; border-color:#E2E8F0; }

  .pagi-wrap { display:flex; justify-content:space-between; align-items:center; padding:1rem 1.4rem; border-top:1px solid #E2E8F0; font-size:.83rem; color:#64748B; }
</style>
@endpush

@section('content')

{{-- HEADER --}}
<div class="page-banner anim-up">
  <div>
    <h2><i class="bi bi-people-fill me-2" style="color:#38BDF8"></i>Gestion des élèves</h2>
    <p>{{ $eleves->total() }} élève(s) au total</p>
  </div>
</div>

{{-- FILTRES --}}
<form method="GET" action="{{ route('admin.eleves.index') }}">
  <div class="filter-bar">
    {{-- Recherche --}}
    <div class="search-wrap">
      <i class="bi bi-search"></i>
      <input type="text" name="search" value="{{ $search }}"
             placeholder="Nom, prénom, matricule…"/>
    </div>

    {{-- Filtre niveau --}}
    <select name="niveau" class="select-filter" onchange="this.form.submit()">
      <option value="">Tous les niveaux</option>
      @foreach($niveaux as $n)
        <option value="{{ $n }}" {{ $niveau === $n ? 'selected' : '' }}>{{ $n }}</option>
      @endforeach
    </select>

    <button type="submit" class="btn-primary-gu" style="padding:.5rem 1rem;font-size:.85rem">
      <i class="bi bi-search"></i> Rechercher
    </button>

    @if($search || $niveau)
      <a href="{{ route('admin.eleves.index') }}" class="btn-outline-gu" style="padding:.5rem 1rem;font-size:.85rem">
        <i class="bi bi-x"></i> Réinitialiser
      </a>
    @endif
  </div>
</form>

{{-- TABLE --}}
<div class="card-gu anim-up">
  <div style="overflow-x:auto">
    <table class="eleves-table">
      <thead>
        <tr>
          <th>Élève</th>
          <th>Date de naissance</th>
          <th>Sexe</th>
          <th>Niveau souhaité</th>
          <th>Classe affectée</th>
          <th>Parent</th>
          <th>Statut inscription</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($eleves as $eleve)
          @php
            $insc   = $eleve->inscription;
            $statut = $insc?->statut;
          @endphp
          <tr>
            {{-- Élève --}}
            <td>
              <div class="eleve-cell">
                <div class="eleve-avatar">
                  @if($eleve->photo)
                    <img src="{{ asset('storage/'.$eleve->photo) }}" alt=""/>
                  @else
                    {{ strtoupper(substr($eleve->prenom, 0, 1)) }}
                  @endif
                </div>
                <div>
                  <div class="eleve-name">{{ $eleve->prenom }} {{ $eleve->nom }}</div>
                  @if($eleve->matricule)
                    <div class="eleve-sub" style="font-family:monospace">{{ $eleve->matricule }}</div>
                  @endif
                </div>
              </div>
            </td>

            {{-- Naissance --}}
            <td style="color:#64748B;font-size:.83rem">
              {{ \Carbon\Carbon::parse($eleve->date_naissance)->isoFormat('D MMM YYYY') }}
            </td>

            {{-- Sexe --}}
            <td>
              <i class="bi bi-{{ $eleve->sexe === 'M' ? 'gender-male' : 'gender-female' }}"
                 style="color:{{ $eleve->sexe === 'M' ? '#2563EB' : '#F43F5E' }};font-size:1.1rem"></i>
            </td>

            {{-- Niveau --}}
            <td style="font-size:.83rem">{{ $eleve->niveau_souhaite ?? '—' }}</td>

            {{-- Classe --}}
            <td>
              @if($insc?->classe)
                <span class="classe-tag">
                  <i class="bi bi-collection"></i> {{ $insc->classe->nom }}
                </span>
              @else
                <span class="classe-tag non-affecte">
                  <i class="bi bi-dash"></i> Non affecté
                </span>
              @endif
            </td>

            {{-- Parent --}}
            <td style="font-size:.83rem;color:#64748B">
              {{ $eleve->parent->prenom ?? '' }} {{ $eleve->parent->nom ?? '' }}
            </td>

            {{-- Statut inscription --}}
            <td>
              @if($statut === 'validee')
                <span class="badge-gu badge-validee"><i class="bi bi-check2-circle"></i> Validée</span>
              @elseif($statut === 'en_attente')
                <span class="badge-gu badge-attente"><i class="bi bi-hourglass-split"></i> En attente</span>
              @elseif($statut === 'refusee')
                <span class="badge-gu badge-refusee"><i class="bi bi-x-circle"></i> Refusée</span>
              @else
                <span style="font-size:.76rem;color:#94A3B8">—</span>
              @endif
            </td>

            {{-- Actions --}}
            <td>
              <a href="{{ route('admin.eleves.show', $eleve->id) }}"
                 class="btn-primary-gu" style="padding:.32rem .75rem;font-size:.78rem">
                <i class="bi bi-eye"></i>
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" style="text-align:center;padding:3.5rem;color:#94A3B8">
              <i class="bi bi-people d-block mb-2" style="font-size:2.2rem;opacity:.2"></i>
              Aucun élève trouvé.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  @if($eleves->hasPages())
    <div class="pagi-wrap">
      <span>{{ $eleves->firstItem() }}–{{ $eleves->lastItem() }} sur {{ $eleves->total() }}</span>
      {{ $eleves->links() }}
    </div>
  @endif
</div>

@endsection