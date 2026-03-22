{{-- ========================================================
     resources/views/emails/affectation-classe.blade.php
     ======================================================== --}}
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <title>Affectation de classe</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Segoe UI', Arial, sans-serif; background:#F1F5F9; color:#0D1B2A; }
    .wrapper { max-width:600px; margin:30px auto; background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 4px 24px rgba(13,27,42,.1); }
    .header { background:linear-gradient(115deg,#1e3a8a,#2563EB); padding:36px 40px; text-align:center; }
    .header .logo { font-size:2.5rem; margin-bottom:10px; }
    .header h1 { color:#fff; font-size:1.4rem; font-weight:700; }
    .header p  { color:rgba(255,255,255,.65); font-size:.88rem; margin-top:6px; }
    .body { padding:36px 40px; }
    .greeting { font-size:1.1rem; font-weight:600; margin-bottom:16px; }
    .text { color:#475569; font-size:.92rem; line-height:1.7; margin-bottom:20px; }
    .classe-box { background:linear-gradient(115deg,#EFF6FF,#DBEAFE); border:1px solid rgba(37,99,235,.2); border-radius:12px; padding:24px; text-align:center; margin:24px 0; }
    .classe-box .label { font-size:.78rem; font-weight:700; color:#64748B; text-transform:uppercase; letter-spacing:.07em; margin-bottom:8px; }
    .classe-box .classe-nom { font-size:1.8rem; font-weight:700; color:#1e3a8a; }
    .classe-box .niveau     { font-size:.92rem; color:#2563EB; margin-top:4px; }
    .info-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; margin:20px 0; }
    .info-item { background:#F8FAFC; border:1px solid #E2E8F0; border-radius:9px; padding:14px 16px; }
    .info-item .k { font-size:.75rem; color:#64748B; font-weight:600; text-transform:uppercase; letter-spacing:.05em; margin-bottom:4px; }
    .info-item .v { font-size:.9rem; color:#0D1B2A; font-weight:700; }
    .btn-cta { display:block; width:fit-content; margin:28px auto 0; background:linear-gradient(135deg,#2563EB,#1D4ED8); color:#fff; text-decoration:none; padding:13px 36px; border-radius:9px; font-weight:700; font-size:.95rem; }
    .footer { background:#F8FAFC; padding:24px 40px; text-align:center; border-top:1px solid #E2E8F0; }
    .footer p { font-size:.78rem; color:#94A3B8; line-height:1.6; }
    .footer strong { color:#64748B; }
  </style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <div class="logo"></div>
    <h1>Classe attribuée !</h1>
    <p>Projet de gestion scolaire groupe 3 — Année {{ $inscription->annee_scolaire }}</p>
  </div>
  <div class="body">
    <div class="greeting">
      Bonjour {{ $inscription->eleve->parent->prenom }} {{ $inscription->eleve->parent->nom }},
    </div>
    <p class="text">
      Nous avons le plaisir de vous informer que votre enfant
      <strong>{{ $inscription->eleve->prenom }} {{ $inscription->eleve->nom }}</strong>
      a été affecté(e) à sa classe pour l'année scolaire <strong>{{ $inscription->annee_scolaire }}</strong>.
    </p>
    <div class="classe-box">
      <div class="label">Classe attribuée</div>
      <div class="classe-nom">{{ $inscription->classe->nom }}</div>
      <div class="niveau">{{ $inscription->classe->niveau }}</div>
    </div>
    <div class="info-grid">
      <div class="info-item">
        <div class="k">Élève</div>
        <div class="v">{{ $inscription->eleve->prenom }} {{ $inscription->eleve->nom }}</div>
      </div>
      <div class="info-item">
        <div class="k">Capacité classe</div>
        <div class="v">{{ $inscription->classe->capacite_max }} élèves max.</div>
      </div>
      <div class="info-item">
        <div class="k">N° dossier</div>
        <div class="v" style="font-family:monospace">{{ $inscription->numero_dossier }}</div>
      </div>
      <div class="info-item">
        <div class="k">Enseignant(e)</div>
        <div class="v">{{ $inscription->classe->enseignant_responsable ?? 'À confirmer' }}</div>
      </div>
    </div>
    <a href="{{ url('/parent/dashboard') }}" class="btn-cta">
      Voir le dossier complet
    </a>
  </div>
  <div class="footer">
    <p><strong>Projet de gestion scolaire groupe 3</strong><br>Cotonou, Bénin<br><br>Cet email a été envoyé automatiquement.</p>
  </div>
</div>
</body>
</html>
