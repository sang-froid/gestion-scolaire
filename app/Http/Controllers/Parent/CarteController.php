<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CarteController extends Controller
{
    // Liste des enfants
    public function index()
    {
        $parent = Auth::user()->parentModel;

        $eleves = $parent->eleves()->with('inscription.classe')->get();

        return view('parent.cartes.index', compact('eleves'));
    }

    // Afficher carte
    public function show(Eleve $eleve)
    {
        $this->authorizeEleve($eleve);

        $eleve->load('inscription.classe', 'parent');

        return view('parent.cartes.show', compact('eleve'));
    }

    // Télécharger le PDF (Utilise la vue PDF simplifiée)
    public function download(Eleve $eleve)
    {
        $this->authorizeEleve($eleve);

        $eleve->load(['inscription.classe', 'parent']);

        // On utilise la vue 'parent.cartes.pdf' (celle que je vous ai donnée plus haut)
        $pdf = Pdf::loadView('parent.cartes.pdf', compact('eleve'));

        /* Taille standard CR80 (Carte de crédit) en points : 
           85.6mm -> ~242.6pt
           53.98mm -> ~153pt
        */
        $pdf->setPaper([0, 0, 242.65, 153.07], 'portrait');

        return $pdf->download('carte_'.$eleve->matricule.'.pdf');
    }

    private function authorizeEleve($eleve)
    {
        if ($eleve->parent_id !== Auth::user()->parentModel->id) {
            abort(403);
        }
    }
}
