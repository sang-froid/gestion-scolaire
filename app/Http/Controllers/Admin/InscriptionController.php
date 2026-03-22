<?php
//==========================================================================
// app/Http/Controllers/Admin/InscriptionController.php
//==========================================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inscription;
use App\Models\Classe;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InscriptionController extends Controller
{
    public function __construct(private MailService $mailService) {}

    // ── GET /admin/inscriptions ───────────────────────────────
    public function index(Request $request)
    {
        $annee  = config('app.annee_scolaire', '2025-2026');
        $statut = $request->get('statut', 'tous');
        $search = $request->get('search');

        $query = Inscription::with(['eleve', 'eleve.parent', 'classe'])
            ->where('annee_scolaire', $annee)
            ->latest();

        if ($statut !== 'tous') {
            $query->where('statut', $statut);
        }

        if ($request->get('affectation') === 'non') {
            $query->where('statut', 'validee')->whereNull('classe_id');
        }

        if ($search) {
            $query->whereHas('eleve', function ($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhere('prenom', 'like', "%$search%")
                  ->orWhere('numero_dossier', 'like', "%$search%");
            });
        }

        $inscriptions = $query->paginate(15)->withQueryString();

        // Stats pour les filtres
        $stats = [
            'tous'       => Inscription::where('annee_scolaire', $annee)->count(),
            'en_attente' => Inscription::where('annee_scolaire', $annee)->where('statut', 'en_attente')->count(),
            'validee'    => Inscription::where('annee_scolaire', $annee)->where('statut', 'validee')->count(),
            'refusee'    => Inscription::where('annee_scolaire', $annee)->where('statut', 'refusee')->count(),
        ];

        return view('admin.inscriptions.index', compact('inscriptions', 'stats', 'statut', 'search', 'annee'));
    }

    // ── GET /admin/inscriptions/{id} ──────────────────────────
    public function show(int $id)
    {
        $inscription = Inscription::with([
            'eleve', 'eleve.parent', 'eleve.parent.user',
            'classe', 'documents', 'validePar'
        ])->findOrFail($id);

        // Classes disponibles pour l'affectation
        $classes = Classe::where('annee_scolaire', config('app.annee_scolaire', '2025-2026'))
            ->where('active', true)
            ->get()
            ->filter(fn($c) => !$c->estPleine());

        return view('admin.inscriptions.show', compact('inscription', 'classes'));
    }

    // ── POST /admin/inscriptions/{id}/valider ─────────────────
    public function valider(int $id)
    {
        $inscription = Inscription::with(['eleve.parent.user'])->findOrFail($id);

        if ($inscription->statut !== 'en_attente') {
            return back()->with('error', 'Ce dossier ne peut plus être modifié.');
        }

        $inscription->update([
            'statut'     => 'validee',
            'validee_le' => now(),
            'validee_par'=> Auth::id(),
            'motif_refus'=> null,
        ]);

        // Envoi email décision
        $this->mailService->envoyerDecision($inscription->fresh(['eleve.parent.user']));

        return back()->with('success', 'Dossier #' . $inscription->numero_dossier . ' validé avec succès.');
    }

    // ── POST /admin/inscriptions/{id}/refuser ─────────────────
    public function refuser(Request $request, int $id)
    {
        $request->validate([
            'motif_refus' => ['required', 'string', 'max:500'],
        ], [
            'motif_refus.required' => 'Le motif du refus est obligatoire.',
        ]);

        $inscription = Inscription::with(['eleve.parent.user'])->findOrFail($id);

        if ($inscription->statut !== 'en_attente') {
            return back()->with('error', 'Ce dossier ne peut plus être modifié.');
        }

        $inscription->update([
            'statut'      => 'refusee',
            'motif_refus' => $request->motif_refus,
            'validee_le'  => now(),
            'validee_par' => Auth::id(),
        ]);

        // Envoi email décision
        $this->mailService->envoyerDecision($inscription->fresh(['eleve.parent.user']));

        return back()->with('success', 'Dossier #' . $inscription->numero_dossier . ' refusé.');
    }

    // ── POST /admin/inscriptions/{id}/affecter ────────────────
    public function affecter(Request $request, int $id)
    {
        $request->validate([
            'classe_id' => ['required', 'exists:classes,id'],
        ], [
            'classe_id.required' => 'Veuillez sélectionner une classe.',
            'classe_id.exists'   => 'Classe introuvable.',
        ]);

        $inscription = Inscription::with(['eleve.parent.user', 'classe'])->findOrFail($id);

        if ($inscription->statut !== 'validee') {
            return back()->with('error', 'Seuls les dossiers validés peuvent être affectés.');
        }

        $classe = Classe::findOrFail($request->classe_id);

        if ($classe->estPleine()) {
            return back()->with('error', 'La classe ' . $classe->nom . ' est pleine.');
        }

        $inscription->update(['classe_id' => $classe->id]);

        // Envoi email affectation
        $this->mailService->envoyerAffectationClasse(
            $inscription->fresh(['eleve.parent.user', 'classe'])
        );

        return back()->with('success',
            $inscription->eleve->prenom . ' a été affecté(e) à la classe ' . $classe->nom . '.'
        );
    }
}