@extends('layouts.app')
@section('title', 'Modifier ' . $classe->nom . ' — Admin')
@section('page_title', 'Modifier la classe')
@section('page_subtitle', $classe->nom . ' · ' . $classe->niveau)

@section('content')

@if(session('error'))
  <div class="alert-gu alert-danger mb-3"><i class="bi bi-x-circle-fill"></i> {{ session('error') }}</div>
@endif

<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="card-gu anim-up">
      <div class="card-gu-header">
        <h5><i class="bi bi-pencil"></i> Modifier la classe {{ $classe->nom }}</h5>
      </div>
      <div class="card-gu-body">

        <form action="{{ route('admin.classes.update', $classe->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="row g-3">

            {{-- Nom --}}
            <div class="col-md-7">
              <label class="form-label-gu">Nom de la classe <span class="text-danger">*</span></label>
              <input type="text" name="nom"
                     class="form-control-gu @error('nom') is-invalid @enderror"
                     value="{{ old('nom', $classe->nom) }}"
                     placeholder="ex: CP1-A, CE2-B, GS…"/>
              @error('nom')<div class="invalid-msg">{{ $message }}</div>@enderror
            </div>

            {{-- Niveau --}}
            <div class="col-md-5">
              <label class="form-label-gu">Niveau <span class="text-danger">*</span></label>
              <select name="niveau" class="form-select-gu @error('niveau') is-invalid @enderror">
                <option value="">Sélectionner…</option>
                <optgroup label="Maternelle">
                  @foreach(['Petite Section (PS)','Moyenne Section (MS)','Grande Section (GS)'] as $n)
                    <option value="{{ $n }}"
                      {{ old('niveau', $classe->niveau) === $n ? 'selected' : '' }}>
                      {{ $n }}
                    </option>
                  @endforeach
                </optgroup>
                <optgroup label="Primaire">
                  @foreach(['CP1 — 1ère année','CP2 — 2ème année','CE1 — 3ème année','CE2 — 4ème année','CM1 — 5ème année','CM2 — 6ème année'] as $n)
                    <option value="{{ $n }}"
                      {{ old('niveau', $classe->niveau) === $n ? 'selected' : '' }}>
                      {{ $n }}
                    </option>
                  @endforeach
                </optgroup>
              </select>
              @error('niveau')<div class="invalid-msg">{{ $message }}</div>@enderror
            </div>

            {{-- Capacité --}}
            <div class="col-md-5">
              <label class="form-label-gu">Capacité maximale <span class="text-danger">*</span></label>
              <input type="number" name="capacite_max" min="1" max="100"
                     class="form-control-gu @error('capacite_max') is-invalid @enderror"
                     value="{{ old('capacite_max', $classe->capacite_max) }}"/>
              @error('capacite_max')<div class="invalid-msg">{{ $message }}</div>@enderror
              @php
                $nbActuels = $classe->inscriptions()->where('statut','validee')->count();
              @endphp
              @if($nbActuels > 0)
                <div style="font-size:.76rem;color:#64748B;margin-top:.3rem">
                  <i class="bi bi-info-circle me-1"></i>
                  {{ $nbActuels }} élève(s) actuellement affecté(s)
                </div>
              @endif
            </div>

            {{-- Enseignant --}}
            <div class="col-md-7">
              <label class="form-label-gu">Enseignant(e) responsable</label>
              <input type="text" name="enseignant_responsable"
                     class="form-control-gu"
                     value="{{ old('enseignant_responsable', $classe->enseignant_responsable) }}"
                     placeholder="Nom de l'enseignant(e) — optionnel"/>
            </div>

            {{-- Active --}}
            <div class="col-12">
              <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:10px;padding:.9rem 1.1rem;display:flex;align-items:center;gap:.9rem">
                <div class="form-check mb-0">
                  <input type="checkbox" class="form-check-input" name="active" id="active"
                         value="1" {{ old('active', $classe->active) ? 'checked' : '' }}/>
                  <label class="form-check-label fw-semibold" for="active" style="font-size:.88rem;color:#0D1B2A">
                    Classe active
                  </label>
                </div>
                <span style="font-size:.8rem;color:#64748B">
                  Une classe inactive n'apparaît pas dans les affectations.
                </span>
              </div>
            </div>

          </div>

          {{-- Boutons --}}
          <div class="d-flex justify-content-between align-items-center mt-4 pt-3"
               style="border-top:1px solid #E2E8F0">
            <a href="{{ route('admin.classes.index') }}" class="btn-outline-gu">
              <i class="bi bi-arrow-left"></i> Annuler
            </a>
            <button type="submit" class="btn-primary-gu">
              <i class="bi bi-check-lg"></i> Enregistrer les modifications
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

@endsection