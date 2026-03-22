<?php
//==========================================================================
// app/Http/Controllers/Admin/ClasseController.php
//==========================================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    // ── GET /admin/classes ────────────────────────────────────
    public function index()
    {
        $annee = config('app.annee_scolaire', '2025-2026');

        $classes = Classe::where('annee_scolaire', $annee)
            ->withCount(['inscriptions as nb_eleves' => fn($q) => $q->where('statut', 'validee')])
            ->orderBy('niveau')
            ->orderBy('nom')
            ->get();

        return view('admin.classes.index', compact('classes', 'annee'));
    }

    // ── GET /admin/classes/create ─────────────────────────────
    public function create()
    {
        return view('admin.classes.create');
    }

    // ── POST /admin/classes ───────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'nom'                   => ['required', 'string', 'max:50'],
            'niveau'                => ['required', 'string', 'max:100'],
            'capacite_max'          => ['required', 'integer', 'min:1', 'max:100'],
            'enseignant_responsable'=> ['nullable', 'string', 'max:150'],
        ], [
            'nom.required'          => 'Le nom de la classe est obligatoire.',
            'niveau.required'       => 'Le niveau est obligatoire.',
            'capacite_max.required' => 'La capacité maximale est obligatoire.',
            'capacite_max.min'      => 'La capacité doit être au moins 1.',
        ]);

        $annee = config('app.annee_scolaire', '2025-2026');

        // Vérifier doublon
        $existe = Classe::where('nom', $request->nom)
            ->where('annee_scolaire', $annee)
            ->exists();

        if ($existe) {
            return back()->withInput()
                ->with('error', 'Une classe avec ce nom existe déjà pour cette année.');
        }

        Classe::create([
            'nom'                    => $request->nom,
            'niveau'                 => $request->niveau,
            'annee_scolaire'         => $annee,
            'capacite_max'           => $request->capacite_max,
            'enseignant_responsable' => $request->enseignant_responsable,
            'active'                 => true,
        ]);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Classe ' . $request->nom . ' créée avec succès.');
    }

    // ── GET /admin/classes/{id} ───────────────────────────────
    public function show(int $id)
    {
        $classe = Classe::with([
            'inscriptions' => fn($q) => $q->where('statut', 'validee'),
            'inscriptions.eleve',
            'inscriptions.eleve.parent',
        ])->findOrFail($id);

        return view('admin.classes.show', compact('classe'));
    }

    // ── GET /admin/classes/{id}/edit ──────────────────────────
    public function edit(int $id)
    {
        $classe = Classe::findOrFail($id);
        return view('admin.classes.edit', compact('classe'));
    }

    // ── PUT /admin/classes/{id} ───────────────────────────────
    public function update(Request $request, int $id)
    {
        $request->validate([
            'nom'                   => ['required', 'string', 'max:50'],
            'niveau'                => ['required', 'string', 'max:100'],
            'capacite_max'          => ['required', 'integer', 'min:1', 'max:100'],
            'enseignant_responsable'=> ['nullable', 'string', 'max:150'],
            'active'                => ['boolean'],
        ]);

        $classe = Classe::findOrFail($id);
        $classe->update([
            'nom'                    => $request->nom,
            'niveau'                 => $request->niveau,
            'capacite_max'           => $request->capacite_max,
            'enseignant_responsable' => $request->enseignant_responsable,
            'active'                 => $request->boolean('active', true),
        ]);

        return redirect()->route('admin.classes.index')
            ->with('success', 'Classe ' . $classe->nom . ' mise à jour.');
    }

    // ── DELETE /admin/classes/{id} ────────────────────────────
    public function destroy(int $id)
    {
        $classe = Classe::withCount(['inscriptions as nb_eleves' => fn($q) => $q->where('statut', 'validee')])
            ->findOrFail($id);

        if ($classe->nb_eleves > 0) {
            return back()->with('error', 'Impossible de supprimer une classe qui contient des élèves.');
        }

        $nom = $classe->nom;
        $classe->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', 'Classe ' . $nom . ' supprimée.');
    }
}