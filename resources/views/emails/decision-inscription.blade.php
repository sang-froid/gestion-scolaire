<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Décision sur votre dossier</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Segoe UI', Arial, sans-serif; background:#F1F5F9; color:#0D1B2A; }
    .wrapper { max-width:600px; margin:30px auto; background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 4px 24px rgba(13,27,42,.1); }

    /* Header dynamique selon statut */
    .header-valide  { background:linear-gradient(115deg,#065F46,#047857); padding:36px 40px; text-align:center; }
    .header-refuse  { background:linear-gradient(115deg,#7F1D1D,#9F1239); padding:36px 40px; text-align:center; }
    .header .logo { font-size:2.5rem; margin-bottom:10px; }
    .header h1 { color:#fff; font-size:1.4rem; font-weight:700; }
    .header p  { color:rgba(255,255,255,.65); font-size:.88rem; margin-top:6px; }

    .body { padding:36px 40px; }
    .greeting { font-size:1.1rem; font-weight:600; color:#0D1B2A; margin-bottom:16px; }
    .text { color:#475569; font-size:.92rem; line-height:1.7; margin-bottom:20px; }

    /* Box validée */
    .box-valide { background:rgba(16,185,129,.07); border:1px solid rgba(16,185,129,.25); border-left:4px solid #10B981; border-radius:10px; padding:20px 24px; margin:20px 0; }
    .box-valide .titre { color:#065F46; font-weight:700; font-size:.95rem; margin-bottom:8px; }
    .box-valide p { color:#047857; font-size:.88rem; line-height:1.6; }

    /* Box refusée */
    .box-refuse { background:rgba(244,63,94,.05); border:1px solid rgba(244,63,94,.2); border-left:4px solid #F43F5E; border-radius:10px; padding:20px 24px; margin:20px 0; }
    .box-refuse .titre { color:#9F1239; font-weight:700; font-size:.95rem; margin-bottom:8px; }
    .box-refuse p { color:#7F1D1D; font-size:.88rem; line-height:1.6; }

    /* Infos dossier */
    .dossier-info { background:#F8FAFC; border:1px solid #E2E8F0; border-radius:9px; padding:16px 20px; margin:20px 0; }
    .dossier-info .row { display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px solid #F1F5F9; font-size:.87rem; }
    .dossier-info .row:last-child { border:none; }
    .dossier-info .row .k { color:#64748B; }
    .dossier-info .row .v { color:#0D1B2A; font-weight:600; }

    .btn-cta-valide { display:block; width:fit-content; margin:28px auto 0; background:linear-gradient(135deg,#059669,#047857); color:#fff; text-decoration:none; padding:13px 36px; border-radius:9px; font-weight:700; font-size:.95rem; }
    .btn-cta-refuse { display:block; width:fit-content; margin:28px auto 0; background:linear-gradient(135deg,#2563EB,#1D4ED8); color:#fff; text-decoration:none; padding:13px 36px; border-radius:9px; font-weight:700; font-size:.95rem; }

    .footer { background:#F8FAFC; padding:24px 40px; text-align:center; border-top:1px solid #E2E8F0; }
    .footer p { font-size:.78rem; color:#94A3B8; line-height:1.6; }
    .footer strong { color:#64748B; }
  </style>
</head>
<body>
<div class="wrapper">

  {{-- Header conditionnel --}}
  @if($inscription->statut === 'validee')
    <div class="header header-valide">
      <div class="logo"></div>
      <h1>Inscription validée !</h1>
      <p>Projet de gestion scolaire groupe 3</p>
    </div>
  @else
    <div class="header header-refuse">
      <div class="logo"></div>
      <h1>Dossier non retenu</h1>
      <p>Projet de gestion scolaire groupe 3 — Année {{ $inscription->annee_scolaire }}</p>
    </div>
  @endif

  <div class="body">
    <div class="greeting">
      Bonjour {{ $inscription->eleve->parent->prenom }} {{ $inscription->eleve->parent->nom }},
    </div>

    @if($inscription->statut === 'validee')
      {{-- VALIDÉ --}}
      <p class="text">
        Nous avons le plaisir de vous informer que le dossier d'inscription de votre enfant
        <strong>{{ $inscription->eleve->prenom }} {{ $inscription->eleve->nom }}</strong>
        a été <strong style="color:#059669">validé</strong> par notre équipe.
      </p>

      <div class="box-valide">
        <div class="titre">✅ Dossier accepté</div>
        <p>
          Votre enfant sera prochainement affecté à une classe.
          Vous recevrez un email de confirmation dès que l'affectation sera effectuée.
        </p>
      </div>

      <div class="dossier-info">
        <div class="row"><span class="k">N° dossier</span><span class="v">{{ $inscription->numero_dossier }}</span></div>
        <div class="row"><span class="k">Élève</span><span class="v">{{ $inscription->eleve->prenom }} {{ $inscription->eleve->nom }}</span></div>
        <div class="row"><span class="k">Niveau</span><span class="v">{{ $inscription->eleve->niveau_souhaite }}</span></div>
        <div class="row"><span class="k">Année scolaire</span><span class="v">{{ $inscription->annee_scolaire }}</span></div>
        <div class="row"><span class="k">Validé le</span><span class="v">{{ $inscription->validee_le?->isoFormat('D MMMM YYYY') }}</span></div>
      </div>

      <a href="{{ url('/parent/dashboard') }}" class="btn-cta-valide">
        🏫 Accéder à mon espace
      </a>

    @else
      {{-- REFUSÉ --}}
      <p class="text">
        Nous avons le regret de vous informer que le dossier d'inscription de votre enfant
        <strong>{{ $inscription->eleve->prenom }} {{ $inscription->eleve->nom }}</strong>
        n'a pas pu être retenu pour l'année scolaire {{ $inscription->annee_scolaire }}.
      </p>

      @if($inscription->motif_refus)
        <div class="box-refuse">
          <div class="titre">❌ Motif du refus</div>
          <p>{{ $inscription->motif_refus }}</p>
        </div>
      @endif

      <p class="text">
        Vous pouvez soumettre un nouveau dossier en corrigeant les éléments mentionnés,
        ou contacter notre secrétariat pour plus d'informations.
      </p>

      <div class="dossier-info">
        <div class="row"><span class="k">N° dossier</span><span class="v">{{ $inscription->numero_dossier }}</span></div>
        <div class="row"><span class="k">Élève</span><span class="v">{{ $inscription->eleve->prenom }} {{ $inscription->eleve->nom }}</span></div>
        <div class="row"><span class="k">Année scolaire</span><span class="v">{{ $inscription->annee_scolaire }}</span></div>
      </div>

      <a href="{{ url('/parent/dashboard') }}" class="btn-cta-refuse">
        📂 Soumettre un nouveau dossier
      </a>
    @endif
  </div>

  <div class="footer">
    <p>
      <strong>Projet de gestion scolaire groupe 3</strong><br>
      Cotonou, Bénin · Pour toute question : irenelokossou16@gmail.com<br><br>
      Cet email a été envoyé automatiquement.
    </p>
  </div>

</div>
</body>
</html>
