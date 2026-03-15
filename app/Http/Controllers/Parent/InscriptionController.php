<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ParentModel;
use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class InscriptionController extends Controller
{


    public function index()
    {
        $user   = Auth::user();
        $parent = $user->parentModel;

        // Enfants avec inscription et classe
        $eleves = $parent
            ->eleves()
            ->with(['inscription', 'inscription.classe'])
            ->get();

        // 5 dernières notifications
        $notifications = $parent
            ->notifications()
            ->latest()
            ->take(5)
            ->get();

        // Prochaine échéance impayée
        $prochaine_echeance = $parent
            ->echeances()
            ->where('statut', 'impayee')
            ->where('date_limite', '>=', now())
            ->orderBy('date_limite')
            ->first();

        return view('parent.dashboard', compact(
            'parent',
            'eleves',
            'notifications',
            'prochaine_echeance'
        ));
    }
    public function showRegister()
    {
        return view('auth.register');
    }


    // ──────────────────────────────────────────────────────────
    // POST /parent/inscription
    // Traitement complet du formulaire
    // ──────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        // ── 1. VALIDATION ─────────────────────────────────────

        Log::info("rentre");
        $request->validate([
            // Infos parent
            'civilite'              => ['nullable', 'in:M.,Mme,Dr'],
            'parent_nom'            => ['required', 'string', 'max:100'],
            'parent_prenom'         => ['required', 'string', 'max:100'],
            'parent_email'          => ['required', 'email', 'max:200'],
            'parent_telephone'      => ['required', 'string', 'max:20'],
            'parent_telephone2'     => ['nullable', 'string', 'max:20'],
            'lien_parente'          => ['required', 'in:Père,Mère,Tuteur légal,Grand-parent,Autre'],
            'parent_adresse'        => ['required', 'string', 'max:255'],
            'parent_ville'          => ['required', 'string', 'max:100'],
            'parent_arrondissement' => ['nullable', 'string', 'max:100'],
            'parent_code_postal'    => ['nullable', 'string', 'max:20'],

            // Infos élève
            'eleve_nom'             => ['required', 'string', 'max:100'],
            'eleve_prenom'          => ['required', 'string', 'max:100'],
            'eleve_date_naissance'  => ['required', 'date', 'before:today'],
            'eleve_lieu_naissance'  => ['required', 'string', 'max:100'],
            'eleve_sexe'            => ['required', 'in:M,F'],
            'eleve_nationalite'     => ['nullable', 'string', 'max:100'],
            'eleve_acte_naissance'  => ['nullable', 'string', 'max:100'],
            'niveau_souhaite'       => ['required', 'string', 'max:100'],
            'ancienne_ecole'        => ['nullable', 'string', 'max:200'],
            'derniere_classe'       => ['nullable', 'string', 'max:50'],
            'resultat_precedent'    => ['nullable', 'in:Admis(e),Ajourné(e),1ère scolarisation'],
            'groupe_sanguin'        => ['nullable', 'in:A+,A-,B+,B-,O+,O-,AB+,AB-'],
            'infos_medicales'       => ['nullable', 'string', 'max:1000'],

            // Photo élève (optionnelle)
            'eleve_photo'           => ['nullable', 'image', 'max:2048'], // 2 Mo

            // Documents
            'doc_acte_naissance'    => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'doc_bulletin'          => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'doc_certificat_med'    => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'doc_piece_identite'    => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'doc_photo_identite'    => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],

            // Conditions
            'conditions'            => ['required', 'accepted'],
        ], [
            // Messages personnalisés
            'parent_nom.required'          => 'Le nom du parent est obligatoire.',
            'parent_prenom.required'       => 'Le prénom du parent est obligatoire.',
            'parent_email.required'        => "L'adresse e-mail est obligatoire.",
            'parent_email.email'           => "L'adresse e-mail n'est pas valide.",
            'parent_telephone.required'    => 'Le téléphone est obligatoire.',
            'lien_parente.required'        => 'Le lien de parenté est obligatoire.',
            'parent_adresse.required'      => "L'adresse est obligatoire.",
            'parent_ville.required'        => 'La ville est obligatoire.',
            'eleve_nom.required'           => "Le nom de l'élève est obligatoire.",
            'eleve_prenom.required'        => "Le prénom de l'élève est obligatoire.",
            'eleve_date_naissance.required' => 'La date de naissance est obligatoire.',
            'eleve_date_naissance.before'  => 'La date de naissance doit être dans le passé.',
            'eleve_lieu_naissance.required' => 'Le lieu de naissance est obligatoire.',
            'eleve_sexe.required'          => 'Le sexe est obligatoire.',
            'niveau_souhaite.required'     => 'Le niveau souhaité est obligatoire.',
            'doc_acte_naissance.required'  => "L'extrait d'acte de naissance est obligatoire.",
            'doc_piece_identite.required'  => "La pièce d'identité du parent est obligatoire.",
            'doc_photo_identite.required'  => "La photo d'identité de l'élève est obligatoire.",
            'conditions.accepted'          => 'Vous devez accepter les conditions.',
        ]);

        // ── 2. TOUT DANS UNE TRANSACTION ──────────────────────
        try {
            DB::beginTransaction();

            // ── a) Compte User ─────────────────────────────────
            // Si l'email existe déjà → on lie au compte existant
            // Sinon → on crée un nouveau compte avec mdp auto
            $user = User::where('email', $request->parent_email)->first();

            if (!$user) {
                // Générer mot de passe aléatoire 10 caractères
                $motDePasse = Str::random(10);

                $user = User::create([
                    'name'     => $request->parent_prenom . ' ' . strtoupper($request->parent_nom),
                    'email'    => $request->parent_email,
                    'password' => Hash::make($motDePasse),
                    'role'     => 'parent',
                    'actif'    => true,
                ]);

                // TODO Sprint 5 : envoyer le mot de passe par email
                // Mail::to($user->email)->send(new CompteCreeMail($user, $motDePasse));

                // Pour l'instant : stocker en session pour affichage
                session(['mdp_genere' => $motDePasse]);
                Log::info('[INSCRIPTION] ==============================');
                Log::info('[INSCRIPTION] EMAIL      : ' . $request->parent_email);
                Log::info('[INSCRIPTION] MOT PASSE  : ' . $motDePasse);
                Log::info('[INSCRIPTION] ==============================');
            }

            // ── b) Profil ParentModel ──────────────────────────
            // Un seul profil parent par user_id
            $parent = ParentModel::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'civilite'              => $request->civilite,
                    'nom'                   => strtoupper($request->parent_nom),
                    'prenom'                => $request->parent_prenom,
                    'lien_parente'          => $request->lien_parente,
                    'telephone'             => $request->parent_telephone,
                    'telephone_secondaire'  => $request->parent_telephone2,
                    'adresse'               => $request->parent_adresse,
                    'ville'                 => $request->parent_ville,
                    'arrondissement'        => $request->parent_arrondissement,
                    'code_postal'           => $request->parent_code_postal,
                ]
            );

            // ── c) Photo de l'élève ────────────────────────────
            $photoPath = null;
            if ($request->hasFile('eleve_photo')) {
                $photoPath = $request->file('eleve_photo')
                    ->store('photos/eleves', 'public');
            }

            // ── d) Eleve ───────────────────────────────────────
            $eleve = Eleve::create([
                'parent_id'             => $parent->id,
                'nom'                   => strtoupper($request->eleve_nom),
                'prenom'                => $request->eleve_prenom,
                'date_naissance'        => $request->eleve_date_naissance,
                'lieu_naissance'        => $request->eleve_lieu_naissance,
                'sexe'                  => $request->eleve_sexe,
                'nationalite'           => $request->eleve_nationalite ?? 'Béninoise',
                'numero_acte_naissance' => $request->eleve_acte_naissance,
                'photo'                 => $photoPath,
                'niveau_souhaite'       => $request->niveau_souhaite,
                'ancienne_ecole'        => $request->ancienne_ecole,
                'derniere_classe'       => $request->derniere_classe,
                'resultat_precedent'    => $request->resultat_precedent,
                'groupe_sanguin'        => $request->groupe_sanguin,
                'infos_medicales'       => $request->infos_medicales,
            ]);

            // ── e) Inscription ─────────────────────────────────
            $inscription = Inscription::create([
                'eleve_id'      => $eleve->id,
                'classe_id'     => null, // affectée plus tard par l'admin
                'annee_scolaire' => config('app.annee_scolaire', '2025-2026'),
                'type'          => 'nouvelle',
                'statut'        => 'en_attente',
                // numero_dossier généré automatiquement dans le booted() du model
            ]);

            // ── f) Documents uploadés ──────────────────────────
            $docs = [
                'doc_acte_naissance'  => 'acte_naissance',
                'doc_bulletin'        => 'bulletin',
                'doc_certificat_med'  => 'certificat_medical',
                'doc_piece_identite'  => 'piece_identite_parent',
                'doc_photo_identite'  => 'photo_identite',
            ];

            foreach ($docs as $champ => $type) {
                if ($request->hasFile($champ)) {
                    $fichier   = $request->file($champ);
                    $chemin    = $fichier->store('documents/inscriptions/' . $inscription->id, 'public');

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

            // ── g) Connexion automatique du parent ─────────────
            if (!Auth::check()) {
                Auth::login($user);
            }

            // ── h) Redirection avec succès ─────────────────────
            return redirect()->route('login');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la soumission. Veuillez réessayer. (' . $e->getMessage() . ')');
        }
    }

    // ──────────────────────────────────────────────────────────
    // GET /parent/inscription/{id}
    // Voir le détail d'un dossier
    // ──────────────────────────────────────────────────────────
    public function show(int $id)
    {
        $parent = Auth::user()->parentModel;

        // Sécurité : le parent ne peut voir que ses propres dossiers
        $inscription = Inscription::with(['eleve', 'classe', 'documents'])
            ->whereHas('eleve', fn($q) => $q->where('parent_id', $parent->id))
            ->findOrFail($id);

        // Téléchargement PDF si demandé
        if (request('dl') === 'fiche') {
            // TODO Sprint 4 : return $this->genererFiche($inscription);
        }
        if (request('dl') === 'carte') {
            // TODO Sprint 4 : return $this->genererCarte($inscription);
        }

        return view('parent.inscription.show', compact('inscription'));
    }
}
