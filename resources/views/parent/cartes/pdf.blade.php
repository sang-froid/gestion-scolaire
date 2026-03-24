<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        /* Configuration de la page PDF */
        @page {
            margin: 0;
            size: 85.6mm 54mm; /* Taille standard carte de crédit */
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
        }

        .page-break {
            page-break-after: always;
        }

        /* ─── CARTE COMMUNE ─── */
        .sc-card {
            width: 85.6mm;
            height: 54mm;
            position: relative;
            overflow: hidden;
        }

        /* ─── RECTO ─── */
        .sc-recto {
            background-color: #ffffff;
        }

        .sc-recto-strip {
            background: #0D1B2A;
            height: 14mm;
            color: #ffffff;
            padding: 0 4mm;
            width: 100%;
        }

        .sc-school-name {
            font-size: 10px;
            font-weight: bold;
            margin-top: 3mm;
            display: inline-block;
        }

        .sc-school-year {
            font-size: 8px;
            color: #cbd5e1;
        }

        .sc-body {
            padding: 3mm 4mm;
        }

        /* Simulation de Flexbox avec des tables pour le PDF */
        .table-layout {
            width: 100%;
            border-collapse: collapse;
        }

        .photo-cell {
            width: 22mm;
            vertical-align: top;
        }

        .sc-photo-wrap {
            width: 20mm;
            height: 24mm;
            border: 1px solid #E2E8F0;
            border-radius: 2mm;
            background: #F1F5F9;
        }

        .info-cell {
            vertical-align: top;
            padding-left: 3mm;
        }

        .sc-name {
            font-size: 11px;
            color: #0D1B2A;
            font-weight: bold;
            margin-bottom: 2mm;
            text-transform: uppercase;
        }

        .sc-row {
            font-size: 8px;
            margin-bottom: 1mm;
        }

        .sc-row-label {
            color: #94A3B8;
            width: 15mm;
            display: inline-block;
        }

        .sc-row-val {
            color: #1E293B;
            font-weight: bold;
        }

        .sc-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            border-top: 1px dashed #E2E8F0;
            padding: 2mm 4mm;
            font-size: 7px;
            color: #94A3B8;
        }

        /* ─── VERSO ─── */
        .sc-verso {
            background: #0D1B2A;
            color: #ffffff;
            text-align: center;
        }

        .verso-content {
            padding-top: 8mm;
        }

        .sc-verso-logo {
            width: 12mm;
            height: 12mm;
            margin: 0 auto 3mm;
        }

        .sc-verso-school {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 1mm;
        }

        .sc-verso-tagline {
            font-size: 8px;
            opacity: 0.7;
            margin-bottom: 3mm;
        }

        .sc-verso-contact {
            font-size: 7px;
            line-height: 1.5;
            color: #cbd5e1;
        }
    </style>
</head>
<body>

    <div class="sc-card sc-recto">
        <div class="sc-recto-strip">
            <span class="sc-school-name">{{ config('app.nom_ecole', 'Nom de l\'école') }}</span><br>
            <span class="sc-school-year"> Année accadémique : {{ $eleve->inscription?->annee_scolaire ?? '2025-2026' }}</span>
        </div>

        <div class="sc-body">
            <table class="table-layout">
                <tr>
                    <td class="photo-cell">
                        <div class="sc-photo-wrap">
                            @if($eleve->photo)
                                <img src="{{ public_path('storage/'.$eleve->photo) }}" style="width:100%; height:100%; object-fit: cover;">
                            @endif
                        </div>
                    </td>
                    <td class="info-cell">
                        <div class="sc-name">{{ $eleve->nom }} {{ $eleve->prenom }}</div>
                        
                        <div class="sc-row">
                            <span class="sc-row-label">Matricule:</span>
                            <span class="sc-row-val">{{ $eleve->matricule ?? '—' }}</span>
                        </div>
                        <div class="sc-row">
                            <span class="sc-row-label">Classe:</span>
                            <span class="sc-row-val">{{ $eleve->inscription?->classe?->nom ?? '—' }}</span>
                        </div>
                        <div class="sc-row">
                            <span class="sc-row-label">Né(e) le:</span>
                            <span class="sc-row-val">{{ $eleve->date_naissance?->format('d/m/Y') ?? '—' }}</span>
                        </div>
                        <div class="sc-row">
                            <span class="sc-row-label">Parent:</span>
                            <span class="sc-row-val">{{ $eleve->parent->nom ?? '—' }}</span>
                            <span class="sc-row-val">{{ $eleve->parent->prenom ?? '—' }}</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="sc-footer">
            Valide pour l'année scolaire {{ $eleve->inscription?->annee_scolaire ?? '—' }}
        </div>
    </div>

    <div class="page-break"></div>

    <div class="sc-card sc-verso">
        <div class="verso-content">
            <div class="sc-verso-logo">
                <img src="{{ public_path(config('app.logo_ecole','images/logo.png')) }}" style="width:100%;">
            </div>
            <div class="sc-verso-school">{{ config('app.nom_ecole') }}</div>
            <div class="sc-verso-tagline">Carte officielle d'identification scolaire</div>
            <div style="width: 20mm; height: 1px; background: rgba(255,255,255,0.2); margin: 2mm auto;"></div>
            <div class="sc-verso-contact">
                Adresse : {{ config('app.adresse_ecole', 'Adresse de l\'école') }}<br>
                Tél: {{ config('app.telephone_ecole', '+229 01 99 99 99') }}<br>
                Email: {{ config('app.email_ecole', 'contact@ecole.bj') }}
            </div>
        </div>
    </div>

</body>
</html>