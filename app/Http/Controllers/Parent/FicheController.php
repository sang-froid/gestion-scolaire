<?php
//==========================================================================
// app/Http/Controllers/Parent/FicheController.php
//
// Même logique que Admin\FicheController mais avec vérification
// que l'inscription appartient bien au parent connecté
//==========================================================================

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Inscription;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class FicheController extends Controller
{
    // ── GET /parent/inscription/{id}/fiche ───────────────────
    public function fiche(int $id)
    {
        $parent = Auth::user()->parentModel;

        // Sécurité : l'inscription appartient à ce parent
        $inscription = Inscription::with([
            'eleve', 'eleve.parent', 'eleve.parent.user',
            'classe', 'documents'
        ])
        ->whereHas('eleve', fn($q) => $q->where('parent_id', $parent->id))
        ->where('statut', 'validee') // seulement les dossiers validés
        ->findOrFail($id);

        $pdf = Pdf::loadView('admin.fiches.pdf-fiche', compact('inscription'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'          => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
            ]);

        $filename = 'fiche_' . $inscription->numero_dossier . '_' .
            strtolower($inscription->eleve->nom) . '.pdf';

        return $pdf->download($filename);
    }

    // ── GET /parent/inscription/{id}/carte ───────────────────
    public function carte(int $id)
    {
        $parent = Auth::user()->parentModel;

        // Sécurité
        $inscription = Inscription::with(['eleve', 'classe'])
            ->whereHas('eleve', fn($q) => $q->where('parent_id', $parent->id))
            ->where('statut', 'validee')
            ->whereNotNull('classe_id') // seulement si classe affectée
            ->findOrFail($id);

        $pdf = Pdf::loadView('admin.fiches.pdf-carte', compact('inscription'))
            ->setPaper([0, 0, 242.56, 306.14], 'portrait')
            ->setOptions([
                'defaultFont'          => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
                'dpi'                  => 96,
            ]);

        $filename = 'carte_' . strtolower($inscription->eleve->nom) .
            '_' . $inscription->annee_scolaire . '.pdf';

        return $pdf->download($filename);
    }
}