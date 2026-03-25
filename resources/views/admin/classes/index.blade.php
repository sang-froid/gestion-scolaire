{{-- ============================================================
     resources/views/admin/classes/index.blade.php
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Classes — Admin EduGest')
@section('page_title', 'Gestion des classes')
@section('page_subtitle', 'Année scolaire ' . $annee)

@push('styles')
<style>
  .page-banner { background:linear-gradient(115deg,#0D1B2A,#1A3A5C); border-radius:14px; padding:1.5rem 2rem; margin-bottom:1.8rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; position:relative; overflow:hidden; }
  .page-banner::after { content:''; position:absolute; right:-40px; top:-40px; width:180px; height:180px; border-radius:50%; background:rgba(56,189,248,.06); pointer-events:none; }
  .page-banner h2 { font-family:'Playfair Display',serif; color:#fff; font-size:1.25rem; margin:0 0 .2rem; }
  .page-banner p  { color:rgba(255,255,255,.5); font-size:.84rem; margin:0; }

  .classe-card { background:#fff; border-radius:13px; border:1px solid #E2E8F0; box-shadow:0 3px 16px rgba(13,27,42,.06); overflow:hidden; height:100%; transition:transform .2s, box-shadow .2s; }
  .classe-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(13,27,42,.1); }
  .classe-card-head { background:linear-gradient(110deg,#0D1B2A,#1A3A5C); padding:1.1rem 1.4rem; }
  .classe-nom { font-family:'Playfair Display',serif; color:#fff; font-size:1.05rem; margin:0 0 .2rem; }
  .classe-niveau { color:#38BDF8; font-size:.78rem; }

  .classe-stat { display:flex; justify-content:space-between; align-items:center; padding:.4rem 0; border-bottom:1px solid #F1F5F9; font-size:.83rem; }
  .classe-stat:last-child { border:none; }
  .classe-stat .k { color:#64748B; }
  .classe-stat .v { color:#0D1B2A; font-weight:600; }

  .fill-bar-wrap { flex:1; background:#F1F5F9; border-radius:20px; height:6px; overflow:hidden; margin:0 .7rem; }
  .fill-bar { height:100%; border-radius:20px; }
  .fill-green  { background:#10B981; }
  .fill-orange { background:#F59E0B; }
  .fill-red    { background:#F43F5E; }

  .classe-card-foot { padding:.8rem 1.4rem; background:#FAFBFC; border-top:1px solid #E2E8F0; display:flex; gap:.5rem; }

  .badge-inactive { background:#F1F5F9; color:#94A3B8; border:1px solid #E2E8F0; border-radius:20px; padding:.15rem .65rem; font-size:.72rem; font-weight:600; }
</style>
@endpush

@section('content')

<div class="page-banner anim-up">
  <div>
    <h2><i class="bi bi-collection-fill me-2" style="color:#38BDF8"></i>Gestion des classes</h2>
    <p>{{ $classes->count() }} classe(s) · Année {{ $annee }}</p>
  </div>
  <div style="position:relative;z-index:1">
    <a href="{{ route('admin.classes.create') }}" class="btn-primary-gu">
      <i class="bi bi-plus-circle"></i> Nouvelle classe
    </a>
  </div>
</div>

@if(session('success'))
  <div class="alert-gu alert-success mb-3"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
  <div class="alert-gu alert-danger mb-3"><i class="bi bi-x-circle-fill"></i> {{ session('error') }}</div>
@endif

@if($classes->isEmpty())
  <div class="card-gu"><div class="empty-state">
    <i class="bi bi-collection"></i>
    <p>Aucune classe créée pour l'année {{ $annee }}.</p>
    <a href="{{ route('admin.classes.create') }}" class="btn-primary-gu mt-3">
      <i class="bi bi-plus-circle"></i> Créer la première classe
    </a>
  </div></div>
@else
  <div class="row g-4">
    @foreach($classes as $classe)
      @php
        $nb  = $classe->nb_eleves ?? 0;
        $cap = $classe->capacite_max;
        $pct = $cap > 0 ? round(($nb / $cap) * 100) : 0;
        $fillClass = $pct >= 90 ? 'fill-red' : ($pct >= 70 ? 'fill-orange' : 'fill-green');
        $pctColor  = $pct >= 90 ? '#F43F5E' : ($pct >= 70 ? '#F59E0B' : '#10B981');
      @endphp
      <div class="col-sm-6 col-lg-4 anim-up">
        <div class="classe-card">
          <div class="classe-card-head">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <div class="classe-nom">{{ $classe->nom }}</div>
                <div class="classe-niveau">{{ $classe->niveau }}</div>
              </div>
              @if(!$classe->active)
                <span class="badge-inactive">Inactive</span>
              @endif
            </div>
          </div>
          <div class="p-3">
            <div class="classe-stat">
              <span class="k"><i class="bi bi-people me-1"></i>Élèves</span>
              <div class="d-flex align-items-center" style="flex:1;justify-content:flex-end;gap:.5rem">
                <div class="fill-bar-wrap">
                  <div class="fill-bar {{ $fillClass }}" style="width:{{ $pct }}%"></div>
                </div>
                <span class="v" style="color:{{ $pctColor }};min-width:55px;text-align:right">
                  {{ $nb }}/{{ $cap }}
                </span>
              </div>
            </div>
            <div class="classe-stat">
              <span class="k"><i class="bi bi-person-badge me-1"></i>Enseignant(e)</span>
              <span class="v">{{ $classe->enseignant_responsable ?? '—' }}</span>
            </div>
            <div class="classe-stat">
              <span class="k"><i class="bi bi-pie-chart me-1"></i>Taux remplissage</span>
              <span class="v" style="color:{{ $pctColor }}">{{ $pct }}%</span>
            </div>
          </div>
          <div class="classe-card-foot">
            <a href="{{ route('admin.classes.show', $classe->id) }}"
               class="btn-primary-gu flex-1 justify-content-center" style="font-size:.82rem;padding:.45rem .8rem">
              <i class="bi bi-eye"></i> Voir les élèves
            </a>
            <a href="{{ route('admin.classes.edit', $classe->id) }}"
               class="btn-outline-gu" style="font-size:.82rem;padding:.45rem .8rem">
              <i class="bi bi-pencil"></i>
            </a>
            <form action="{{ route('admin.classes.destroy', $classe->id) }}" method="POST">
              @csrf @method('DELETE')
              <button type="submit"
                      class="btn-outline-gu"
                      style="font-size:.82rem;padding:.45rem .8rem;border-color:#F43F5E;color:#F43F5E"
                      onclick="return confirm('Supprimer la classe {{ $classe->nom }} ?')">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endif

@endsection
