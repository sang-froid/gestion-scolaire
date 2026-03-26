<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <title>Carte scolaire</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'DejaVu Sans', sans-serif; background:#fff; }

    @page { margin:0; size:85.6mm 108mm; }

    .card-recto,
    .card-verso {
      height: 53mm;
      border: none;
    }

    /* Bandes tricolores */
    .bande-table { width:85.6mm; border-collapse:collapse; }
    .b-vert  { background:#009A44; height:1.5mm; width:33.33%; }
    .b-jaune { background:#FCD116; height:1.5mm; width:33.33%; }
    .b-rouge { background:#CE1126; height:1.5mm; width:33.33%; }

    .ministere-nom { font-size:4.5pt; font-weight:bold; color:#111111; line-height:1.3; }
    .republique    { font-size:4pt;   color:#333333; margin-top:0.3mm; }
    .school-nom    { font-size:5.5pt; font-weight:bold; color:#111111; text-align:right; }
    .school-ville  { font-size:4pt;   color:#555555; text-align:right; margin-top:0.3mm; }

    .titre {
      width:85.6mm; text-align:center; font-size:9pt;
      font-weight:bold; color:#CE1126; letter-spacing:.04em;
      padding:1mm 0; border-top:0.2mm solid #eeeeee; border-bottom:0.2mm solid #eeeeee;
    }

    /* ✅ Clé anti-décalage : line-height:0 et font-size:0 */
    .photo-box {
      width:14mm; height:18mm;
      border:0.3mm solid #aaaaaa;
      background:#f0f0f0;
      overflow:hidden;
      text-align:center;
      line-height:0;
      font-size:0;
    }
    .photo-ph { font-size:4pt; color:#aaaaaa; padding-top:6mm; line-height:1.2; }

    .lbl    { font-size:5.5pt; color:#111111; width:13mm; white-space:nowrap; }
    .sep    { font-size:5.5pt; color:#111111; width:2mm; }
    .val    { font-size:5.5pt; font-weight:bold; color:#000000; }
    .row-sp { height:1.3mm; }

    .sig-label     { font-size:4.5pt; color:#444444; white-space:nowrap; }
    .sig-line-cell { border-bottom:0.2mm solid #333333; }
    .footer-td     { font-size:4.5pt; color:#333333; padding:0.5mm 2mm; }

    /* VERSO */
    .verso-school-name { font-size:9pt; font-weight:bold; color:#111111; text-align:center; letter-spacing:.04em; text-transform:uppercase; }
    .verso-sub         { font-size:4.5pt; color:#555555; text-align:center; margin-top:0.5mm; }
    .verso-annee       { font-size:6pt; font-weight:bold; color:#CE1126; text-align:center; margin-top:1mm; }
    .verso-dir-label   { font-size:4.5pt; color:#888888; text-transform:uppercase; letter-spacing:.06em; text-align:center; }
    .verso-dir-name    { font-size:7pt; font-weight:bold; color:#111111; text-align:center; margin-top:0.8mm; }
    .verso-sig-label   { font-size:4.5pt; color:#888888; text-align:center; margin-top:1.5mm; }
  </style>
</head>
<body>

{{-- ══════════ RECTO ══════════ --}}
<div class="card-recto">

  <table class="bande-table" cellspacing="0" cellpadding="0">
    <tr><td class="b-vert"></td><td class="b-jaune"></td><td class="b-rouge"></td></tr>
  </table>

  <table width="85.6mm" cellspacing="0" cellpadding="0">
    <tr>
      <td style="width:8mm;padding:1.5mm 1mm 1mm 2mm;vertical-align:middle;">
        <svg width="7mm" height="7mm" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
          <rect width="20" height="60" fill="#009A44"/>
          <rect x="20" y="0" width="40" height="30" fill="#FCD116"/>
          <rect x="20" y="30" width="40" height="30" fill="#CE1126"/>
          <circle cx="30" cy="30" r="9" fill="rgba(255,255,255,.25)"/>
        </svg>
      </td>
      <td style="padding:1.5mm 1mm 1mm 1mm;vertical-align:middle;">
        <div class="ministere-nom">MINISTÈRE DES ENSEIGNEMENTS<br/>MATERNEL ET PRIMAIRE (MEMP)</div>
        <div class="republique">RÉPUBLIQUE DU BÉNIN</div>
      </td>
      <td style="padding:1.5mm 2mm 1mm 1mm;vertical-align:middle;text-align:right;">
        <div class="school-nom">{{ $inscription->classe?->ecole ?? 'École ' }}</div>
        <div class="school-ville">Cotonou, Bénin</div>
      </td>
    </tr>
  </table>

  <div class="titre">CARTE SCOLAIRE : {{ $inscription->annee_scolaire }}</div>

  <table width="85.6mm" cellspacing="0" cellpadding="0">
    <tr>
      {{--  padding vertical 0 + line-height:0 sur la td photo ── --}}
      <td style="width:18mm;padding:1.8mm 1mm 0 2mm;vertical-align:top;line-height:0;">
        <div class="photo-box">
          @if($inscription->eleve->photo)
            @php
              $path   = public_path('storage/' . $inscription->eleve->photo);
              $type   = pathinfo($path, PATHINFO_EXTENSION);
              $data   = file_get_contents($path);
              $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            @endphp
            {{--  display:block + dimensions fixes + object-fit + margin:0 ── --}}
            <img src="{{ $base64 }}"
                 style="width:14mm;height:18mm;display:block;object-fit:cover;margin:0;padding:0;border:0;"/>
          @else
            <div class="photo-ph">PHOTO</div>
          @endif
        </div>
      </td>
      <td style="padding:1.8mm 2mm 1mm 1mm;vertical-align:top;">
        <table width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="lbl">Nom</td>
            <td class="sep">:</td>
            <td class="val">{{ strtoupper($inscription->eleve->nom) }}</td>
          </tr>
          <tr><td colspan="3" class="row-sp"></td></tr>
          <tr>
            <td class="lbl">Prénoms</td>
            <td class="sep">:</td>
            <td class="val">{{ $inscription->eleve->prenom }}</td>
          </tr>
          <tr><td colspan="3" class="row-sp"></td></tr>
          <tr>
            <td class="lbl">Né(e) le</td>
            <td class="sep">:</td>
            <td class="val">{{ \Carbon\Carbon::parse($inscription->eleve->date_naissance)->format('d/m/Y') }} à {{ $inscription->eleve->lieu_naissance ?? '—' }}</td>
          </tr>
          <tr><td colspan="3" class="row-sp"></td></tr>
          <tr>
            <td class="lbl">Classe</td>
            <td class="sep">:</td>
            <td class="val">{{ $inscription->classe?->nom ?? '—' }}</td>
          </tr>
          <tr><td colspan="3" style="height:2mm;"></td></tr>
          <tr>
            <td class="sig-label">Signature de l'apprenant</td>
            <td colspan="2" class="sig-line-cell"></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <table width="85.6mm" cellspacing="0" cellpadding="0">
    <tr><td class="footer-td">N°Inscription : {{ $inscription->numero_dossier }}</td></tr>
  </table>

  <table class="bande-table" cellspacing="0" cellpadding="0">
    <tr><td class="b-vert"></td><td class="b-jaune"></td><td class="b-rouge"></td></tr>
  </table>

</div>

{{-- ══════════ VERSO ══════════ --}}
<div class="card-verso">

  <table class="bande-table" cellspacing="0" cellpadding="0">
    <tr><td class="b-vert"></td><td class="b-jaune"></td><td class="b-rouge"></td></tr>
  </table>

  <table width="85.6mm" cellspacing="0" cellpadding="0">
    <tr>
      <td style="padding:3.5mm 4mm 0 4mm;text-align:center;">

        <div class="verso-school-name">{{ $inscription->classe?->ecole ?? 'École' }}</div>
        <div class="verso-sub">Cotonou, Bénin &nbsp;·&nbsp; Tél : +229 00 00 00 00</div>
        <div class="verso-annee">Année scolaire : {{ $inscription->annee_scolaire }}</div>

        <table width="85.6mm" cellspacing="0" cellpadding="0">
          <tr><td style="padding:2mm 27mm;">
            <table width="100%" cellspacing="0" cellpadding="0">
              <tr><td style="height:0.2mm;background:#cccccc;font-size:0;line-height:0;"></td></tr>
            </table>
          </td></tr>
        </table>

        <div class="verso-dir-label">Directeur de l'établissement</div>
        <div class="verso-dir-name">{{ $inscription->classe?->directeur ?? 'M. Prénom NOM' }}</div>
        <div class="verso-sig-label">Signature &amp; Cachet</div>

        <table width="28mm" cellspacing="0" cellpadding="0" align="center">
          <tr><td style="height:10mm;border-bottom:0.3mm solid #333333;"></td></tr>
        </table>

      </td>
    </tr>
  </table>

  <table class="bande-table" cellspacing="0" cellpadding="0">
    <tr><td class="b-vert"></td><td class="b-jaune"></td><td class="b-rouge"></td></tr>
  </table>

</div>

</body>
</html>