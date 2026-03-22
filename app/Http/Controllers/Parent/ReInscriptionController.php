<?php
//==========================================================================


namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReInscriptionController extends Controller
{
    // ──────────────────────────────────────────────────────────
    // GET /parent/reinscription/{eleve}
    // Affiche le formulaire de réinscription prérempli
    // ──────────────────────────────────────────────────────────
    public function create(int $eleveId)
    {
        $parent = Auth::user()->parentModel;

        // Sécurité : l'élève appartient bien à ce parent
        $eleve = Eleve::with(['inscription', 'inscription.classe'])
            ->where('parent_id', $parent->id)
            ->findOrFail($eleveId);

        // Vérifier qu'il n'est pas déjà réinscrit pour cette année
        $anneeCourante = config('app.annee_scolaire', '2025-2026');
        $dejaInscrit = Inscription::where('eleve_id', $eleve->id)
            ->where('annee_scolaire', $anneeCourante)
            ->whereIn('statut', ['en_attente', 'validee'])
            ->exists();

        if ($dejaInscrit) {
            return redirect()
                ->route('parent.dashboard')
                ->with('warning', $eleve->prenom . ' est déjà inscrit(e) pour l\'année ' . $anneeCourante . '.');
        }

        return view('parent.create', compact('eleve', 'parent', 'anneeCourante'));
    }

    // ──────────────────────────────────────────────────────────
    // POST /parent/reinscription/{eleve}
    // Traitement de la réinscription
    // ──────────────────────────────────────────────────────────
    public function store(Request $request, int $eleveId)
    {
        Log::info('[REINSCRIPTION] store() appelé pour eleve_id=' . $eleveId);

        $parent = Auth::user()->parentModel;

        // Sécurité
        $eleve = Eleve::where('parent_id', $parent->id)->findOrFail($eleveId);

        // Vérification doublon
        $anneeCourante = config('app.annee_scolaire', '2025-2026');
        $dejaInscrit = Inscription::where('eleve_id', $eleve->id)
            ->where('annee_scolaire', $anneeCourante)
            ->whereIn('statut', ['en_attente', 'validee'])
            ->exists();

        if ($dejaInscrit) {
            return redirect()
                ->route('parent.dashboard')
                ->with('warning', $eleve->prenom . ' est déjà inscrit(e) pour cette année.');
        }

        // ── VALIDATION ────────────────────────────────────────
        $request->validate([
            // Niveau (obligatoire)
            'niveau_souhaite'       => ['required', 'string', 'max:100'],

            // Infos modifiables élève
            'eleve_photo'           => ['nullable', 'image', 'max:2048'],
            'infos_medicales'       => ['nullable', 'string', 'max:1000'],
            'groupe_sanguin'        => ['nullable', 'string', 'max:5'],

            // Infos modifiables parent
            'parent_telephone'      => ['required', 'string', 'max:20'],
            'parent_telephone2'     => ['nullable', 'string', 'max:20'],
            'parent_adresse'        => ['required', 'string', 'max:255'],
            'parent_ville'          => ['required', 'string', 'max:100'],
            'parent_arrondissement' => ['nullable', 'string', 'max:100'],

            // Documents (tous optionnels en réinscription)
            'doc_acte_naissance'    => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'doc_bulletin'          => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'doc_certificat_med'    => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'doc_piece_identite'    => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'doc_photo_identite'    => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],

            'conditions'            => ['required', 'accepted'],
        ], [
            'niveau_souhaite.required'  => 'Le niveau souhaité est obligatoire.',
            'parent_telephone.required' => 'Le téléphone est obligatoire.',
            'parent_adresse.required'   => "L'adresse est obligatoire.",
            'parent_ville.required'     => 'La ville est obligatoire.',
            'conditions.accepted'       => 'Vous devez accepter les conditions.',
        ]);

        try {
            DB::beginTransaction();

            // ── 1. Mettre à jour la photo de l'élève si nouvelle ──
            if ($request->hasFile('eleve_photo')) {
                $photoPath = $request->file('eleve_photo')
                    ->store('photos/eleves', 'public');
                $eleve->photo = $photoPath;
            }

            // ── 2. Mettre à jour les infos modifiables de l'élève ──
            $eleve->update([
                'niveau_souhaite' => $request->niveau_souhaite,
                'infos_medicales' => $request->infos_medicales,
                'groupe_sanguin'  => $request->groupe_sanguin ?? $eleve->groupe_sanguin,
                'photo'           => $eleve->photo,
            ]);
            Log::info('[REINSCRIPTION] Élève mis à jour : ' . $eleve->id);

            // ── 3. Mettre à jour les infos du parent ──────────────
            $parent->update([
                'telephone'            => $request->parent_telephone,
                'telephone_secondaire' => $request->parent_telephone2,
                'adresse'              => $request->parent_adresse,
                'ville'                => $request->parent_ville,
                'arrondissement'       => $request->parent_arrondissement,
            ]);
            Log::info('[REINSCRIPTION] Parent mis à jour : ' . $parent->id);

            // ── 4. Créer la nouvelle inscription ──────────────────
            $inscription = Inscription::create([
                'eleve_id'       => $eleve->id,
                'classe_id'      => null,
                'annee_scolaire' => $anneeCourante,
                'type'           => 'reinscription',
                'statut'         => 'en_attente',
            ]);
            Log::info('[REINSCRIPTION] Inscription créée : ' . $inscription->numero_dossier);

            // ── 5. Sauvegarder les nouveaux documents si fournis ──
            $docs = [
                'doc_acte_naissance'    => 'acte_naissance',
                'doc_bulletin'          => 'bulletin',
                'doc_certificat_med'    => 'certificat_medical',
                'doc_piece_identite'    => 'piece_identite_parent',
                'doc_photo_identite'    => 'photo_identite',
            ];

            foreach ($docs as $champ => $type) {
                if ($request->hasFile($champ)) {
                    $fichier = $request->file($champ);
                    $chemin  = $fichier->store(
                        'documents/inscriptions/' . $inscription->id, 'public'
                    );
                    Document::create([
                        'inscription_id' => $inscription->id,
                        'type'           => $type,
                        'nom_fichier'    => $fichier->getClientOriginalName(),
                        'chemin_fichier' => $chemin,
                        'mime_type'      => $fichier->getMimeType(),
                        'taille'         => $fichier->getSize(),
                        'genere_systeme' => false,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('parent.dashboard')
                ->with('success',
                    'Réinscription de ' . $eleve->prenom .
                    ' soumise avec succès ! Dossier #' . $inscription->numero_dossier
                );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[REINSCRIPTION] ERREUR : ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la réinscription : ' . $e->getMessage());
        }
    }
}
