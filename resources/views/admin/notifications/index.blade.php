@extends('layouts.app')

@section('title', 'Notifications — Admin EduGest')
@section('page_title', 'Notifications & Alertes')
@section('page_subtitle', 'Envoyer des messages aux parents')

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

  /* ── Stat chips ── */
  .stat-chip { display:inline-flex; align-items:center; gap:.4rem; background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.15); border-radius:20px; padding:.35rem .9rem; font-size:.82rem; font-weight:600; color:#fff; position:relative; z-index:1; }

  /* ── Formulaire envoi ── */
  .send-form-card { background:#fff; border-radius:14px; border:1px solid #E2E8F0; box-shadow:0 4px 20px rgba(13,27,42,.07); overflow:hidden; margin-bottom:1.5rem; }
  .send-form-head { background:linear-gradient(110deg,#4C1D95,#7C3AED); padding:1rem 1.5rem; display:flex; align-items:center; gap:.8rem; }
  .send-form-head h5 { font-family:'Playfair Display',serif; color:#fff; font-size:1rem; margin:0; }
  .send-form-head i  { color:#C4B5FD; font-size:1.1rem; }
  .send-form-body { padding:1.5rem; }

  /* ── Type selector ── */
  .type-selector { display:flex; gap:.7rem; margin-bottom:1.2rem; flex-wrap:wrap; }
  .type-btn {
    flex:1; min-width:100px; padding:.75rem .5rem; border-radius:11px;
    border:2px solid #E2E8F0; background:#fff; cursor:pointer;
    text-align:center; transition:all .2s; position:relative;
  }
  .type-btn:hover { border-color:#7C3AED; background:rgba(124,58,237,.04); }
  .type-btn.selected-info    { border-color:#2563EB; background:rgba(37,99,235,.06); }
  .type-btn.selected-paiement{ border-color:#F59E0B; background:rgba(245,158,11,.06); }
  .type-btn.selected-urgente { border-color:#F43F5E; background:rgba(244,63,94,.06); }
  .type-btn input { position:absolute; opacity:0; width:0; height:0; }
  .type-btn .tb-icon { font-size:1.4rem; display:block; margin-bottom:.3rem; }
  .type-btn .tb-label { font-size:.8rem; font-weight:700; color:#0D1B2A; }
  .type-btn .tb-sub   { font-size:.72rem; color:#64748B; }

  /* ── Destinataire selector ── */
  .dest-selector { display:flex; gap:.5rem; margin-bottom:1rem; flex-wrap:wrap; }
  .dest-chip {
    padding:.42rem 1rem; border-radius:20px; font-size:.83rem; font-weight:600;
    border:1.5px solid #E2E8F0; background:#fff; color:#64748B;
    cursor:pointer; transition:all .2s; display:flex; align-items:center; gap:.4rem;
  }
  .dest-chip:hover { border-color:#7C3AED; color:#7C3AED; background:rgba(124,58,237,.05); }
  .dest-chip.active { border-color:#7C3AED; color:#7C3AED; background:rgba(124,58,237,.07); }
  .dest-chip input { display:none; }

  /* ── Inputs ── */
  .form-label-gu { font-size:.82rem; font-weight:600; color:#0D1B2A; margin-bottom:.4rem; display:block; }
  .form-control-gu { border:1.5px solid #E2E8F0; border-radius:10px; padding:.6rem 1rem; font-size:.88rem; width:100%; transition:border-color .2s; }
  .form-control-gu:focus { border-color:#7C3AED; outline:none; box-shadow:0 0 0 3px rgba(124,58,237,.08); }
  .form-select-gu { border:1.5px solid #E2E8F0; border-radius:10px; padding:.6rem 1rem; font-size:.88rem; width:100%; background:#fff; transition:border-color .2s; }
  .form-select-gu:focus { border-color:#7C3AED; outline:none; }
  textarea.form-control-gu { resize:vertical; min-height:100px; }
  .invalid-msg { color:#F43F5E; font-size:.78rem; margin-top:.25rem; }

  /* Preview email */
  .preview-box {
    background:#F8FAFC; border:1px solid #E2E8F0; border-radius:10px;
    padding:1rem 1.2rem; margin-top:.8rem; display:none;
  }
  .preview-box.visible { display:block; }
  .preview-box .prev-sujet { font-weight:700; font-size:.9rem; color:#0D1B2A; margin-bottom:.3rem; }
  .preview-box .prev-msg   { font-size:.84rem; color:#64748B; line-height:1.6; }

  /* ── Historique ── */
  .notif-table { width:100%; border-collapse:collapse; }
  .notif-table th { background:#F8FAFC; padding:.7rem 1rem; text-align:left; font-size:.74rem; font-weight:700; color:#64748B; text-transform:uppercase; letter-spacing:.05em; border-bottom:2px solid #E2E8F0; white-space:nowrap; }
  .notif-table td { padding:.75rem 1rem; font-size:.84rem; color:#0D1B2A; border-bottom:1px solid #F1F5F9; vertical-align:middle; }
  .notif-table tr:last-child td { border:none; }
  .notif-table tr:hover td { background:#FAFBFD; }

  .notif-ico { width:34px; height:34px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:.95rem; flex-shrink:0; }
  .ico-pay   { background:rgba(245,158,11,.12); color:#F59E0B; }
  .ico-info  { background:rgba(37,99,235,.1);   color:#2563EB; }
  .ico-alert { background:rgba(244,63,94,.1);   color:#F43F5E; }

  .email-sent   { color:#10B981; font-size:.78rem; font-weight:600; }
  .email-nosent { color:#94A3B8; font-size:.78rem; }

  .pagi-wrap { display:flex; justify-content:space-between; align-items:center; padding:1rem 1.4rem; border-top:1px solid #E2E8F0; font-size:.83rem; color:#64748B; }

  /* Sous-section destinataire */
  .dest-options { display:none; margin-top:.8rem; }
  .dest-options.visible { display:block; }
</style>
@endpush

@section('content')

{{-- HEADER --}}
<div class="page-banner anim-up">
  <div>
    <h2><i class="bi bi-bell-fill me-2" style="color:#38BDF8"></i>Notifications & Alertes</h2>
    <p>Communiquez avec les parents en temps réel</p>
  </div>
  <div class="d-flex gap-2 flex-wrap" style="position:relative;z-index:1">
    <span class="stat-chip"><i class="bi bi-send"></i> {{ $stats['total'] }} envoyée(s)</span>
    <span class="stat-chip" style="border-color:rgba(245,158,11,.3)"><i class="bi bi-wallet2"></i> {{ $stats['paiement'] }} paiement</span>
    <span class="stat-chip" style="border-color:rgba(244,63,94,.3)"><i class="bi bi-exclamation-triangle"></i> {{ $stats['urgentes'] }} urgentes</span>
  </div>
</div>

{{-- ALERTES --}}
@if(session('success'))
  <div class="alert-gu alert-success mb-3"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
@endif
@if(session('error'))
  <div class="alert-gu alert-danger mb-3"><i class="bi bi-x-circle-fill"></i> {{ session('error') }}</div>
@endif
@if($errors->any())
  <div class="alert-gu alert-danger mb-3">
    <i class="bi bi-exclamation-circle-fill"></i>
    <div>
      @foreach($errors->all() as $err)
        <div>{{ $err }}</div>
      @endforeach
    </div>
  </div>
@endif

<div class="row g-4">

  {{-- GAUCHE : formulaire envoi --}}
  <div class="col-lg-5">

    <div class="send-form-card anim-up">
      <div class="send-form-head">
        <i class="bi bi-send-fill"></i>
        <h5>Envoyer une notification</h5>
      </div>
      <div class="send-form-body">

        <form action="{{ route('admin.notifications.send') }}" method="POST" id="notifForm">
          @csrf

          {{-- Type --}}
          <div class="mb-3">
            <label class="form-label-gu">Type de notification <span class="text-danger">*</span></label>
            <div class="type-selector" id="typeSelector">

              <label class="type-btn {{ old('type') === 'info' || !old('type') ? 'selected-info' : '' }}"
                     onclick="selectType('info', this)">
                <input type="radio" name="type" value="info"
                       {{ old('type', 'info') === 'info' ? 'checked' : '' }}/>
                <span class="tb-icon"></span>
                <span class="tb-label">Information</span>
                <span class="tb-sub">Annonce générale</span>
              </label>

              <label class="type-btn {{ old('type') === 'paiement' ? 'selected-paiement' : '' }}"
                     onclick="selectType('paiement', this)">
                <input type="radio" name="type" value="paiement"
                       {{ old('type') === 'paiement' ? 'checked' : '' }}/>
                <span class="tb-icon"></span>
                <span class="tb-label">Paiement</span>
                <span class="tb-sub">Rappel échéance</span>
              </label>

              <label class="type-btn {{ old('type') === 'urgente' ? 'selected-urgente' : '' }}"
                     onclick="selectType('urgente', this)">
                <input type="radio" name="type" value="urgente"
                       {{ old('type') === 'urgente' ? 'checked' : '' }}/>
                <span class="tb-icon"></span>
                <span class="tb-label">Urgente</span>
                <span class="tb-sub">Priorité haute</span>
              </label>

            </div>
          </div>

          {{-- Destinataires --}}
          <div class="mb-3">
            <label class="form-label-gu">Destinataires <span class="text-danger">*</span></label>
            <div class="dest-selector">

              <label class="dest-chip {{ old('destinataire', 'tous') === 'tous' ? 'active' : '' }}"
                     onclick="selectDest('tous', this)">
                <input type="radio" name="destinataire" value="tous"
                       {{ old('destinataire', 'tous') === 'tous' ? 'checked' : '' }}/>
                <i class="bi bi-people-fill"></i> Tous les parents
              </label>

              <label class="dest-chip {{ old('destinataire') === 'classe' ? 'active' : '' }}"
                     onclick="selectDest('classe', this)">
                <input type="radio" name="destinataire" value="classe"
                       {{ old('destinataire') === 'classe' ? 'checked' : '' }}/>
                <i class="bi bi-collection"></i> Par classe
              </label>

              <label class="dest-chip {{ old('destinataire') === 'parent' ? 'active' : '' }}"
                     onclick="selectDest('parent', this)">
                <input type="radio" name="destinataire" value="parent"
                       {{ old('destinataire') === 'parent' ? 'checked' : '' }}/>
                <i class="bi bi-person"></i> Un parent
              </label>

            </div>
            @error('destinataire')<div class="invalid-msg">{{ $message }}</div>@enderror

            {{-- Select classe --}}
            <div class="dest-options {{ old('destinataire') === 'classe' ? 'visible' : '' }}" id="opt-classe">
              <select name="classe_id" class="form-select-gu @error('classe_id') is-invalid @enderror">
                <option value="">Sélectionner une classe…</option>
                @foreach($classes as $c)
                  <option value="{{ $c->id }}" {{ old('classe_id') == $c->id ? 'selected' : '' }}>
                    {{ $c->nom }} — {{ $c->niveau }}
                  </option>
                @endforeach
              </select>
              @error('classe_id')<div class="invalid-msg">{{ $message }}</div>@enderror
            </div>

            {{-- Select parent --}}
            <div class="dest-options {{ old('destinataire') === 'parent' ? 'visible' : '' }}" id="opt-parent">
              <select name="parent_id" class="form-select-gu @error('parent_id') is-invalid @enderror">
                <option value="">Sélectionner un parent…</option>
                @foreach($parents as $p)
                  <option value="{{ $p->id }}" {{ old('parent_id') == $p->id ? 'selected' : '' }}>
                    {{ $p->civilite ?? '' }} {{ $p->prenom }} {{ $p->nom }}
                    — {{ $p->user->email }}
                  </option>
                @endforeach
              </select>
              @error('parent_id')<div class="invalid-msg">{{ $message }}</div>@enderror
            </div>
          </div>

          {{-- Sujet --}}
          <div class="mb-3">
            <label class="form-label-gu">Sujet <span class="text-danger">*</span></label>
            <input type="text" name="sujet"
                   class="form-control-gu @error('sujet') is-invalid @enderror"
                   value="{{ old('sujet') }}"
                   placeholder="Objet de la notification…"
                   oninput="updatePreview()"/>
            @error('sujet')<div class="invalid-msg">{{ $message }}</div>@enderror
          </div>

          {{-- Message --}}
          <div class="mb-3">
            <label class="form-label-gu">Message <span class="text-danger">*</span></label>
            <textarea name="message" rows="4"
                      class="form-control-gu @error('message') is-invalid @enderror"
                      placeholder="Rédigez votre message ici…"
                      oninput="updatePreview()">{{ old('message') }}</textarea>
            @error('message')<div class="invalid-msg">{{ $message }}</div>@enderror
          </div>

          {{-- Prévisualisation --}}
          <div class="preview-box {{ old('sujet') ? 'visible' : '' }}" id="previewBox">
            <div style="font-size:.72rem;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.5rem">
              <i class="bi bi-eye me-1"></i>Aperçu
            </div>
            <div class="prev-sujet" id="prevSujet">{{ old('sujet') }}</div>
            <div class="prev-msg"   id="prevMsg">{{ old('message') }}</div>
          </div>

          {{-- Submit --}}
          <div class="mt-3 pt-3" style="border-top:1px solid #E2E8F0">
            <button type="submit" class="btn-primary-gu w-100 justify-content-center"
                    style="background:linear-gradient(135deg,#7C3AED,#6D28D9);box-shadow:0 4px 16px rgba(124,58,237,.3);padding:.7rem">
              <i class="bi bi-send-fill"></i> Envoyer la notification
            </button>
            <p style="font-size:.76rem;color:#94A3B8;text-align:center;margin-top:.6rem">
              <i class="bi bi-info-circle me-1"></i>
              Types "Paiement" et "Urgente" déclenchent aussi un envoi par email.
            </p>
          </div>

        </form>
      </div>
    </div>

  </div>

  {{-- DROITE : historique --}}
  <div class="col-lg-7">

    <div class="card-gu anim-up">
      <div class="card-gu-header">
        <h5><i class="bi bi-clock-history"></i> Historique des notifications</h5>
      </div>
      <div style="overflow-x:auto">
        <table class="notif-table">
          <thead>
            <tr>
              <th>Type</th>
              <th>Sujet</th>
              <th>Destinataire</th>
              <th>Email</th>
              <th>Envoyée le</th>
              <th>Par</th>
            </tr>
          </thead>
          <tbody>
            @forelse($notifications as $notif)
              <tr>
                {{-- Type --}}
                <td>
                  <div class="d-flex align-items-center gap-2">
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
                    <span style="font-size:.76rem;font-weight:600;
                      @if($notif->type === 'paiement') color:#B45309
                      @elseif($notif->type === 'urgente') color:#9F1239
                      @else color:#1E40AF @endif">
                      {{ ucfirst($notif->type) }}
                    </span>
                  </div>
                </td>

                {{-- Sujet --}}
                <td>
                  <div style="font-weight:600;font-size:.85rem;color:#0D1B2A">
                    {{ Str::limit($notif->sujet, 40) }}
                  </div>
                  <div style="font-size:.76rem;color:#94A3B8">
                    {{ Str::limit($notif->message, 60) }}
                  </div>
                </td>

                {{-- Destinataire --}}
                <td style="font-size:.83rem">
                  @if($notif->parent)
                    <span style="font-weight:600">{{ $notif->parent->prenom }} {{ $notif->parent->nom }}</span>
                    <div style="font-size:.75rem;color:#94A3B8">{{ $notif->parent->user->email ?? '' }}</div>
                  @else
                    <span style="color:#94A3B8">—</span>
                  @endif
                </td>

                {{-- Email envoyé --}}
                <td>
                  @if($notif->email_envoye)
                    <span class="email-sent"><i class="bi bi-check2-all me-1"></i>Envoyé</span>
                    @if($notif->email_envoye_le)
                      <div style="font-size:.72rem;color:#94A3B8">{{ $notif->email_envoye_le->isoFormat('H[h]mm') }}</div>
                    @endif
                  @else
                    <span class="email-nosent"><i class="bi bi-dash me-1"></i>Non envoyé</span>
                  @endif
                </td>

                {{-- Date --}}
                <td style="font-size:.8rem;color:#64748B">
                  {{ $notif->created_at->isoFormat('D MMM YYYY') }}
                  <div style="font-size:.74rem;color:#94A3B8">{{ $notif->created_at->diffForHumans() }}</div>
                </td>

                {{-- Par --}}
                <td style="font-size:.82rem;color:#64748B">
                  {{ $notif->envoyeur?->name ?? 'Système' }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" style="text-align:center;padding:3rem;color:#94A3B8">
                  <i class="bi bi-bell-slash d-block mb-2" style="font-size:2rem;opacity:.2"></i>
                  Aucune notification envoyée pour le moment.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      @if($notifications->hasPages())
        <div class="pagi-wrap">
          <span>{{ $notifications->firstItem() }}–{{ $notifications->lastItem() }} sur {{ $notifications->total() }}</span>
          {{ $notifications->links() }}
        </div>
      @endif
    </div>

  </div>
</div>

@endsection

<script>
  // Sélection type
  function selectType(type, el) {
    document.querySelectorAll('.type-btn').forEach(b => {
      b.className = 'type-btn';
    });
    el.classList.add('selected-' + type);
  }

  // Sélection destinataire
  function selectDest(dest, el) {
    document.querySelectorAll('.dest-chip').forEach(c => c.classList.remove('active'));
    el.classList.add('active');
    document.querySelectorAll('.dest-options').forEach(o => o.classList.remove('visible'));
    if (dest !== 'tous') {
      document.getElementById('opt-' + dest)?.classList.add('visible');
    }
  }

  // Prévisualisation live
  function updatePreview() {
    const sujet = document.querySelector('[name="sujet"]').value;
    const msg   = document.querySelector('[name="message"]').value;
    const box   = document.getElementById('previewBox');
    if (sujet || msg) {
      box.classList.add('visible');
      document.getElementById('prevSujet').textContent = sujet || '—';
      document.getElementById('prevMsg').textContent   = msg   || '—';
    } else {
      box.classList.remove('visible');
    }
  }

  // Restaurer état si erreurs Laravel
  document.addEventListener('DOMContentLoaded', () => {
    const dest = document.querySelector('[name="destinataire"]:checked')?.value;
    if (dest && dest !== 'tous') {
      document.getElementById('opt-' + dest)?.classList.add('visible');
    }
    updatePreview();
  });
</script>