<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <title>Attestation d'inscription — {{ $inscription->numero_dossier }}</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      font-family: 'DejaVu Sans', Arial, sans-serif;
      font-size: 11pt;
      color: #0D1B2A;
      background: #fff;
      line-height: 1.7;
    }
    @page { margin: 20mm 20mm 20mm 20mm; size: A4; }

    .entete {
      text-align: center;
      margin-bottom: 30px;
      padding-bottom: 16px;
      border-bottom: 2px solid #1A3A5C;
    }
    .logo-placeholder {
      display: inline-block;
      width: 70px; height: 70px;
      border-radius: 50%;
      background: #1A3A5C;
      color: #fff;
      font-size: 28pt;
      line-height: 70px;
      text-align: center;
      margin-bottom: 8px;
    }
    .nom-universite {
      font-size: 15pt;
      font-weight: bold;
      color: #0D1B2A;
      text-transform: uppercase;
      letter-spacing: .06em;
    }
    .sous-entete { font-size: 9.5pt; color: #334155; margin-top: 4px; }

    .titre-doc { text-align: center; margin: 28px 0 32px; }
    .titre-doc h1 {
      font-size: 14pt;
      font-weight: bold;
      color: #1A5C2A;
      text-transform: uppercase;
      letter-spacing: .1em;
      text-decoration: underline;
    }

    .corps { margin: 0 auto; max-width: 540px; text-align: justify; }
    .intro       { margin-bottom: 18px; font-size: 11pt; }
    .attestation { margin-bottom: 24px; font-size: 11pt; }
    .attestation strong { font-weight: bold; }
    .formule-fin { margin-top: 24px; font-size: 11pt; }

    .signature-zone { margin-top: 40px; text-align: right; padding-right: 40px; }
    .lieu-date       { font-size: 10.5pt; margin-bottom: 55px; }
    .signataire-nom  { font-size: 11pt; font-weight: bold; }

    .qr-zone {
      margin-top: 40px;
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
    }
    .qr-box {
      width: 80px; height: 80px;
      border: 1px solid #CBD5E1;
      background: #F8FAFC;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 7pt;
      color: #94A3B8;
      text-align: center;
    }

    .footer {
      margin-top: 30px;
      padding-top: 8px;
      border-top: 1px solid #CBD5E1;
      text-align: center;
      font-size: 7.5pt;
      color: #64748B;
    }
  </style>
</head>
<body>

{{-- EN-TÊTE --}}
<div class="entete">
  <div class="logo-placeholder">🎓</div>
  <div class="nom-universite">Gestion scolaire</div>
  <div class="sous-entete">
    Direction des Études · Cotonou, Bénin<br>
    Tél : +229 00 01 00 00 00 · irenelokossou16@gmail.com 
  </div>
</div>

{{-- TITRE --}}
<div class="titre-doc">
  <h1>Attestation d'inscription</h1>
</div>

{{-- CORPS --}}
<div class="corps">

  <p class="intro">
    Le Directeur des Études de l'École 
  </p>

  <p class="attestation">
    Atteste que le nommé
    <strong>{{ strtoupper($inscription->eleve->nom) }} {{ $inscription->eleve->prenom }}</strong>,
    né(e) le
    <strong>{{ \Carbon\Carbon::parse($inscription->eleve->date_naissance)->isoFormat('D MMMM YYYY') }}</strong>
    à <strong>{{ $inscription->eleve->lieu_naissance ?? '—' }}</strong>,
    est inscrit(e) à l'École 
    sous le numéro de dossier <strong>{{ $inscription->numero_dossier }}</strong>
    en <strong>{{ $inscription->classe?->nom ?? 'classe non affectée' }}</strong>
    au titre de l'année scolaire&nbsp;: <strong>{{ $inscription->annee_scolaire }}</strong>.
  </p>

  <p class="formule-fin">
    Cette attestation a été délivrée à l'intéressé(e) pour servir et valoir ce que de droit.
  </p>

</div>

{{-- SIGNATURE --}}
<div class="signature-zone">
  <div class="lieu-date">
    Fait à Cotonou le {{ now()->isoFormat('D MMMM YYYY') }}
  </div>
  <div class="signataire-nom">Le Directeur des Études</div>
</div>

{{-- QR + TAMPON --}}

{{-- PIED DE PAGE --}}
<div class="footer">
  Attestation générée le {{ now()->isoFormat('D MMMM YYYY [à] HH[h]mm') }}
  · Dossier N° {{ $inscription->numero_dossier }}
  · Gestion-scolaire · Cotonou, Bénin
</div>

</body>
</html>