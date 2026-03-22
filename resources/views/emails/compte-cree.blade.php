<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Votre compte </title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: 'Segoe UI', Arial, sans-serif; background:#F1F5F9; color:#0D1B2A; }
    .wrapper { max-width:600px; margin:30px auto; background:#fff; border-radius:14px; overflow:hidden; box-shadow:0 4px 24px rgba(13,27,42,.1); }
    .header  { background:linear-gradient(115deg,#0D1B2A,#1A3A5C); padding:36px 40px; text-align:center; }
    .header .logo { font-size:2.2rem; margin-bottom:10px; }
    .header h1 { color:#fff; font-size:1.4rem; font-weight:700; margin:0; }
    .header p  { color:rgba(255,255,255,.6); font-size:.88rem; margin-top:6px; }
    .body { padding:36px 40px; }
    .greeting { font-size:1.1rem; font-weight:600; color:#0D1B2A; margin-bottom:16px; }
    .text { color:#475569; font-size:.92rem; line-height:1.7; margin-bottom:20px; }
    .credentials-box {
      background:#F8FAFC; border:1px solid #E2E8F0;
      border-left:4px solid #2563EB;
      border-radius:10px; padding:20px 24px; margin:24px 0;
    }
    .credentials-box .cred-label { font-size:.75rem; font-weight:700; color:#64748B; text-transform:uppercase; letter-spacing:.06em; margin-bottom:4px; }
    .credentials-box .cred-value { font-size:1rem; font-weight:700; color:#0D1B2A; font-family:monospace; }
    .credentials-box .cred-value.password { font-size:1.25rem; color:#2563EB; letter-spacing:.08em; }
    .credentials-row { margin-bottom:14px; }
    .credentials-row:last-child { margin-bottom:0; }
    .btn-connect {
      display:block; width:fit-content; margin:0 auto;
      background:linear-gradient(135deg,#2563EB,#1D4ED8);
      color:#fff; text-decoration:none;
      padding:13px 36px; border-radius:9px;
      font-weight:700; font-size:.95rem;
      text-align:center; margin-top:28px;
    }
    .warning-box {
      background:#FFFBEB; border:1px solid rgba(245,158,11,.25);
      border-left:4px solid #F59E0B;
      border-radius:9px; padding:14px 18px; margin-top:24px;
      font-size:.84rem; color:#92400E; line-height:1.6;
    }
    .warning-box strong { color:#B45309; }
    .footer { background:#F8FAFC; padding:24px 40px; text-align:center; border-top:1px solid #E2E8F0; }
    .footer p { font-size:.78rem; color:#94A3B8; line-height:1.6; }
    .footer strong { color:#64748B; }
    .divider { height:1px; background:#F1F5F9; margin:20px 0; }
  </style>
</head>
<body>
<div class="wrapper">

  {{-- Header --}}
  <div class="header">
    <div class="logo">🎓</div>
    <h1>Bienvenue sur La plateforme de gestion scolaire</h1>
    <p>Projet de gestion scolaire groupe 3 — Espace Parent</p>
  </div>

  {{-- Body --}}
  <div class="body">
    <div class="greeting">Bonjour {{ $user->name }},</div>

    <p class="text">
      Votre compte parent a été créé avec succès sur la plateforme <strong>scolaire</strong>.
      Vous pouvez dès maintenant vous connecter pour suivre le dossier d'inscription de votre enfant.
    </p>

    {{-- Identifiants --}}
    <div class="credentials-box">
      <div class="credentials-row">
        <div class="cred-label">Adresse e-mail</div>
        <div class="cred-value">{{ $user->email }}</div>
      </div>
      <div class="divider"></div>
      <div class="credentials-row">
        <div class="cred-label">Mot de passe temporaire</div>
        <div class="cred-value password">{{ $motDePasse }}</div>
      </div>
    </div>

    <a href="{{ url('/login') }}" class="btn-connect">
       Se connecter à mon espace
    </a>

    {{-- Avertissement --}}
    <div class="warning-box">
      <strong> Important :</strong> Ce mot de passe est temporaire.
      Nous vous recommandons de le changer dès votre première connexion.
      Ne le partagez avec personne.
    </div>
  </div>

  {{-- Footer --}}
  <div class="footer">
    <p>
      <strong>Projet de gestion scolaire groupe 3</strong><br>
      Cotonou, Bénin · {{ config('app.url') }}<br>
      <br>
      Cet email a été envoyé automatiquement. Merci de ne pas y répondre directement.
    </p>
  </div>

</div>
</body>
</html>
