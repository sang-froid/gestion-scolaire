<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Confirmation d'inscription</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Segoe UI', Arial, sans-serif; background:#F1F5F9; color:#0D1B2A; }
    .wrapper { max-width:600px; margin:30px auto; background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 4px 24px rgba(13,27,42,.1); }
    .header  { background:linear-gradient(115deg,#0D1B2A,#1A3A5C); padding:36px 40px; text-align:center; }
    .header .logo { font-size:2.2rem; margin-bottom:10px; }
    .header h1 { color:#fff; font-size:1.4rem; font-weight:700; }
    .header p  { color:rgba(255,255,255,.6); font-size:.88rem; margin-top:6px; }
    .body { padding:36px 40px; }
    .greeting { font-size:1.1rem; font-weight:600; color:#0D1B2A; margin-bottom:16px; }
    .text { color:#475569; font-size:.92rem; line-height:1.7; margin-bottom:20px; }
    /* Numéro dossier */
    .dossier-badge {
      display:inline-block;
      background:linear-gradient(135deg,#2563EB,#1D4ED8);
      color:#fff; font-family:monospace; font-size:1.1rem; font-weight:700;
      padding:10px 24px; border-radius:9px; letter-spacing:.06em;
      margin:16px 0;
    }
    /* Tableau récap */
    .recap { width:100%; border-collapse:collapse; margin:24px 0; }
    .recap th { background:#F8FAFC; padding:10px 16px; text-align:left; font-size:.78rem; font-weight:700; color:#64748B; text-transform:uppercase; letter-spacing:.05em; border-bottom:2px solid #E2E8F0; }
    .recap td { padding:12px 16px; font-size:.88rem; color:#0D1B2A; border-bottom:1px solid #F1F5F9; }
    .recap tr:last-child td { border:none; }
    .recap .val-strong { font-weight:700; }
    /* Badge statut */
    .badge-attente { display:inline-flex; align-items:center; gap:5px; background:rgba(245,158,11,.1); color:#B45309; border:1px solid rgba(245,158,11,.25); border-radius:20px; padding:3px 12px; font-size:.8rem; font-weight:700; }
    /* Timeline */
    .steps { margin:24px 0; }
    .step { display:flex; align-items:flex-start; gap:12px; margin-bottom:16px; }
    .step-num { width:28px; height:28px; border-radius:50%; background:#2563EB; color:#fff; font-size:.78rem; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:1px; }
    .step-num.done { background:#10B981; }
    .step-content strong { font-size:.88rem; color:#0D1B2A; display:block; }
    .step-content span   { font-size:.8rem; color:#64748B; }
    /* CTA */
    .btn-cta { display:block; width:fit-content; margin:0 auto; background:linear-gradient(135deg,#2563EB,#1D4ED8); color:#fff; text-decoration:none; padding:13px 36px; border-radius:9px; font-weight:700; font-size:.95rem; text-align:center; margin-top:28px; }
    .footer { background:#F8FAFC; padding:24px 40px; text-align:center; border-top:1px solid #E2E8F0; }
    .footer p { font-size:.78rem; color:#94A3B8; line-height:1.6; }
    .footer strong { color:#64748B; }
  </style>
</head>
<body>
<div class="wrapper">

  <div class="header">
    <div class="logo"></div>
    <h1>Dossier d'inscription reçu</h1>
    <p>Projet de gestion scolaire groupe 3 — Année {{ $inscription->annee_scolaire }}</p>
  </div>

  <div class="body">
    <div class="greeting">
      Bonjour {{ $inscription->eleve->parent->prenom }} {{ $inscription->eleve->parent->nom }},
    </div>

    <p class="text">
      Nous avons bien reçu le dossier d'inscription de votre enfant.
      Voici le récapitulatif de votre demande :
    </p>

    {{-- Numéro dossier --}}
    <div style="text-align:center">
      <div class="dossier-badge">{{ $inscription->numero_dossier }}</div>
    </div>

    {{-- Tableau récap --}}
    <table class="recap">
      <tr>
        <th>Élève</th>
        <th>Niveau souhaité</th>
        <th>Type</th>
        <th>Statut</th>
      </tr>
      <tr>
        <td class="val-strong">{{ $inscription->eleve->prenom }} {{ $inscription->eleve->nom }}</td>
        <td>{{ $inscription->eleve->niveau_souhaite }}</td>
        <td>{{ $inscription->type === 'nouvelle' ? 'Nouvelle inscription' : 'Réinscription' }}</td>
        <td><span class="badge-attente">⏳ En attente</span></td>
      </tr>
    </table>

    {{-- Prochaines étapes --}}
    <p class="text"><strong>Que se passe-t-il ensuite ?</strong></p>
    <div class="steps">
      <div class="step">
        <div class="step-num done">✓</div>
        <div class="step-content">
          <strong>Dossier soumis</strong>
          <span>Votre demande a bien été enregistrée.</span>
        </div>
      </div>
      <div class="step">
        <div class="step-num">2</div>
        <div class="step-content">
          <strong>Vérification par le secrétariat</strong>
          <span>Notre équipe examine votre dossier et les documents fournis.</span>
        </div>
      </div>
      <div class="step">
        <div class="step-num">3</div>
        <div class="step-content">
          <strong>Décision et notification</strong>
          <span>Vous recevrez un email dès que votre dossier sera traité.</span>
        </div>
      </div>
      <div class="step">
        <div class="step-num">4</div>
        <div class="step-content">
          <strong>Affectation de classe</strong>
          <span>En cas de validation, votre enfant sera affecté à une classe.</span>
        </div>
      </div>
    </div>

    <a href="{{ url('/parent/dashboard') }}" class="btn-cta">
       Suivre mon dossier
    </a>
  </div>

  <div class="footer">
    <p>
      <strong>Projet de gestion scolaire groupe 3</strong><br>
      Cotonou, Bénin<br><br>
      Cet email a été envoyé automatiquement. Ne pas répondre directement.
    </p>
  </div>

</div>
</body>
</html>
