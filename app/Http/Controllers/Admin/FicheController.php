<?php
//==========================================================================
// app/Http/Controllers/Admin/FicheController.php
//
// Prérequis : composer require barryvdh/laravel-dompdf
//==========================================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inscription;
use App\Models\Eleve;
use App\Models\Classe;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class FicheController extends Controller
{
    // ── GET /admin/fiches ─────────────────────────────────────
    // Page de gestion des fiches (liste + filtres)
    public function index(Request $request)
    {
        $annee  = config('app.annee_scolaire', '2025-2026');
        $search = $request->get('search');
        $classe = $request->get('classe_id');

        $query = Inscription::with(['eleve', 'eleve.parent', 'classe'])
            ->where('annee_scolaire', $annee)
            ->where('statut', 'validee')
            ->latest();

        if ($search) {
            $query->whereHas('eleve', fn($q) => $q
                ->where('nom',    'like', "%$search%")
                ->orWhere('prenom', 'like', "%$search%")
            );
        }

        if ($classe) {
            $query->where('classe_id', $classe);
        }

        $inscriptions = $query->paginate(20)->withQueryString();

        $classes = Classe::where('annee_scolaire', $annee)
            ->where('active', true)
            ->orderBy('nom')
            ->get();

        return view('admin.fiches.index', compact(
            'inscriptions', 'classes', 'annee', 'search', 'classe'
        ));
    }

    // ── GET /admin/fiches/{id}/fiche ──────────────────────────
    // Télécharger la fiche d'inscription PDF d'un élève
    public function fiche(int $id)
    {
        $inscription = Inscription::with([
            'eleve', 'eleve.parent', 'eleve.parent.user',
            'classe', 'documents'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('admin.fiches.pdf-fiche', compact('inscription'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
            ]);

        $filename = 'fiche_' . $inscription->numero_dossier . '_' .
            strtolower($inscription->eleve->nom) . '.pdf';

        return $pdf->download($filename);
    }

    // ── GET /admin/fiches/{id}/carte ──────────────────────────
    // Télécharger la carte de scolarité PDF
    public function carte(int $id)
    {
        $inscription = Inscription::with([
            'eleve', 'classe'
        ])->where('statut', 'validee')->findOrFail($id);

        $pdf = Pdf::loadView('admin.fiches.pdf-carte', compact('inscription'))
            ->setPaper([0, 0, 243, 153], 'landscape') // taille carte (85x54mm)
            ->setOptions([
                'defaultFont'          => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
            ]);

        $filename = 'carte_' . strtolower($inscription->eleve->nom) .
            '_' . $inscription->annee_scolaire . '.pdf';

        return $pdf->download($filename);
    }

    // ── GET /admin/fiches/classe/{classeId} ───────────────────
    // Télécharger toutes les fiches d'une classe en un seul PDF
    public function ficheClasse(int $classeId)
    {
        $inscriptions = Inscription::with([
            'eleve', 'eleve.parent', 'eleve.parent.user', 'classe', 'documents'
        ])
        ->where('classe_id', $classeId)
        ->where('statut', 'validee')
        ->orderBy(fn($q) => $q->getRelation('eleve')->orderBy('nom'))
        ->get();

        if ($inscriptions->isEmpty()) {
            return back()->with('error', 'Aucun élève dans cette classe.');
        }

        $classe = Classe::findOrFail($classeId);

        $pdf = Pdf::loadView('admin.fiches.pdf-fiche-classe', compact('inscriptions', 'classe'))
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'          => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
            ]);

        $filename = 'fiches_classe_' . strtolower($classe->nom) . '.pdf';

        return $pdf->download($filename);
    }
}