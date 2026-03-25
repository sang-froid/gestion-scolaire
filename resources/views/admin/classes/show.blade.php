@extends('layouts.app')
@section('title', 'Classe ' . $classe->nom . ' — Admin')
@section('page_title', 'Classe ' . $classe->nom)
@section('page_subtitle', $classe->niveau . ' · ' . ($classe->inscriptions->count()) . ' élève(s)')

@section('content')

{{-- Header --}}
<div style="background:linear-gradient(115deg,#0D1B2A,#1A3A5C);border-radius:14px;padding:1.5rem 2rem;margin-bottom:1.8rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;position:relative;overflow:hidden" class="anim-up">
  <div style="position:absolute;right:-40px;top:-40px;width:180px;height:180px;border-radius:50%;background:rgba(56,189,248,.06)"></div>
  <div>
    <h2 style="font-family:'Playfair Display',serif;color:#fff;font-size:1.3rem;margin:0 0 .2rem">
      <i class="bi bi-collection me-2" style="color:#38BDF8"></i>{{ $classe->nom }}
    </h2>
    <p style="color:rgba(255,255,255,.5);font-size:.84rem;margin:0">
      {{ $classe->niveau }} · {{ $classe->annee_scolaire }}
      @if($classe->enseignant_responsable)
        · Enseignant : {{ $classe->enseignant_responsable }}
      @endif
    </p>
  </div>
  <div class="d-flex gap-2" style="position:relative;z-index:1">
    @php
      $nb  = $classe->inscriptions->count();
      $pct = $classe->capacite_max > 0 ? round(($nb / $classe->capacite_max) * 100) : 0;
    @endphp
    <div style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:.6rem 1.1rem;text-align:center">
      <div style="font-family:'Playfair Display',serif;font-size:1.5rem;color:#fff;line-height:1">{{ $nb }}/{{ $classe->capacite_max }}</div>
      <div style="color:rgba(255,255,255,.45);font-size:.72rem">élèves</div>
    </div>
    <div style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:.6rem 1.1rem;text-align:center">
      <div style="font-family:'Playfair Display',serif;font-size:1.5rem;color:{{ $pct >= 90 ? '#F43F5E' : ($pct >= 70 ? '#F59E0B' : '#34D399') }};line-height:1">{{ $pct }}%</div>
      <div style="color:rgba(255,255,255,.45);font-size:.72rem">remplie</div>
    </div>
    <a href="{{ route('admin.classes.edit', $classe->id) }}"
       class="btn-outline-gu align-self-center" style="border-color:rgba(255,255,255,.2);color:#fff;padding:.5rem 1rem;font-size:.84rem">
      <i class="bi bi-pencil"></i> Modifier
    </a>
    <a href="{{ route('admin.classes.index') }}"
       class="btn-outline-gu align-self-center" style="border-color:rgba(255,255,255,.2);color:#fff;padding:.5rem 1rem;font-size:.84rem">
      <i class="bi bi-arrow-left"></i> Retour
    </a>
  </div>
</div>

{{-- Liste élèves --}}
<div class="card-gu anim-up">
  <div class="card-gu-header">
    <h5><i class="bi bi-people-fill"></i> Élèves de la classe</h5>
  </div>
  <div style="overflow-x:auto">
    <table style="width:100%;border-collapse:collapse">
      <thead>
        <tr>
          <th style="background:#F8FAFC;padding:.7rem 1rem;text-align:left;font-size:.75rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.05em;border-bottom:2px solid #E2E8F0">Élève</th>
          <th style="background:#F8FAFC;padding:.7rem 1rem;text-align:left;font-size:.75rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.05em;border-bottom:2px solid #E2E8F0">Date de naissance</th>
          <th style="background:#F8FAFC;padding:.7rem 1rem;text-align:left;font-size:.75rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.05em;border-bottom:2px solid #E2E8F0">Sexe</th>
          <th style="background:#F8FAFC;padding:.7rem 1rem;text-align:left;font-size:.75rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.05em;border-bottom:2px solid #E2E8F0">Parent</th>
          <th style="background:#F8FAFC;padding:.7rem 1rem;text-align:left;font-size:.75rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.05em;border-bottom:2px solid #E2E8F0">N° Dossier</th>
          <th style="background:#F8FAFC;padding:.7rem 1rem;text-align:left;font-size:.75rem;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.05em;border-bottom:2px solid #E2E8F0"></th>
        </tr>
      </thead>
      <tbody>
        @forelse($classe->inscriptions as $insc)
          <tr style="border-bottom:1px solid #F1F5F9">
            <td style="padding:.8rem 1rem;vertical-align:middle">
              <div style="display:flex;align-items:center;gap:.7rem">
                <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#1A3A5C,#2563EB);display:flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;color:#fff;overflow:hidden;flex-shrink:0">
                  @if($insc->eleve->photo)
                    <img src="{{ asset('storage/'.$insc->eleve->photo) }}" style="width:100%;height:100%;object-fit:cover" alt=""/>
                  @else
                    {{ strtoupper(substr($insc->eleve->prenom, 0, 1)) }}
                  @endif
                </div>
                <div>
                  <div style="font-weight:600;font-size:.86rem">{{ $insc->eleve->prenom }} {{ $insc->eleve->nom }}</div>
                </div>
              </div>
            </td>
            <td style="padding:.8rem 1rem;font-size:.84rem;color:#64748B">
              {{ \Carbon\Carbon::parse($insc->eleve->date_naissance)->isoFormat('D MMM YYYY') }}
            </td>
            <td style="padding:.8rem 1rem;font-size:.84rem">
              {{ $insc->eleve->sexe === 'M' ? '♂ Garçon' : '♀ Fille' }}
            </td>
            <td style="padding:.8rem 1rem;font-size:.83rem;color:#64748B">
              {{ $insc->eleve->parent->prenom ?? '' }} {{ $insc->eleve->parent->nom ?? '' }}
            </td>
            <td style="padding:.8rem 1rem">
              <span style="font-family:monospace;font-size:.76rem;color:#2563EB;background:rgba(37,99,235,.08);border:1px solid rgba(37,99,235,.15);border-radius:6px;padding:.1rem .5rem">
                {{ $insc->numero_dossier }}
              </span>
            </td>
            <td style="padding:.8rem 1rem">
              <a href="{{ route('admin.inscriptions.show', $insc->id) }}"
                 class="btn-outline-gu" style="padding:.3rem .7rem;font-size:.78rem">
                <i class="bi bi-eye"></i>
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" style="text-align:center;padding:3rem;color:#94A3B8;font-size:.88rem">
              <i class="bi bi-people d-block mb-2" style="font-size:2rem;opacity:.2"></i>
              Aucun élève affecté à cette classe.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
