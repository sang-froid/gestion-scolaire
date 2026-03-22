@extends('layouts.app')

@section('title', 'Dashboard Admin — EduGest')
@section('page_title', 'Tableau de bord')
@section('page_subtitle', 'Vue d\'ensemble · Année scolaire ' . $annee)

@push('styles')
<style>
  /* ── Welcome banner ── */
  .admin-banner {
    background: linear-gradient(115deg, #0D1B2A 0%, #1A3A5C 55%, #1e4d7e 100%);
    border-radius: 14px; padding: 1.6rem 2rem;
    margin-bottom: 1.8rem; position: relative; overflow: hidden;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 1rem;
  }
  .admin-banner::after {
    content:''; position:absolute; right:-40px; top:-40px;
    width:200px; height:200px; border-radius:50%;
    background:rgba(56,189,248,.06); pointer-events:none;
  }
  .admin-banner h2 { font-family:'Playfair Display',serif; color:#fff; font-size:1.35rem; margin:0 0 .25rem; }
  .admin-banner p  { color:rgba(255,255,255,.55); font-size:.87rem; margin:0; }

  /* ── Stat cards ── */
  .stat-card {
    background:#fff; border-radius:14px;
    border:1px solid #E2E8F0; box-shadow:0 4px 20px rgba(13,27,42,.07);
    padding:1.3rem 1.5rem; display:flex; align-items:center; gap:1rem;
    transition:transform .2s, box-shadow .2s; text-decoration:none;
  }
  .stat-card:hover { transform:translateY(-3px); box-shadow:0 8px 28px rgba(13,27,42,.12); }
  .stat-icon  { width:52px; height:52px; border-radius:13px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0; }
  .stat-val   { font-family:'Playfair Display',serif; font-size:1.9rem; color:#0D1B2A; line-height:1; }
  .stat-label { color:#64748B; font-size:.8rem; margin-top:.2rem; }
  .stat-delta { font-size:.75rem; margin-top:.3rem; font-weight:600; }
  .delta-warn { color:#F59E0B; }
  .delta-ok   { color:#10B981; }
  .delta-info { color:#2563EB; }

  /* ── Alerte dossiers en attente ── */
  .alert-attente {
    background:rgba(245,158,11,.08); border:1px solid rgba(245,158,11,.25);
    border-left:4px solid #F59E0B; border-radius:10px;
    padding:.9rem 1.2rem; margin-bottom:1.5rem;
    display:flex; align-items:center; gap:.8rem;
    font-size:.88rem; color:#92400E;
    text-decoration:none; transition:background .2s;
  }
  .alert-attente:hover { background:rgba(245,158,11,.13); color:#92400E; }
  .alert-attente i { color:#F59E0B; font-size:1.1rem; flex-shrink:0; }
  .alert-attente strong { color:#B45309; }

  /* ── Section title ── */
  .section-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem; }
  .section-head h5 { font-family:'Playfair Display',serif; font-size:1rem; color:#0D1B2A; margin:0; display:flex; align-items:center; gap:.5rem; }
  .section-head h5 i { color:#2563EB; }

  /* ── Tableau inscriptions ── */
  .inscr-table { width:100%; border-collapse:collapse; }
  .inscr-table th { background:#F8FAFC; padding:.7rem 1rem; text-align:left; font-size:.75rem; font-weight:700; color:#64748B; text-transform:uppercase; letter-spacing:.05em; border-bottom:2px solid #E2E8F0; }
  .inscr-table td { padding:.75rem 1rem; font-size:.85rem; color:#0D1B2A; border-bottom:1px solid #F1F5F9; vertical-align:middle; }
  .inscr-table tr:last-child td { border:none; }
  .inscr-table tr:hover td { background:#FAFBFD; }

  .eleve-mini { display:flex; align-items:center; gap:.7rem; }
  .eleve-mini-avatar {
    width:34px; height:34px; border-radius:50%; flex-shrink:0;
    background:linear-gradient(135deg,#1A3A5C,#2563EB);
    display:flex; align-items:center; justify-content:center;
    font-size:.78rem; font-weight:700; color:#fff; overflow:hidden;
  }
  .eleve-mini-avatar img { width:100%; height:100%; object-fit:cover; }
  .eleve-mini-name { font-weight:600; font-size:.86rem; color:#0D1B2A; }
  .eleve-mini-sub  { font-size:.76rem; color:#64748B; }

  .numero-badge { font-family:monospace; font-size:.76rem; color:#2563EB; background:rgba(37,99,235,.08); border:1px solid rgba(37,99,235,.15); border-radius:6px; padding:.12rem .5rem; }

  /* ── Graphe barres CSS ── */
  .bar-chart { display:flex; align-items:flex-end; gap:.5rem; height:120px; padding-top:10px; }
  .bar-item  { flex:1; display:flex; flex-direction:column; align-items:center; gap:.3rem; }
  .bar       { width:100%; border-radius:6px 6px 0 0; background:linear-gradient(180deg,#2563EB,#1D4ED8); min-height:4px; transition:height .4s ease; position:relative; }
  .bar:hover { background:linear-gradient(180deg,#38BDF8,#2563EB); }
  .bar-val   { font-size:.7rem; font-weight:700; color:#0D1B2A; position:absolute; top:-18px; left:50%; transform:translateX(-50%); white-space:nowrap; }
  .bar-label { font-size:.68rem; color:#94A3B8; text-align:center; white-space:nowrap; }

  /* ── Classes remplissage ── */
  .classe-row { display:flex; align-items:center; gap:.8rem; padding:.6rem 0; border-bottom:1px solid #F1F5F9; }
  .classe-row:last-child { border:none; }
  .classe-nom { font-size:.85rem; font-weight:600; color:#0D1B2A; min-width:80px; }
  .classe-bar-wrap { flex:1; background:#F1F5F9; border-radius:20px; height:8px; overflow:hidden; }
  .classe-bar-fill { height:100%; border-radius:20px; transition:width .5s ease; }
  .fill-green  { background:linear-gradient(90deg,#10B981,#059669); }
  .fill-orange { background:linear-gradient(90deg,#F59E0B,#D97706); }
  .fill-red    { background:linear-gradient(90deg,#F43F5E,#E11D48); }
  .classe-pct  { font-size:.76rem; font-weight:700; min-width:36px; text-align:right; }

  /* ── Actions rapides ── */
  .quick-action {
    display:flex; align-items:center; gap:.9rem;
    padding:.9rem 1.1rem; border-radius:11px;
    border:1.5px solid #E2E8F0; background:#fff;
    text-decoration:none; transition:all .2s;
    margin-bottom:.6rem;
  }
  .quick-action:hover { border-color:#2563EB; background:#EFF6FF; transform:translateX(3px); }
  .qa-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0; }
  .quick-action .qa-text strong { display:block; font-size:.86rem; color:#0D1B2A; }
  .quick-action .qa-text span   { font-size:.76rem; color:#64748B; }
  .quick-action i.arrow { margin-left:auto; color:#CBD5E1; font-size:.9rem; }
</style>
@endpush

@section('content')

{{-- ── BANNER ── --}}
<div class="admin-banner anim-up">
  <div>
    <h2>Bonjour, {{ Auth::user()->name }} </h2>
    <p>Voici l'état de votre établissement · {{ now()->isoFormat('dddd D MMMM YYYY') }}</p>
  </div>
  <div style="position:relative;z-index:1">
    <a href="{{ route('admin.inscriptions.index') }}" class="btn-primary-gu" style="font-size:.88rem">
      <i class="bi bi-list-check"></i> Gérer les inscriptions
    </a>
  </div>
</div>

{{-- ── ALERTE DOSSIERS EN ATTENTE ── --}}
@if($enAttente > 0)
  <a href="{{ route('admin.inscriptions.index', ['statut' => 'en_attente']) }}" class="alert-attente anim-up">
    <i class="bi bi-exclamation-triangle-fill"></i>
    <span>
      <strong>{{ $enAttente }} dossier(s) en attente</strong> de validation.
      Cliquez pour les traiter.
    </span>
    <i class="bi bi-arrow-right ms-auto"></i>
  </a>
@endif

@if($totalNonAffectes > 0)
  <a href="{{ route('admin.inscriptions.index', ['statut' => 'validee', 'affectation' => 'non']) }}" class="alert-attente anim-up" style="border-left-color:#2563EB;background:rgba(37,99,235,.06);color:#1E40AF">
    <i class="bi bi-collection" style="color:#2563EB"></i>
    <span>
      <strong>{{ $totalNonAffectes }} élève(s) validé(s)</strong> n'ont pas encore de classe.
    </span>
    <i class="bi bi-arrow-right ms-auto"></i>
  </a>
@endif

{{-- ── STAT CARDS ── --}}
<div class="row g-3 mb-4">
  <div class="col-6 col-md-3">
    <a href="{{ route('admin.inscriptions.index') }}" class="stat-card anim-up d-flex">
      <div class="stat-icon" style="background:rgba(37,99,235,.1)">
        <i class="bi bi-folder2-open" style="color:#2563EB"></i>
      </div>
      <div>
        <div class="stat-val">{{ $totalInscriptions }}</div>
        <div class="stat-label">Inscriptions totales</div>
        <div class="stat-delta delta-info">Année {{ $annee }}</div>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-3">
    <a href="{{ route('admin.inscriptions.index', ['statut' => 'en_attente']) }}" class="stat-card anim-up delay-1 d-flex">
      <div class="stat-icon" style="background:rgba(245,158,11,.1)">
        <i class="bi bi-hourglass-split" style="color:#F59E0B"></i>
      </div>
      <div>
        <div class="stat-val">{{ $enAttente }}</div>
        <div class="stat-label">En attente</div>
        @if($enAttente > 0)
          <div class="stat-delta delta-warn">À traiter</div>
        @endif
      </div>
    </a>
  </div>
  <div class="col-6 col-md-3">
    <a href="{{ route('admin.inscriptions.index', ['statut' => 'validee']) }}" class="stat-card anim-up delay-2 d-flex">
      <div class="stat-icon" style="background:rgba(16,185,129,.1)">
        <i class="bi bi-check-circle-fill" style="color:#10B981"></i>
      </div>
      <div>
        <div class="stat-val">{{ $validees }}</div>
        <div class="stat-label">Validées</div>
        <div class="stat-delta delta-ok">
          @if($totalInscriptions > 0)
            {{ round(($validees / $totalInscriptions) * 100) }}% du total
          @endif
        </div>
      </div>
    </a>
  </div>
  <div class="col-6 col-md-3">
    <a href="{{ route('admin.eleves.index') }}" class="stat-card anim-up delay-3 d-flex">
      <div class="stat-icon" style="background:rgba(124,58,237,.1)">
        <i class="bi bi-people-fill" style="color:#7C3AED"></i>
      </div>
      <div>
        <div class="stat-val">{{ $totalEleves }}</div>
        <div class="stat-label">Élèves enregistrés</div>
        <div class="stat-delta" style="color:#7C3AED">{{ $totalParents }} parent(s)</div>
      </div>
    </a>
  </div>
</div>

{{-- ── LIGNE PRINCIPALE ── --}}
<div class="row g-4">

  {{-- GAUCHE : dernières inscriptions + graphe --}}
  <div class="col-lg-8">

    {{-- Graphe 7 jours --}}
    <div class="card-gu mb-4 anim-up">
      <div class="card-gu-header">
        <h5><i class="bi bi-bar-chart-fill"></i> Inscriptions — 7 derniers jours</h5>
      </div>
      <div class="card-gu-body">
@php
  $vals   = array_column($graphData, 'total');
  $maxVal = count($vals) > 0 ? max(max($vals), 1) : 1;
@endphp        <div class="bar-chart">
          @foreach($graphData as $jour)
            @php $hauteur = max(4, round(($jour['total'] / $maxVal) * 100)); @endphp
            <div class="bar-item">
              <div class="bar" style="height:{{ $hauteur }}px">
                @if($jour['total'] > 0)
                  <span class="bar-val">{{ $jour['total'] }}</span>
                @endif
              </div>
              <span class="bar-label">{{ $jour['date'] }}</span>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- Dernières inscriptions --}}
    <div class="card-gu anim-up">
      <div class="card-gu-header">
        <h5><i class="bi bi-clock-history"></i> Dernières inscriptions</h5>
        <a href="{{ route('admin.inscriptions.index') }}" class="link-sm">
          Tout voir <i class="bi bi-arrow-right"></i>
        </a>
      </div>
      <div style="overflow-x:auto">
        <table class="inscr-table">
          <thead>
            <tr>
              <th>Élève</th>
              <th>N° Dossier</th>
              <th>Niveau</th>
              <th>Statut</th>
              <th>Date</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @forelse($dernieresInscriptions as $insc)
              <tr>
                <td>
                  <div class="eleve-mini">
                    <div class="eleve-mini-avatar">
                      @if($insc->eleve->photo)
                        <img src="{{ asset('storage/'.$insc->eleve->photo) }}" alt=""/>
                      @else
                        {{ strtoupper(substr($insc->eleve->prenom, 0, 1)) }}
                      @endif
                    </div>
                    <div>
                      <div class="eleve-mini-name">{{ $insc->eleve->prenom }} {{ $insc->eleve->nom }}</div>
                      <div class="eleve-mini-sub">{{ $insc->eleve->parent->prenom ?? '' }} {{ $insc->eleve->parent->nom ?? '' }}</div>
                    </div>
                  </div>
                </td>
                <td><span class="numero-badge">{{ $insc->numero_dossier }}</span></td>
                <td style="font-size:.83rem">{{ $insc->eleve->niveau_souhaite }}</td>
                <td>
                  @if($insc->statut === 'validee')
                    <span class="badge-gu badge-validee"><i class="bi bi-check2-circle"></i> Validée</span>
                  @elseif($insc->statut === 'en_attente')
                    <span class="badge-gu badge-attente"><i class="bi bi-hourglass-split"></i> En attente</span>
                  @else
                    <span class="badge-gu badge-refusee"><i class="bi bi-x-circle"></i> Refusée</span>
                  @endif
                </td>
                <td style="font-size:.8rem;color:#94A3B8">{{ $insc->created_at->diffForHumans() }}</td>
                <td>
                  <a href="{{ route('admin.inscriptions.show', $insc->id) }}"
                     class="btn-outline-gu" style="padding:.3rem .7rem;font-size:.78rem">
                    <i class="bi bi-eye"></i>
                  </a>
                </td>
              </tr>
            @empty
              <tr><td colspan="6" class="text-center py-4" style="color:#94A3B8;font-size:.88rem">Aucune inscription pour le moment.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>

  {{-- DROITE : classes + actions rapides --}}
  <div class="col-lg-4">

    {{-- Taux remplissage classes --}}
    <div class="card-gu mb-4 anim-up">
      <div class="card-gu-header">
        <h5><i class="bi bi-collection-fill"></i> Remplissage des classes</h5>
        <a href="{{ route('admin.classes.index') }}" class="link-sm">Gérer</a>
      </div>
      <div class="card-gu-body">
        @forelse($classes as $classe)
          @php
            $pct   = $classe->capacite_max > 0
                ? round(($classe->nb_eleves / $classe->capacite_max) * 100)
                : 0;
            $color = $pct >= 90 ? 'fill-red' : ($pct >= 70 ? 'fill-orange' : 'fill-green');
          @endphp
          <div class="classe-row">
            <div class="classe-nom">{{ $classe->nom }}</div>
            <div class="classe-bar-wrap">
              <div class="classe-bar-fill {{ $color }}" style="width:{{ $pct }}%"></div>
            </div>
            <div class="classe-pct" style="color:{{ $pct >= 90 ? '#F43F5E' : ($pct >= 70 ? '#F59E0B' : '#10B981') }}">
              {{ $pct }}%
            </div>
          </div>
        @empty
          <div class="empty-state">
            <i class="bi bi-collection"></i>
            <p>Aucune classe créée.</p>
            <a href="{{ route('admin.classes.create') }}" class="btn-primary-gu mt-2" style="font-size:.82rem;padding:.4rem .9rem">
              <i class="bi bi-plus"></i> Créer une classe
            </a>
          </div>
        @endforelse
      </div>
    </div>

    {{-- Actions rapides --}}
    <div class="card-gu anim-up">
      <div class="card-gu-header">
        <h5><i class="bi bi-lightning-fill"></i> Actions rapides</h5>
      </div>
      <div class="card-gu-body">

        <a href="{{ route('admin.inscriptions.index', ['statut' => 'en_attente']) }}" class="quick-action">
          <div class="qa-icon" style="background:rgba(245,158,11,.1)">
            <i class="bi bi-hourglass-split" style="color:#F59E0B"></i>
          </div>
          <div class="qa-text">
            <strong>Traiter les dossiers</strong>
            <span>{{ $enAttente }} en attente</span>
          </div>
          <i class="bi bi-chevron-right arrow"></i>
        </a>

        <a href="{{ route('admin.classes.create') }}" class="quick-action">
          <div class="qa-icon" style="background:rgba(37,99,235,.1)">
            <i class="bi bi-plus-circle" style="color:#2563EB"></i>
          </div>
          <div class="qa-text">
            <strong>Créer une classe</strong>
            <span>{{ $totalClasses }} classe(s) active(s)</span>
          </div>
          <i class="bi bi-chevron-right arrow"></i>
        </a>

        <a href="{{ route('admin.eleves.index') }}" class="quick-action">
          <div class="qa-icon" style="background:rgba(124,58,237,.1)">
            <i class="bi bi-people" style="color:#7C3AED"></i>
          </div>
          <div class="qa-text">
            <strong>Voir les élèves</strong>
            <span>{{ $totalEleves }} élève(s)</span>
          </div>
          <i class="bi bi-chevron-right arrow"></i>
        </a>

        <a href="{{ route('admin.notifications.index') }}" class="quick-action">
          <div class="qa-icon" style="background:rgba(16,185,129,.1)">
            <i class="bi bi-bell" style="color:#10B981"></i>
          </div>
          <div class="qa-text">
            <strong>Envoyer une notification</strong>
            <span>Alertes aux parents</span>
          </div>
          <i class="bi bi-chevron-right arrow"></i>
        </a>

      </div>
    </div>

  </div>
</div>

@endsection