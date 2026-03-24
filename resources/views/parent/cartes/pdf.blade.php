<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <style>
        /* Configuration de la page : Format Carte ID standard */
        @page { margin: 0; size: 86mm 54mm; }
        
        body {
            margin: 0; padding: 0;
            font-family: 'Helvetica', sans-serif;
            background-color: #fff;
        }

        .page-break { page-break-after: always; }

        /* Conteneur principal fixe */
        .card-container {
            width: 86mm;
            height: 54mm;
            overflow: hidden;
            position: relative;
        }

        /* ─── RECTO ─── */
        .header-strip {
            background-color: #0D1B2A;
            height: 14mm;
            width: 100%;
        }

        .header-table {
            width: 100%;
            padding: 2mm 4mm;
            border-collapse: collapse;
        }

        .school-logo { width: 10mm; height: 10mm; background: rgba(255,255,255,0.1); border-radius: 2mm; text-align: center; }
        .school-name { font-size: 9px; font-weight: bold; color: #ffffff; line-height: 1.1; }
        .school-year { font-size: 7px; color: rgba(255,255,255,0.6); }
        .badge-eleve {
            background: rgba(255,255,255,0.15);
            color: #fff;
            font-size: 6px;
            padding: 1mm 2.5mm;
            border-radius: 10mm;
            text-transform: uppercase;
        }

        .body-table {
            width: 100%;
            padding: 3mm 4mm;
            border-collapse: collapse;
        }

        .photo-box {
            width: 21mm;
            height: 25mm;
            border: 1pt solid #E2E8F0;
            border-radius: 3mm;
            background: #F1F5F9;
        }

        .info-cell { padding-left: 4mm; vertical-align: top; }
        .student-name {
            font-size: 11px;
            font-weight: bold;
            color: #0D1B2A;
            margin-bottom: 2mm;
            text-transform: uppercase;
        }

        .detail-row { font-size: 8px; margin-bottom: 1mm; }
        .label { color: #94A3B8; width: 15mm; display: inline-block; }
        .value { color: #1E293B; font-weight: bold; }

        .footer-line {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 8mm;
            border-top: 0.5pt dashed #E2E8F0;
            padding: 2mm 4mm 0;
        }

        /* ─── VERSO ─── */
        .verso-bg {
            background-color: #0D1B2A;
            color: #ffffff;
            width: 100%;
            height: 100%;
            text-align: center;
        }

        .verso-content { padding-top: 10mm; }
        .verso-divider { width: 15mm; height: 1pt; background: rgba(255,255,255,0.2); margin: 3mm auto; }
    </style>
</head>
<body>

    <div class="card-container">
        <div class="header-strip">
            <table class="header-table">
                <tr>
                    <td style="width: 12mm;">
                        <div class="school-logo">
                            @if(config('app.logo_ecole'))
                                <img src="{{ public_path(config('app.logo_ecole')) }}" style="width: 100%; height: 100%; border-radius: 1.5mm;">
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="school-name">{{ config('app.nom_ecole', 'ÉCOLE') }}</div>
                        <div class="school-year">{{ $eleve->inscription?->annee_scolaire }}</div>
                    </td>
                    <td style="text-align: right; vertical-align: middle;">
                        <span class="badge-eleve">Élève</span>
                    </td>
                </tr>
            </table>
        </div>

        <table class="body-table">
            <tr>
                <td style="width: 21mm;">
                    <div class="photo-box">
                        @if($eleve->photo)
                            <img src="{{ public_path('storage/'.$eleve->photo) }}" style="width: 100%; height: 100%; border-radius: 2.5mm;">
                        @endif
                    </div>
                </td>
                <td class="info-cell">
                    <div class="student-name">{{ $eleve->nom }} {{ $eleve->prenom }}</div>
                    
                    <div class="detail-row"><span class="label">Matricule</span><span class="value">: {{ $eleve->matricule ?? '—' }}</span></div>
                    <div class="detail-row"><span class="label">Classe</span><span class="value">: {{ $eleve->inscription?->classe?->nom ?? '—' }}</span></div>
                    <div class="detail-row"><span class="label">Né(e) le</span><span class="value">: {{ $eleve->date_naissance?->format('d/m/Y') ?? '—' }}</span></div>
                    <div class="detail-row"><span class="label">Sexe</span><span class="value">: {{ $eleve->sexe ?? '—' }}</span></div>
                    
                    <div style="font-size: 7px; color: #94A3B8; margin-top: 2mm;">
                        Parent : <span style="color: #1E293B;">{{ $eleve->parent->nom_complet }}</span>
                    </div>
                </td>
            </tr>
        </table>

        <div class="footer-line">
            <table style="width: 100%;">
                <tr>
                    <td style="font-size: 7px; color: #94A3B8;">Valide pour l'année {{ $eleve->inscription?->annee_scolaire }}</td>
                    <td style="text-align: right;">
                        <span style="background: #EFF6FF; color: #1D4ED8; font-size: 6px; font-weight: bold; padding: 0.5mm 2mm; border-radius: 5mm;">OFFICIEL</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="page-break"></div>

    <div class="card-container">
        <div class="verso-bg">
            <div class="verso-content">
                @if(config('app.logo_ecole'))
                    <img src="{{ public_path(config('app.logo_ecole')) }}" style="width: 12mm; margin-bottom: 2mm;">
                @endif
                <div style="font-size: 11px; font-weight: bold; letter-spacing: 1px;">{{ config('app.nom_ecole') }}</div>
                <div class="verso-divider"></div>
                <div style="font-size: 7px; opacity: 0.7;">Carte officielle d'identification scolaire</div>
                
                <div style="margin-top: 6mm; font-size: 7px; line-height: 1.5; color: #cbd5e1;">
                    {{ config('app.adresse_ecole', 'Adresse de l\'école') }}<br>
            {{ config('app.tel_ecole', '+XXX XX XX XX XX') }}<br>
            {{ config('app.email_ecole', 'contact@ecole.bj') }}
                </div>
            </div>
        </div>
    </div>

</body>
</html>