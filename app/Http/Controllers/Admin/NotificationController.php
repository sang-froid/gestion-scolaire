<?php
//==========================================================================
// app/Http/Controllers/Admin/NotificationController.php
//==========================================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\ParentModel;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Echeance;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct(private MailService $mailService) {}

    // ── GET /admin/notifications ──────────────────────────────
    public function index()
    {
        // Historique des notifications envoyées
        $notifications = Notification::with(['parent', 'eleve', 'envoyeur'])
            ->latest()
            ->paginate(20);

        // Données pour le formulaire d'envoi
        $parents = ParentModel::with('user')->orderBy('nom')->get();
        $classes = Classe::where('annee_scolaire', config('app.annee_scolaire'))
            ->where('active', true)
            ->orderBy('nom')
            ->get();

        // Stats
        $stats = [
            'total'    => Notification::count(),
            'paiement' => Notification::where('type', 'paiement')->count(),
            'urgentes' => Notification::where('type', 'urgente')->count(),
            'infos'    => Notification::where('type', 'info')->count(),
        ];

        return view('admin.notifications.index', compact(
            'notifications', 'parents', 'classes', 'stats'
        ));
    }

    // ── POST /admin/notifications ─────────────────────────────
    public function send(Request $request)
    {
        $request->validate([
            'sujet'        => ['required', 'string', 'max:200'],
            'message'      => ['required', 'string', 'max:2000'],
            'type'         => ['required', 'in:paiement,urgente,info'],
            'destinataire' => ['required', 'in:tous,classe,parent'],
            'classe_id'    => ['required_if:destinataire,classe', 'nullable', 'exists:classes,id'],
            'parent_id'    => ['required_if:destinataire,parent', 'nullable', 'exists:parents,id'],
        ], [
            'sujet.required'   => 'Le sujet est obligatoire.',
            'message.required' => 'Le message est obligatoire.',
            'type.required'    => 'Le type est obligatoire.',
            'classe_id.required_if' => 'Veuillez sélectionner une classe.',
            'parent_id.required_if' => 'Veuillez sélectionner un parent.',
        ]);

        // Déterminer les destinataires
        $parents = match($request->destinataire) {
            'tous'   => ParentModel::with('user')->get(),
            'classe' => ParentModel::with('user')
                ->whereHas('eleves.inscription', fn($q) => $q
                    ->where('classe_id', $request->classe_id)
                    ->where('statut', 'validee')
                )->get(),
            'parent' => ParentModel::with('user')->where('id', $request->parent_id)->get(),
        };

        $nbEnvoyes = 0;

        foreach ($parents as $parent) {
            // Créer la notification en BDD
            $notif = Notification::create([
                'envoyee_par'     => Auth::id(),
                'parent_id'       => $parent->id,
                'eleve_id'        => null,
                'sujet'           => $request->sujet,
                'message'         => $request->message,
                'type'            => $request->type,
                'email_envoye'    => false,
                'lu_le'           => null,
            ]);

            // Envoi email si type paiement ou urgente
            if (in_array($request->type, ['paiement', 'urgente'])) {
                try {
                    \Illuminate\Support\Facades\Mail::to($parent->user->email)
                        ->send(new \App\Mail\NotificationGeneraleMail($notif));

                    $notif->update([
                        'email_envoye'    => true,
                        'email_envoye_le' => now(),
                    ]);
                    $nbEnvoyes++;
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('[NOTIF] Erreur envoi : ' . $e->getMessage());
                }
            } else {
                $nbEnvoyes++;
            }
        }

        return back()->with('success',
            'Notification envoyée à ' . $nbEnvoyes . ' parent(s) avec succès.'
        );
    }
}