@extends('layouts.app')

@section('title', 'Fiches d\'inscription — Admin EduGest')
@section('page_title', 'Fiches d\'inscription')
@section('page_subtitle', 'Génération des fiches et cartes de scolarité · ' . $annee)

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

  .filter-bar { display:flex; align-items:center; gap:.6rem; margin-bottom:1.4rem; flex-wrap:wrap; }
  .search-wrap { position:relative; }
  .search-wrap input { border:1.5px solid #E2E8F0; border-radius:10px; padding:.5rem 1rem .5rem 2.4rem; font-size:.86rem; width:220px; }
  .search-wrap input:focus { border-color:#2563EB; outline:none; }
  .search-wrap i { position:absolute; left:.8rem; top:50%; transform:translateY(-50%); color:#94A3B8; }
  .select-filter { border:1.5px solid #E2E8F0; border-radius:10px; padding:.48rem .9rem; font-size:.84rem; background:#fff; }
  .select-filter:focus { border-color:#2563EB; outline:none; }

  /* Classes raccourcis */
  .classe-shortcuts { display:flex; gap:.5rem; flex-wrap:wrap; margin-bottom:1.5rem; }
  .classe-btn {
    display:inline-flex; align-items:center; gap:.5rem;
    padding:.4rem .9rem; border-radius:10px; font-size:.82rem; font-weight:600;
    background:#fff; border:1.5px solid #E2E8F0; color:#0D1B2A;
    text-decoration:none; transition:all .2s;
  }
  .classe-btn:hover { border-color:#2563EB; color:#2563EB; background:#EFF6FF; }
  .classe-btn i { color:#2563EB; }

  /* Table */
  .fiches-table { width:100%; border-collapse:collapse; }
  .fiches-table th { background:#F8FAFC; padding:.75rem 1rem; text-align:left; font-size:.75rem; font-weight:700; color:#64748B; text-transform:uppercase; letter-spacing:.05em; border-bottom:2px solid #E2E8F0; white-space:nowrap; }
  .fiches-table td { padding:.8rem 1rem; font-size:.85rem; color:#0D1B2A; border-bottom:1px solid #F1F5F9; vertical-align:middle; }
  .fiches-table tr:last-child td { border:none; }
  .fiches-table tr:hover td { background:#FAFBFD; }

  .eleve-cell { display:flex; align-items:center; gap:.75rem; }
  .eleve-avatar { width:36px; height:36px; border-radius:50%; flex-shrink:0; background:linear-gradient(135deg,#1A3A5C,#2563EB); display:flex; align-items:center; justify-content:center; font-size:.8rem; font-weight:700; color:#fff; overflow:hidden; }
  .eleve-avatar img { width:100%; height:100%; object-fit:cover; }

  .dl-btns { display:flex; gap:.4rem; }

  .pagi-wrap { display:flex; justify-content:space-between; align-items:center; padding:1rem 1.4rem; border-top:1px solid #E2E8F0; font-size:.83rem; color:#64748B; }
</style>
@endpush

@section('content')

{{-- HEADER --}}
<div class="page-banner anim-up">
  <div>
    <h2><i class="bi bi-file-earmark-text me-2" style="color:#38BDF8"></i>Fiches d'inscription</h2>
    <p>{{ $inscriptions->total() }} élève(s) validé(s) · Téléchargez les fiches et cartes de scolarité</p>
  </div>
</div>

@if(session('error'))
  <div class="alert-gu alert-danger mb-3"><i class="bi bi-x-circle-fill"></i> {{ session('error') }}</div>
@endif

{{-- RACCOURCIS PAR CLASSE --}}


{{-- FILTRES --}}
<form method="GET" action="{{ route('admin.fiches.index') }}">
  <div class="filter-bar">
    <div class="search-wrap">
      <i class="bi bi-search"></i>
      <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher un élève…"/>
    </div>
    <select name="classe_id" class="select-filter" onchange="this.form.submit()">
      <option value="">Toutes les classes</option>
      @foreach($classes as $c)
        <option value="{{ $c->id }}" {{ $classe == $c->id ? 'selected' : '' }}>{{ $c->nom }}</option>
      @endforeach
    </select>
    <button type="submit" class="btn-primary-gu" style="padding:.5rem 1rem;font-size:.85rem">
      <i class="bi bi-search"></i> Filtrer
    </button>
    @if($search || $classe)
      <a href="{{ route('admin.fiches.index') }}" class="btn-outline-gu" style="padding:.5rem 1rem;font-size:.85rem">
        <i class="bi bi-x"></i> Réinitialiser
      </a>
    @endif
  </div>
</form>

{{-- TABLE --}}
<div class="card-gu anim-up">
  <div style="overflow-x:auto">
    <table class="fiches-table">
      <thead>
        <tr>
          <th>Élève</th>
          <th>N° Dossier</th>
          <th>Classe</th>
          <th>Niveau</th>
          <th>Parent</th>
          <th>Télécharger</th>
        </tr>
      </thead>
      <tbody>
        @forelse($inscriptions as $insc)
          <tr>
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
                  <div style="font-weight:600">{{ $insc->eleve->prenom }} {{ $insc->eleve->nom }}</div>
                  <div style="font-size:.75rem;color:#64748B">
                    Né(e) le {{ \Carbon\Carbon::parse($insc->eleve->date_naissance)->isoFormat('D MMM YYYY') }}
                  </div>
                </div>
              </div>
            </td>
            <td>
              <span style="font-family:monospace;font-size:.76rem;color:#2563EB;background:rgba(37,99,235,.08);border:1px solid rgba(37,99,235,.15);border-radius:6px;padding:.1rem .5rem">
                {{ $insc->numero_dossier }}
              </span>
            </td>
            <td style="font-size:.84rem;font-weight:600">
              {{ $insc->classe?->nom ?? '—' }}
            </td>
            <td style="font-size:.83rem;color:#64748B">
              {{ $insc->eleve->niveau_souhaite }}
            </td>
            <td style="font-size:.83rem;color:#64748B">
              {{ $insc->eleve->parent->prenom ?? '' }} {{ $insc->eleve->parent->nom ?? '' }}
            </td>
            <td>
              <div class="dl-btns">
                {{-- Fiche inscription --}}
                <a href="{{ route('admin.fiches.fiche', $insc->id) }}"
                   class="btn-primary-gu" style="padding:.32rem .75rem;font-size:.78rem"
                   title="Télécharger la fiche d'inscription">
                  <i class="bi bi-file-earmark-pdf"></i> Fiche
                </a>
                {{-- Carte scolarité (seulement si classe affectée) --}}
                @if($insc->classe)
                  <a href="{{ route('admin.fiches.carte', $insc->id) }}"
                     class="btn-outline-gu" style="padding:.32rem .75rem;font-size:.78rem"
                     title="Télécharger la carte de scolarité">
                    <i class="bi bi-credit-card-2-front"></i> Carte
                  </a>
                @endif
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" style="text-align:center;padding:3rem;color:#94A3B8">
              <i class="bi bi-file-earmark-x d-block mb-2" style="font-size:2rem;opacity:.2"></i>
              Aucun élève validé trouvé.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($inscriptions->hasPages())
    <div class="pagi-wrap">
      <span>{{ $inscriptions->firstItem() }}–{{ $inscriptions->lastItem() }} sur {{ $inscriptions->total() }}</span>
      {{ $inscriptions->links() }}
    </div>
  @endif
</div>

@endsection