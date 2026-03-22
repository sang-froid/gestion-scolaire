<?php
//==========================================================================
// app/Http/Controllers/Admin/EleveController.php
//==========================================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use Illuminate\Http\Request;

class EleveController extends Controller
{
    // ── GET /admin/eleves ─────────────────────────────────────
    public function index(Request $request)
    {
        $search  = $request->get('search');
        $niveau  = $request->get('niveau');
        $action  = $request->get('action'); // fiches | cartes

        $query = Eleve::with([
            'parent',
            'inscription',
            'inscription.classe',
        ])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nom',    'like', "%$search%")
                  ->orWhere('prenom', 'like', "%$search%")
                  ->orWhere('matricule', 'like', "%$search%");
            });
        }

        if ($niveau) {
            $query->where('niveau_souhaite', $niveau);
        }

        $eleves = $query->paginate(20)->withQueryString();

        // Niveaux distincts pour le filtre
        $niveaux = Eleve::distinct()->pluck('niveau_souhaite')->filter()->sort()->values();

        return view('admin.eleves.index', compact('eleves', 'search', 'niveau', 'niveaux', 'action'));
    }

    // ── GET /admin/eleves/{id} ────────────────────────────────
    public function show(int $id)
    {
        $eleve = Eleve::with([
            'parent', 'parent.user',
            'inscriptions', 'inscriptions.classe', 'inscriptions.documents',
        ])->findOrFail($id);

        return view('admin.eleves.show', compact('eleve'));
    }
}