@extends('layouts.app')

@section('title','Cartes scolaires — Gestion Scolaire')
@section('page_title','Cartes scolaires')
@section('page_subtitle',$eleves->count().' élève(s)')

@push('styles')
<style>
.carte-card{
  background:#fff;
  border-radius:14px;
  border:1px solid #E2E8F0;
  box-shadow:0 4px 20px rgba(13,27,42,.07);
  overflow:hidden;
  transition:.2s;
}
.carte-card:hover{
  transform:translateY(-3px);
  box-shadow:0 10px 30px rgba(13,27,42,.12);
}

.carte-head{
  background:linear-gradient(115deg,#0D1B2A,#1A3A5C);
  padding:1.4rem;
  text-align:center;

  display:flex;
  flex-direction:column;
  align-items:center; /* centre horizontalement */
  justify-content:center;
}




.carte-photo{
  width:100px;
  height:70px;
  border-radius:50%;
  overflow:hidden;
  border:3px solid rgba(255,255,255,.25);
  margin:auto;

}
  .eleve-photo img { width:100%; height:100%; object-fit:cover; }
  .eleve-nom  { font-family:'Playfair Display',serif; color:#fff; font-size:1.05rem; margin:0 0 .2rem; }
  .eleve-sexe { color:rgba(255,255,255,.5); font-size:.78rem; }

  .eleve-photo {
  width:72px;
  height:72px;
  border-radius:50%;
  border:3px solid rgba(255,255,255,.25);
  background:rgba(255,255,255,.1);

  display:flex;
  align-items:center;
  justify-content:center;

  overflow:hidden;
  margin-bottom:.6rem;
}

.carte-nom{
  color:#fff;
  font-family:'Playfair Display',serif;
  margin-top:.5rem;
}

.carte-body{
  padding:1rem 1.2rem;
}

.carte-foot{
  padding:.9rem;
  background:#F8FAFC;
  border-top:1px solid #E2E8F0;
}

.eleve-photo {
    width: 72px; height: 72px; border-radius: 50%;
    border: 3px solid rgba(255,255,255,.25);
    background: rgba(255,255,255,.1);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.8rem; color: #38BDF8;
    overflow: hidden; margin-bottom: .8rem;
  }
</style>
@endpush


@section('content')

{{-- ── HEADER ── --}}
<div style="background:linear-gradient(115deg,#0D1B2A,#1A3A5C);border-radius:14px;
            padding:1.6rem 2rem;margin-bottom:1.8rem;position:relative;overflow:hidden"
     class="anim-up">
  <div style="position:absolute;right:-40px;top:-40px;width:180px;height:180px;
              border-radius:50%;background:rgba(56,189,248,.06)"></div>
  <h2 style="font-family:'Playfair Display',serif;color:#fff;font-size:1.35rem;margin:0 0 .25rem">
    Mes cartes scolaires
  </h2>
  <p style="color:rgba(255,255,255,.55);font-size:.87rem;margin:0">
    {{ $eleves->count() }} élève(s) enregistré(s)
    &nbsp;·&nbsp; Cliquez sur un enfant pour voir sa carte scolaire.
  </p>
</div>

<div class="row g-4">

@forelse($eleves as $eleve)

<div class="col-md-4">
<div class="carte-card anim-up">

<div class="carte-head">



    <div class="eleve-photo">
        @if($eleve->photo)
            <img src="{{ asset('storage/'.$eleve->photo) }}" alt="photo"/>
        @else
            <i class="bi bi-person-fill"></i>
        @endif
    </div>


<div class="carte-nom">
{{ $eleve->prenom }} {{ $eleve->nom }}
</div>

</div>

<div class="carte-body">

<div class="d-flex justify-content-between mb-2">
<span class="text-muted">Classe</span>
<strong>{{ $eleve->inscription?->classe?->nom ?? '—' }}</strong>
</div>

<div class="d-flex justify-content-between">
<span class="text-muted">Matricule</span>
<strong>{{ $eleve->matricule ?? '—' }}</strong>
</div>

</div>

<div class="carte-foot text-center">

<a href="{{ route('parent.cartes.show',$eleve->id) }}"
class="btn-primary-gu"
style="padding:.45rem .9rem;font-size:.8rem">

<i class="bi bi-credit-card"></i>
Voir la carte

</a>

</div>

</div>
</div>

@empty

<div class="col-12">
<div class="card-gu text-center p-4">
Aucun élève trouvé
</div>
</div>

@endforelse

</div>

@endsection
