<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <title>Rappel paiement</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Segoe UI', Arial, sans-serif; background:#F1F5F9; color:#0D1B2A; }
    .wrapper { max-width:600px; margin:30px auto; background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 4px 24px rgba(13,27,42,.1); }
    .header { background:linear-gradient(115deg,#4C1D95,#7C3AED); padding:36px 40px; text-align:center; }
    .header .logo { font-size:2.5rem; margin-bottom:10px; }
    .header h1 { color:#fff; font-size:1.4rem; font-weight:700; }
    .header p  { color:rgba(255,255,255,.65); font-size:.88rem; margin-top:6px; }
    .body { padding:36px 40px; }
    .greeting { font-size:1.1rem; font-weight:600; margin-bottom:16px; }
    .text { color:#475569; font-size:.92rem; line-height:1.7; margin-bottom:20px; }
    .montant-box { background:linear-gradient(115deg,#4C1D95,#7C3AED); border-radius:12px; padding:28px; text-align:center; margin:24px 0; }
    .montant-box .label   { font-size:.78rem; font-weight:700; color:rgba(255,255,255,.6); text-transform:uppercase; letter-spacing:.07em; margin-bottom:8px; }
    .montant-box .montant { font-size:2rem; font-weight:700; color:#fff; }
    .montant-box .libelle { font-size:.9rem; color:rgba(255,255,255,.7); margin-top:6px; }
    .echeance-badge {
      display:inline-flex; align-items:center; gap:6px;
      background:rgba(255,255,255,.15); color:#fff;
      border-radius:20px; padding:6px 16px; font-size:.84rem;
      font-weight:600; margin-top:12px;
    }
    .info-box { background:#F8FAFC; border:1px solid #E2E8F0; border-radius:9px; padding:16px 20px; margin:20px 0; }
    .info-row { display:flex; justify-content:space-between; padding:7px 0; border-bottom:1px solid #F1F5F9; font-size:.87rem; }
    .info-row:last-child { border:none; }
    .info-row .k { color:#64748B; }
    .info-row .v { color:#0D1B2A; font-weight:600; }
    .warning-box { background:#FFFBEB; border:1px solid rgba(245,158,11,.25); border-left:4px solid #F59E0B; border-radius:9px; padding:14px 18px; margin:20px 0; font-size:.84rem; color:#92400E; line-height:1.6; }
    .warning-box strong { color:#B45309; }
    .btn-cta { display:block; width:fit-content; margin:28px auto 0; background:linear-gradient(135deg,#7C3AED,#6D28D9); color:#fff; text-decoration:none; padding:13px 36px; border-radius:9px; font-weight:700; font-size:.95rem; }
    .footer { background:#F8FAFC; padding:24px 40px; text-align:center; border-top:1px solid #E2E8F0; }
    .footer p { font-size:.78rem; color:#94A3B8; line-height:1.6; }
    .footer strong { color:#64748B; }
  </style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <div class="logo"></div>
    <h1>Rappel de paiement</h1>
    <p>Projet de gestion scolaire groupe 3 — Frais de scolarité</p>
  </div>
  <div class="body">
    <div class="greeting">
      Bonjour {{ $echeance->parent->prenom }} {{ $echeance->parent->nom }},
    </div>
    <p class="text">
      Nous vous rappelons qu'une échéance de paiement des frais de scolarité
      de votre enfant <strong>{{ $echeance->eleve->prenom }} {{ $echeance->eleve->nom }}</strong>
      arrive prochainement.
    </p>
    <div class="montant-box">
      <div class="label">Montant à régler</div>
      <div class="montant">{{ number_format($echeance->montant, 0, ',', ' ') }} FCFA</div>
      <div class="libelle">{{ $echeance->libelle }}</div>
      <div class="echeance-badge">
        📅 Avant le {{ \Carbon\Carbon::parse($echeance->date_limite)->isoFormat('D MMMM YYYY') }}
      </div>
    </div>
    <div class="info-box">
      <div class="info-row"><span class="k">Élève</span><span class="v">{{ $echeance->eleve->prenom }} {{ $echeance->eleve->nom }}</span></div>
      <div class="info-row"><span class="k">Tranche</span><span class="v">{{ $echeance->libelle }}</span></div>
      <div class="info-row"><span class="k">Date limite</span><span class="v">{{ \Carbon\Carbon::parse($echeance->date_limite)->isoFormat('D MMMM YYYY') }}</span></div>
      <div class="info-row"><span class="k">Statut</span><span class="v" style="color:#B45309">⏳ Impayée</span></div>
    </div>
    @if(\Carbon\Carbon::parse($echeance->date_limite)->diffInDays(now()) <= 3)
      <div class="warning-box">
        <strong>⚠️ Urgent :</strong> L'échéance est dans moins de 3 jours.
        Merci de régulariser votre situation au plus tôt pour éviter tout blocage.
      </div>
    @endif
    <a href="{{ url('/parent/dashboard') }}" class="btn-cta">
      💳 Voir les modalités de paiement
    </a>
  </div>
  <div class="footer">
    <p><strong>Projet de gestion scolaire groupe 3</strong><br>Cotonou, Bénin · irenelokossou16@gmail.com<br><br>Cet email a été envoyé automatiquement.</p>
  </div>
</div>
</body>
</html>
