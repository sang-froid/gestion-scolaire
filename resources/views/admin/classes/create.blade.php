{{-- ============================================================
     resources/views/admin/classes/create.blade.php
     (identique pour edit — change juste le titre et l'action)
     ============================================================ --}}
@extends('layouts.app')
@section('title', isset($classe) ? 'Modifier ' . $classe->nom : 'Nouvelle classe')
@section('page_title', isset($classe) ? 'Modifier la classe' : 'Nouvelle classe')
@section('page_subtitle', isset($classe) ? $classe->nom : 'Créer une classe pour ' . config('app.annee_scolaire'))

@section('content')

@if(session('error'))
  <div class="alert-gu alert-danger mb-3"><i class="bi bi-x-circle-fill"></i> {{ session('error') }}</div>
@endif

<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="card-gu anim-up">
      <div class="card-gu-header">
        <h5>
          <i class="bi bi-{{ isset($classe) ? 'pencil' : 'plus-circle' }}"></i>
          {{ isset($classe) ? 'Modifier' : 'Créer' }} une classe
        </h5>
      </div>
      <div class="card-gu-body">
        <form action="{{ isset($classe) ? route('admin.classes.update', $classe->id) : route('admin.classes.store') }}"
              method="POST">
          @csrf
          @if(isset($classe)) @method('PUT') @endif

          <div class="row g-3">

            <div class="col-md-7">
              <label class="form-label-gu">Nom de la classe <span class="text-danger">*</span></label>
              <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                     value="{{ old('nom', $classe->nom ?? '') }}"
                     placeholder="ex: CP1-A, CE2-B, GS…"/>
              @error('nom')<div class="invalid-msg">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-5">
              <label class="form-label-gu">Niveau <span class="text-danger">*</span></label>
              <select name="niveau" class="form-select @error('niveau') is-invalid @enderror">
                <option value="">Sélectionner…</option>
                <optgroup label="Maternelle">
                  @foreach(['Petite Section (PS)','Moyenne Section (MS)','Grande Section (GS)'] as $n)
                    <option value="{{ $n }}" {{ old('niveau', $classe->niveau ?? '') === $n ? 'selected' : '' }}>{{ $n }}</option>
                  @endforeach
                </optgroup>
                <optgroup label="Primaire">
                  @foreach(['CP1 — 1ère année','CP2 — 2ème année','CE1 — 3ème année','CE2 — 4ème année','CM1 — 5ème année','CM2 — 6ème année'] as $n)
                    <option value="{{ $n }}" {{ old('niveau', $classe->niveau ?? '') === $n ? 'selected' : '' }}>{{ $n }}</option>
                  @endforeach
                </optgroup>
              </select>
              @error('niveau')<div class="invalid-msg">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-5">
              <label class="form-label-gu">Capacité maximale <span class="text-danger">*</span></label>
              <input type="number" name="capacite_max" min="1" max="100"
                     class="form-control @error('capacite_max') is-invalid @enderror"
                     value="{{ old('capacite_max', $classe->capacite_max ?? 30) }}"/>
              @error('capacite_max')<div class="invalid-msg">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-7">
              <label class="form-label-gu">Enseignant(e) responsable</label>
              <input type="text" name="enseignant_responsable"
                     class="form-control"
                     value="{{ old('enseignant_responsable', $classe->enseignant_responsable ?? '') }}"
                     placeholder="Nom de l'enseignant(e) — optionnel"/>
            </div>

            @if(isset($classe))
            <div class="col-12">
              <div class="form-check">
                <input type="checkbox" class="form-check-input" name="active" id="active" value="1"
                       {{ old('active', $classe->active ?? true) ? 'checked' : '' }}/>
                <label class="form-check-label small fw-semibold" for="active">
                  Classe active (visible pour les affectations)
                </label>
              </div>
            </div>
            @endif

          </div>

          <div class="d-flex justify-content-between align-items-center mt-4 pt-3"
               style="border-top:1px solid #E2E8F0">
            <a href="{{ route('admin.classes.index') }}" class="btn-outline-gu">
              <i class="bi bi-arrow-left"></i> Annuler
            </a>
            <button type="submit" class="btn-primary-gu">
              <i class="bi bi-{{ isset($classe) ? 'check-lg' : 'plus-circle' }}"></i>
              {{ isset($classe) ? 'Enregistrer' : 'Créer la classe' }}
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

@endsection
