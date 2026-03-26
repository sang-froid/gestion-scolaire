@extends('layouts.app')

@section('title', 'Mes notifications - Gestion Scolaire')
@section('page_title', 'Notifications')
@section('page_subtitle', $nonLues . ' non lue(s) sur ' . $total . ' au total')

@push('styles')
<style>
  /* ── Header ── */
  .notif-header {
    background: linear-gradient(115deg, #0D1B2A, #1A3A5C);
    border-radius: 14px; padding: 1.6rem 2rem;
    margin-bottom: 1.8rem; position: relative; overflow: hidden;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 1rem;
  }
  .notif-header::after {
    content:''; position:absolute; right:-40px; top:-40px;
    width:180px; height:180px; border-radius:50%;
    background:rgba(56,189,248,.06); pointer-events:none;
  }
  .notif-header h2 { font-family:'Playfair Display',serif; color:#fff; font-size:1.35rem; margin:0 0 .2rem; }
  .notif-header p  { color:rgba(255,255,255,.55); font-size:.87rem; margin:0; }

  .notif-stat {
    background:rgba(255,255,255,.1); border-radius:10px;
    padding:.65rem 1.1rem; text-align:center;
    border:1px solid rgba(255,255,255,.12); min-width:80px;
    position:relative; z-index:1;
  }
  .notif-stat .val { font-family:'Playfair Display',serif; font-size:1.55rem; color:#fff; line-height:1; }
  .notif-stat .lbl { color:rgba(255,255,255,.45); font-size:.7rem; margin-top:.15rem; }

  /* ── Filtres ── */
  .filter-bar { display:flex; align-items:center; gap:.5rem; margin-bottom:1.4rem; flex-wrap:wrap; }
  .filter-chip {
    display:inline-flex; align-items:center; gap:.4rem;
    padding:.38rem .9rem; border-radius:20px;
    font-size:.82rem; font-weight:600;
    border:1.5px solid #E2E8F0; background:#fff; color:#64748B;
    text-decoration:none; transition:all .2s;
  }
  .filter-chip:hover         { border-color:#2563EB; color:#2563EB; background:#EFF6FF; }
  .filter-chip.active        { border-color:#2563EB; color:#2563EB; background:#EFF6FF; }
  .filter-chip.fc-pay.active { border-color:#F59E0B; color:#B45309; background:rgba(245,158,11,.08); }
  .filter-chip.fc-alr.active { border-color:#F43F5E; color:#9F1239; background:rgba(244,63,94,.07); }
  .chip-count {
    display:inline-flex; align-items:center; justify-content:center;
    width:18px; height:18px; border-radius:50%;
    background:#2563EB; color:#fff; font-size:.68rem; font-weight:700;
  }
  .fc-pay  .chip-count { background:#F59E0B; }
  .fc-alr  .chip-count { background:#F43F5E; }

  .btn-tout-lire {
    display:inline-flex; align-items:center; gap:.4rem;
    padding:.38rem .9rem; border-radius:20px; font-size:.82rem;
    font-weight:600; background:#EFF6FF; color:#2563EB;
    border:1.5px solid rgba(37,99,235,.2);
    cursor:pointer; transition:all .2s; text-decoration:none;
  }
  .btn-tout-lire:hover { background:#2563EB; color:#fff; border-color:#2563EB; }

  /* ── Carte notification ── */
  .notif-card {
    background:#fff; border-radius:13px;
    border:1px solid #E2E8F0;
    box-shadow:0 3px 16px rgba(13,27,42,.06);
    margin-bottom:.75rem; overflow:hidden;
    transition:box-shadow .2s, transform .15s;
    cursor: pointer;
  }
  .notif-card:hover { box-shadow:0 6px 22px rgba(13,27,42,.1); transform:translateY(-1px); }
  .notif-card.unread             { border-left:4px solid #2563EB; background:#FAFCFF; }
  .notif-card.unread.tp-paiement { border-left-color:#F59E0B; }
  .notif-card.unread.tp-urgente  { border-left-color:#F43F5E; }

  .notif-inner { display:flex; align-items:flex-start; gap:1rem; padding:1.1rem 1.4rem; }

  .notif-ico {
    width:44px; height:44px; border-radius:11px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:1.15rem;
  }
  .ico-pay   { background:rgba(245,158,11,.12); color:#F59E0B; }
  .ico-info  { background:rgba(37,99,235,.1);   color:#2563EB; }
  .ico-alert { background:rgba(244,63,94,.1);   color:#F43F5E; }

  .notif-body { flex:1; min-width:0; }

  .notif-sujet {
    font-size:.9rem; font-weight:700; color:#0D1B2A; margin:0 0 .25rem;
    display:flex; align-items:center; gap:.5rem;
  }
  .unread .notif-sujet::before {
    content:''; width:8px; height:8px; border-radius:50%;
    background:#2563EB; flex-shrink:0; display:inline-block;
  }
  .unread.tp-paiement .notif-sujet::before { background:#F59E0B; }
  .unread.tp-urgente  .notif-sujet::before { background:#F43F5E; }

  .notif-msg  { font-size:.84rem; color:#64748B; margin:0 0 .45rem; line-height:1.55; }

  .notif-meta { display:flex; align-items:center; gap:.6rem; flex-wrap:wrap; }
  .notif-date { font-size:.75rem; color:#94A3B8; display:flex; align-items:center; gap:.3rem; }
  .notif-tag  {
    font-size:.73rem; font-weight:600; border-radius:20px; padding:.1rem .6rem;
  }
  .tag-pay   { background:rgba(245,158,11,.1); color:#B45309; border:1px solid rgba(245,158,11,.2); }
  .tag-alert { background:rgba(244,63,94,.08); color:#9F1239; border:1px solid rgba(244,63,94,.18); }
  .tag-info  { background:rgba(37,99,235,.08); color:#1E40AF; border:1px solid rgba(37,99,235,.18); }
  .tag-eleve { background:#EFF6FF; color:#2563EB; border:1px solid rgba(37,99,235,.18); }

  .badge-lue {
    font-size:.72rem; font-weight:600; background:#F1F5F9; color:#94A3B8;
    border-radius:20px; padding:.12rem .6rem;
    flex-shrink:0; align-self:flex-start; margin-top:.15rem;
  }

  /* ── Empty ── */
  .notif-empty { text-align:center; padding:3.5rem 2rem; color:#64748B; }
  .notif-empty i { font-size:3rem; opacity:.2; display:block; margin-bottom:.8rem; }
  .notif-empty p { font-size:.9rem; margin:0; }
</style>
@endpush

@section('content')

{{-- HEADER --}}
<div class="notif-header anim-up">
  <div>
    <h2><i class="bi bi-bell-fill me-2" style="color:#38BDF8"></i>Mes notifications</h2>
    <p>
      @if($nonLues > 0)
        <span style="color:#38BDF8;font-weight:600">{{ $nonLues }} non lue(s)</span> ·
      @endif
      {{ $total }} notification(s) au total
    </p>
  </div>
  <div class="d-flex gap-2 flex-wrap">
    <div class="notif-stat">
      <div class="val">{{ $total }}</div>
      <div class="lbl">Total</div>
    </div>
    <div class="notif-stat" style="border-color:rgba(56,189,248,.3)">
      <div class="val" style="color:#38BDF8" data-nonlues="{{ $nonLues }}">{{ $nonLues }}</div>
      <div class="lbl">Non lues</div>
    </div>
    <div class="notif-stat" style="border-color:rgba(245,158,11,.3)">
      <div class="val" style="color:#F59E0B">{{ $paiement }}</div>
      <div class="lbl">Paiement</div>
    </div>
    <div class="notif-stat" style="border-color:rgba(244,63,94,.3)">
      <div class="val" style="color:#F43F5E">{{ $urgentes }}</div>
      <div class="lbl">Urgentes</div>
    </div>
  </div>
</div>

{{-- FILTRES --}}
<div class="filter-bar">
  <a href="{{ route('parent.notifications.index') }}"
     class="filter-chip {{ $filtre === 'toutes' ? 'active' : '' }}">
    <i class="bi bi-grid-1x2"></i> Toutes
    <span class="chip-count">{{ $total }}</span>
  </a>
  <a href="{{ route('parent.notifications.index', ['type' => 'paiement']) }}"
     class="filter-chip fc-pay {{ $filtre === 'paiement' ? 'active' : '' }}">
    <i class="bi bi-wallet2"></i> Paiement
    <span class="chip-count">{{ $paiement }}</span>
  </a>
  <a href="{{ route('parent.notifications.index', ['type' => 'urgente']) }}"
     class="filter-chip fc-alr {{ $filtre === 'urgente' ? 'active' : '' }}">
    <i class="bi bi-exclamation-triangle"></i> Urgentes
    <span class="chip-count">{{ $urgentes }}</span>
  </a>
  <a href="{{ route('parent.notifications.index', ['type' => 'info']) }}"
     class="filter-chip {{ $filtre === 'info' ? 'active' : '' }}">
    <i class="bi bi-info-circle"></i> Informations
  </a>

  @if($nonLues > 0)
    <form action="{{ route('parent.notifications.toutlire') }}" method="POST" class="ms-auto">
      @csrf
      <button type="submit" class="btn-tout-lire">
        <i class="bi bi-check2-all"></i> Tout marquer comme lu
      </button>
    </form>
  @endif
</div>

{{-- LISTE --}}
@php
  $filtrees = $filtre === 'toutes'
      ? $notifications
      : $notifications->where('type', $filtre);
@endphp

@if($filtrees->isEmpty())
  <div class="notif-card">
    <div class="notif-empty">
      <i class="bi bi-bell-slash"></i>
      <p>Aucune notification
        @if($filtre !== 'toutes') de type "{{ $filtre }}" @endif
        pour le moment.
      </p>
    </div>
  </div>
@else
  @foreach($filtrees as $notif)
    @php $estLue = !is_null($notif->lu_le); @endphp

    <div class="notif-card {{ !$estLue ? 'unread tp-'.$notif->type : '' }} anim-up"
         id="notif-{{ $notif->id }}"
         data-id="{{ $notif->id }}"
         onclick="marquerLue({{ $notif->id }})">
      <div class="notif-inner">

        {{-- Icône --}}
        <div class="notif-ico
          @if($notif->type === 'paiement') ico-pay
          @elseif($notif->type === 'urgente') ico-alert
          @else ico-info @endif">
          <i class="bi
            @if($notif->type === 'paiement') bi-wallet2
            @elseif($notif->type === 'urgente') bi-exclamation-triangle-fill
            @else bi-info-circle-fill @endif">
          </i>
        </div>

        {{-- Contenu --}}
        <div class="notif-body">
          <div class="notif-sujet">{{ $notif->sujet }}</div>
          <div class="notif-msg">{{ $notif->message }}</div>
          <div class="notif-meta">
            <span class="notif-date">
              <i class="bi bi-clock"></i>
              {{ $notif->created_at->isoFormat('D MMM YYYY [à] HH[h]mm') }}
              &nbsp;·&nbsp; {{ $notif->created_at->diffForHumans() }}
            </span>
            {{-- Tag type --}}
            <span class="notif-tag
              @if($notif->type === 'paiement') tag-pay
              @elseif($notif->type === 'urgente') tag-alert
              @else tag-info @endif">
              @if($notif->type === 'paiement') Paiement
              @elseif($notif->type === 'urgente')  Urgent
              @else  Info @endif
            </span>
            {{-- Tag élève concerné --}}
            @if($notif->eleve)
              <span class="notif-tag tag-eleve">
                <i class="bi bi-person me-1"></i>
                {{ $notif->eleve->prenom }} {{ $notif->eleve->nom }}
              </span>
            @endif
          </div>
        </div>

        {{-- Badge lu --}}
        @if($estLue)
          <span class="badge-lue"><i class="bi bi-check2-all me-1"></i>Lue</span>
        @endif

      </div>
    </div>

  @endforeach
@endif

@endsection

@push('scripts')
<script>
  function marquerLue(id) {
    const card = document.getElementById('notif-' + id);
    if (!card || !card.classList.contains('unread')) return;

    // Mise à jour visuelle
    card.classList.remove('unread', 'tp-paiement', 'tp-urgente', 'tp-info');

    const inner = card.querySelector('.notif-inner');
    if (inner && !inner.querySelector('.badge-lue')) {
      const badge = document.createElement('span');
      badge.className = 'badge-lue';
      badge.innerHTML = '<i class="bi bi-check2-all me-1"></i>Lue';
      inner.appendChild(badge);
    }

    // Décrémenter compteur
    const compteur = document.querySelector('[data-nonlues]');
    if (compteur) {
      let val = parseInt(compteur.dataset.nonlues) - 1;
      if (val < 0) val = 0;
      compteur.dataset.nonlues = val;
      compteur.textContent = val;
    }

    // AJAX
    fetch(`/parent/notifications/${id}/lire`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json'
      }
    });
  }
</script>
@endpush
