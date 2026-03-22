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
use App\Services\MailService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class InscriptionController extends Controller
{
   public function __construct(private MailService $mailService) {}


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

        // Si connecté → on passe les infos parent déjà en BDD
        $parentExistant = null;
        if (Auth::check()) {
            $parentExistant = Auth::user()->parentModel;
        }
        return view('auth.register', compact('parentExistant'));
    }

  
   

      public function indexParentDossier()
    {
        $parent = Auth::user()->parentModel;

        $eleves = Eleve::with([
                'inscriptions',
                'inscriptions.classe',
                'inscription',          // année en cours
                'inscription.classe',
            ])
            ->where('parent_id', $parent->id)
            ->orderBy('prenom')
            ->get();

        return view('parent.mes_inscrit', compact('parent', 'eleves'));
    }

    // ──────────────────────────────────────────────────────────
    // POST /parent/inscription
    // Traitement complet du formulaire
    // ──────────────────────────────────────────────────────────
    //    public function store(Request $request)
    // {
    //     Log::info('[INSCRIPTION] store() — connecté=' . (Auth::check() ? 'oui' : 'non'));

    //     $estConnecte = Auth::check();

    //     // ── RÈGLES DE VALIDATION ──────────────────────────────
    //     // Les infos parent sont obligatoires SEULEMENT si non connecté
    //     $reglesParent = $estConnecte
    //         ? [] // connecté → on ignore les champs parent du formulaire
    //         : [
    //             'civilite'              => ['nullable', 'in:M.,Mme,Dr'],
    //             'parent_nom'            => ['required', 'string', 'max:100'],
    //             'parent_prenom'         => ['required', 'string', 'max:100'],
    //             'parent_email'          => ['required', 'email', 'max:200'],
    //             'parent_telephone'      => ['required', 'string', 'max:20'],
    //             'parent_telephone2'     => ['nullable', 'string', 'max:20'],
    //             'lien_parente'          => ['required', 'string', 'max:50'],
    //             'parent_adresse'        => ['required', 'string', 'max:255'],
    //             'parent_ville'          => ['required', 'string', 'max:100'],
    //             'parent_arrondissement' => ['nullable', 'string', 'max:100'],
    //             'parent_code_postal'    => ['nullable', 'string', 'max:20'],
    //         ];

    //     $request->validate(array_merge($reglesParent, [
    //         // Élève — toujours obligatoire
    //         'eleve_nom'             => ['required', 'string', 'max:100'],
    //         'eleve_prenom'          => ['required', 'string', 'max:100'],
    //         'eleve_date_naissance'  => ['required', 'date', 'before:today'],
    //         'eleve_lieu_naissance'  => ['required', 'string', 'max:100'],
    //         'eleve_sexe'            => ['required', 'in:M,F'],
    //         'eleve_nationalite'     => ['nullable', 'string', 'max:100'],
    //         'eleve_acte_naissance'  => ['nullable', 'string', 'max:100'],
    //         'niveau_souhaite'       => ['required', 'string', 'max:100'],
    //         'ancienne_ecole'        => ['nullable', 'string', 'max:200'],
    //         'derniere_classe'       => ['nullable', 'string', 'max:50'],
    //         'resultat_precedent'    => ['nullable', 'string', 'max:50'],
    //         'groupe_sanguin'        => ['nullable', 'string', 'max:5'],
    //         'infos_medicales'       => ['nullable', 'string', 'max:1000'],
    //         'eleve_photo'           => ['nullable', 'image', 'max:2048'],

    //         // Documents — obligatoires seulement si non connecté
    //         'doc_acte_naissance'  => [$estConnecte ? 'nullable' : 'required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
    //         'doc_bulletin'        => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
    //         'doc_certificat_med'  => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
    //         'doc_piece_identite'  => [$estConnecte ? 'nullable' : 'required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
    //         'doc_photo_identite'  => [$estConnecte ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png', 'max:2048'],

    //         'conditions' => ['required', 'accepted'],
    //     ]), [
    //         'parent_nom.required'           => 'Le nom du parent est obligatoire.',
    //         'parent_prenom.required'        => 'Le prénom du parent est obligatoire.',
    //         'parent_email.required'         => "L'adresse e-mail est obligatoire.",
    //         'parent_email.email'            => "L'adresse e-mail n'est pas valide.",
    //         'parent_telephone.required'     => 'Le téléphone est obligatoire.',
    //         'lien_parente.required'         => 'Le lien de parenté est obligatoire.',
    //         'parent_adresse.required'       => "L'adresse est obligatoire.",
    //         'parent_ville.required'         => 'La ville est obligatoire.',
    //         'eleve_nom.required'            => "Le nom de l'élève est obligatoire.",
    //         'eleve_prenom.required'         => "Le prénom de l'élève est obligatoire.",
    //         'eleve_date_naissance.required' => 'La date de naissance est obligatoire.',
    //         'eleve_date_naissance.before'   => 'La date de naissance doit être dans le passé.',
    //         'eleve_lieu_naissance.required' => 'Le lieu de naissance est obligatoire.',
    //         'eleve_sexe.required'           => 'Le sexe est obligatoire.',
    //         'niveau_souhaite.required'      => 'Le niveau souhaité est obligatoire.',
    //         'doc_acte_naissance.required'   => "L'acte de naissance est obligatoire.",
    //         'doc_piece_identite.required'   => "La pièce d'identité est obligatoire.",
    //         'doc_photo_identite.required'   => "La photo d'identité est obligatoire.",
    //         'conditions.accepted'           => 'Vous devez accepter les conditions.',
    //     ]);

    //     Log::info('[INSCRIPTION] Validation OK');

    //     try {
    //         DB::beginTransaction();

    //         // ── A) Récupérer ou créer User + ParentModel ──────────
    //         if ($estConnecte) {
    //             // Parent déjà connecté → on réutilise son profil
    //             $user   = Auth::user();
    //             $parent = $user->parentModel;
    //             Log::info('[INSCRIPTION] Parent connecté réutilisé : user_id=' . $user->id);

    //         } else {
    //             // Non connecté → créer le compte
    //             $user = User::where('email', $request->parent_email)->first();

    //             if (!$user) {
    //                 $motDePasse = Str::random(10);
    //                 $user = User::create([
    //                     'name'     => $request->parent_prenom . ' ' . strtoupper($request->parent_nom),
    //                     'email'    => $request->parent_email,
    //                     'password' => Hash::make($motDePasse),
    //                     'role'     => 'parent',
    //                     'actif'    => true,
    //                 ]);
    //                 session(['mdp_genere' => $motDePasse]);

    //                 Log::info('[INSCRIPTION] ==============================');
    //                 Log::info('[INSCRIPTION] EMAIL      : ' . $request->parent_email);
    //                 Log::info('[INSCRIPTION] MOT PASSE  : ' . $motDePasse);
    //                 Log::info('[INSCRIPTION] ==============================');
    //             }

    //             $parent = ParentModel::firstOrCreate(
    //                 ['user_id' => $user->id],
    //                 [
    //                     'civilite'             => $request->civilite,
    //                     'nom'                  => strtoupper($request->parent_nom),
    //                     'prenom'               => $request->parent_prenom,
    //                     'lien_parente'         => $request->lien_parente,
    //                     'telephone'            => $request->parent_telephone,
    //                     'telephone_secondaire' => $request->parent_telephone2,
    //                     'adresse'              => $request->parent_adresse,
    //                     'ville'                => $request->parent_ville,
    //                     'arrondissement'       => $request->parent_arrondissement,
    //                     'code_postal'          => $request->parent_code_postal,
    //                 ]
    //             );
    //             Log::info('[INSCRIPTION] ParentModel créé/récupéré : id=' . $parent->id);
    //         }

    //         // ── B) Photo élève ────────────────────────────────────
    //         $photoPath = null;
    //         if ($request->hasFile('eleve_photo')) {
    //             $photoPath = $request->file('eleve_photo')
    //                 ->store('photos/eleves', 'public');
    //         }

    //         // ── C) Élève ──────────────────────────────────────────
    //         $eleve = Eleve::create([
    //             'parent_id'             => $parent->id,
    //             'nom'                   => strtoupper($request->eleve_nom),
    //             'prenom'                => $request->eleve_prenom,
    //             'date_naissance'        => $request->eleve_date_naissance,
    //             'lieu_naissance'        => $request->eleve_lieu_naissance,
    //             'sexe'                  => $request->eleve_sexe,
    //             'nationalite'           => $request->eleve_nationalite ?? 'Béninoise',
    //             'numero_acte_naissance' => $request->eleve_acte_naissance,
    //             'photo'                 => $photoPath,
    //             'niveau_souhaite'       => $request->niveau_souhaite,
    //             'ancienne_ecole'        => $request->ancienne_ecole,
    //             'derniere_classe'       => $request->derniere_classe,
    //             'resultat_precedent'    => $request->resultat_precedent,
    //             'groupe_sanguin'        => $request->groupe_sanguin,
    //             'infos_medicales'       => $request->infos_medicales,
    //         ]);
    //         Log::info('[INSCRIPTION] Élève créé : id=' . $eleve->id);

    //         // ── D) Inscription ────────────────────────────────────
    //         $inscription = Inscription::create([
    //             'eleve_id'       => $eleve->id,
    //             'classe_id'      => null,
    //             'annee_scolaire' => config('app.annee_scolaire', '2025-2026'),
    //             'type'           => 'nouvelle',
    //             'statut'         => 'en_attente',
    //         ]);
    //         Log::info('[INSCRIPTION] Inscription créée : ' . $inscription->numero_dossier);

    //         // ── E) Documents ──────────────────────────────────────
    //         $docs = [
    //             'doc_acte_naissance'  => 'acte_naissance',
    //             'doc_bulletin'        => 'bulletin',
    //             'doc_certificat_med'  => 'certificat_medical',
    //             'doc_piece_identite'  => 'piece_identite_parent',
    //             'doc_photo_identite'  => 'photo_identite',
    //         ];
    //         foreach ($docs as $champ => $type) {
    //             if ($request->hasFile($champ)) {
    //                 $fichier = $request->file($champ);
    //                 $chemin  = $fichier->store('documents/inscriptions/' . $inscription->id, 'public');
    //                 Document::create([
    //                     'inscription_id' => $inscription->id,
    //                     'type'           => $type,
    //                     'nom_fichier'    => $fichier->getClientOriginalName(),
    //                     'chemin_fichier' => $chemin,
    //                     'mime_type'      => $fichier->getMimeType(),
    //                     'taille'         => $fichier->getSize(),
    //                     'genere_systeme' => false,
    //                 ]);
    //             }
    //         }

    //         DB::commit();
    //         Log::info('[INSCRIPTION] Transaction OK');

    //         // ── F) Connexion automatique si non connecté ──────────
    //         if (!$estConnecte) {
    //             Auth::login($user);
    //             Log::info('[INSCRIPTION] Connexion auto : user_id=' . $user->id);
    //         }

    //         // ── G) Redirection ────────────────────────────────────
    //         $mdpInfo = !$estConnecte && session('mdp_genere')
    //             ? 'Votre compte a été créé. Mot de passe : ' . session('mdp_genere')
    //             : null;

    //         return redirect()
    //             ->route('parent.dashboard')
    //             ->with('success', 'Dossier #' . $inscription->numero_dossier . ' soumis avec succès !')
    //             ->with('mdp_info', $mdpInfo);

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('[INSCRIPTION] ERREUR : ' . $e->getMessage());
    //         Log::error('[INSCRIPTION] Trace : ' . $e->getTraceAsString());

    //         return back()
    //             ->withInput()
    //             ->with('error', 'Erreur lors de la soumission : ' . $e->getMessage());
    //     }
    // }



      public function store(Request $request)
    {

    
        $estConnecte = Auth::check();

        // ... validation (identique à avant) ...

        try {
            DB::beginTransaction();

            $motDePasse   = null;
            $nouveauCompte = false;

            // ── A) User + Parent ──────────────────────────────────
            if ($estConnecte) {
                $user   = Auth::user();
                $parent = $user->parentModel;
            } else {
                $user = User::where('email', $request->parent_email)->first();
                if (!$user) {
                    $motDePasse    = Str::random(10);
                    $nouveauCompte = true;
                    $user = User::create([
                        'name'     => $request->parent_prenom . ' ' . strtoupper($request->parent_nom),
                        'email'    => $request->parent_email,
                        'password' => Hash::make($motDePasse),
                        'role'     => 'parent',
                        'actif'    => true,
                    ]);
                    Log::info('[INSCRIPTION] ============================');
                    Log::info('[INSCRIPTION] EMAIL     : ' . $request->parent_email);
                    Log::info('[INSCRIPTION] MOT PASSE : ' . $motDePasse);
                    Log::info('[INSCRIPTION] ============================');
                }
                $parent = ParentModel::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'civilite'             => $request->civilite,
                        'nom'                  => strtoupper($request->parent_nom),
                        'prenom'               => $request->parent_prenom,
                        'lien_parente'         => $request->lien_parente,
                        'telephone'            => $request->parent_telephone,
                        'telephone_secondaire' => $request->parent_telephone2,
                        'adresse'              => $request->parent_adresse,
                        'ville'                => $request->parent_ville,
                        'arrondissement'       => $request->parent_arrondissement,
                        'code_postal'          => $request->parent_code_postal,
                    ]
                );
            }

            // ── B) Photo + Élève ──────────────────────────────────
            $photoPath = null;
            if ($request->hasFile('eleve_photo')) {
                $photoPath = $request->file('eleve_photo')->store('photos/eleves', 'public');
            }

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

            // ── C) Inscription ────────────────────────────────────
            $inscription = Inscription::create([
                'eleve_id'       => $eleve->id,
                'classe_id'      => null,
                'annee_scolaire' => config('app.annee_scolaire', '2025-2026'),
                'type'           => 'nouvelle',
                'statut'         => 'en_attente',
            ]);

            // ── D) Documents ──────────────────────────────────────
            $docs = [
                'doc_acte_naissance'  => 'acte_naissance',
                'doc_bulletin'        => 'bulletin',
                'doc_certificat_med'  => 'certificat_medical',
                'doc_piece_identite'  => 'piece_identite_parent',
                'doc_photo_identite'  => 'photo_identite',
            ];
            foreach ($docs as $champ => $type) {
                if ($request->hasFile($champ)) {
                    $fichier = $request->file($champ);
                    Document::create([
                        'inscription_id' => $inscription->id,
                        'type'           => $type,
                        'nom_fichier'    => $fichier->getClientOriginalName(),
                        'chemin_fichier' => $fichier->store('documents/inscriptions/'.$inscription->id, 'public'),
                        'mime_type'      => $fichier->getMimeType(),
                        'taille'         => $fichier->getSize(),
                        'genere_systeme' => false,
                    ]);
                }
            }

            DB::commit();

            // ── E) Connexion auto si non connecté ─────────────────
            if (!$estConnecte) {
                Auth::login($user);
            }

            // ── F) ENVOIS EMAILS (après commit) ───────────────────
            // On recharge l'inscription avec toutes les relations
            $inscription->load(['eleve.parent.user', 'classe']);

            // Email 1 : mot de passe si nouveau compte
            if ($nouveauCompte && $motDePasse) {
                $this->mailService->envoyerMotDePasse($user, $motDePasse);
            }

            // Email 2 : confirmation d'inscription
            $this->mailService->envoyerConfirmationInscription($inscription);

            // ── G) Redirection ────────────────────────────────────
            return redirect()
                ->route('parent.dashboard')
                ->with('success', 'Dossier #' . $inscription->numero_dossier . ' soumis avec succès !')
                ->with('mdp_info', $nouveauCompte
                    ? 'Votre compte a été créé. Mot de passe envoyé à : ' . $user->email
                    : null
                );

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('[INSCRIPTION] ERREUR : ' . $e->getMessage());
            return back()->withInput()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }
    // ──────────────────────────────────────────────────────────
    // GET /parent/inscription/{id}
    // Voir le détail d'un dossier
    // ──────────────────────────────────────────────────────────
  

      public function show(int $eleveId)
    {
        $parent = Auth::user()->parentModel;

        $eleve = Eleve::with([
                'inscriptions' => fn($q) => $q
                    ->with(['classe', 'documents'])
                    ->orderByDesc('annee_scolaire'),
            ])
            ->where('parent_id', $parent->id)
            ->findOrFail($eleveId);

        $inscriptions  = $eleve->inscriptions;
        $derniereAnnee = $inscriptions->max('annee_scolaire');

        // Calcul année suivante
        $anneeSuivante = null;
        $dejaReinscrit = false;

        if ($derniereAnnee) {
            $p             = explode('-', $derniereAnnee);
            $anneeSuivante = $p[1] . '-' . ($p[1] + 1);
            $dejaReinscrit = $inscriptions
                ->where('annee_scolaire', $anneeSuivante)
                ->whereIn('statut', ['en_attente', 'validee'])
                ->isNotEmpty();
        }

        return view('parent.show', compact(
            'eleve', 'inscriptions',
            'derniereAnnee', 'anneeSuivante', 'dejaReinscrit'
        ));
    }


      public function showUerInfo(int $id)
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

        return view('parent.eleve_dossier', compact('inscription'));
    }
}
