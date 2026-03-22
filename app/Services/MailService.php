<?php


namespace App\Services;

use App\Models\Inscription;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

// Import des Mailables
use App\Mail\CompteCreeMail;
use App\Mail\ConfirmationInscriptionMail;
use App\Mail\DecisionInscriptionMail;
use App\Mail\AffectationClasseMail;
use App\Mail\RappelEcheanceMail;

class MailService
{
    // ──────────────────────────────────────────────────────────
    // 1. ENVOI DU MOT DE PASSE GÉNÉRÉ (nouveau compte)
    // Appelé dans : InscriptionController::store()
    // ──────────────────────────────────────────────────────────
    public function envoyerMotDePasse(User $user, string $motDePasse): void
    {
        try {
            Mail::to($user->email)->send(new CompteCreeMail($user, $motDePasse));
            Log::info('[MAIL] Mot de passe envoyé à : ' . $user->email);
        } catch (\Exception $e) {
            Log::error('[MAIL] Erreur envoi mot de passe : ' . $e->getMessage());
            // On ne bloque pas l'inscription si l'email échoue
        }
    }

    // ──────────────────────────────────────────────────────────
    // 2. CONFIRMATION D'INSCRIPTION (dossier soumis)
    // Appelé dans : InscriptionController::store()
    // ──────────────────────────────────────────────────────────
    public function envoyerConfirmationInscription(Inscription $inscription): void
    {
        try {
            $email = $inscription->eleve->parent->user->email;
            Mail::to($email)->send(new ConfirmationInscriptionMail($inscription));
            Log::info('[MAIL] Confirmation inscription envoyée : ' . $inscription->numero_dossier);
        } catch (\Exception $e) {
            Log::error('[MAIL] Erreur confirmation inscription : ' . $e->getMessage());
        }
    }

    // ──────────────────────────────────────────────────────────
    // 3. DÉCISION ADMIN (validé ou refusé)
    // Appelé dans : Admin\InscriptionController::valider() et ::refuser()
    // ──────────────────────────────────────────────────────────
    public function envoyerDecision(Inscription $inscription): void
    {
        try {
            $email = $inscription->eleve->parent->user->email;
            Mail::to($email)->send(new DecisionInscriptionMail($inscription));
            Log::info('[MAIL] Décision envoyée (' . $inscription->statut . ') : ' . $inscription->numero_dossier);
        } catch (\Exception $e) {
            Log::error('[MAIL] Erreur décision : ' . $e->getMessage());
        }
    }

    // ──────────────────────────────────────────────────────────
    // 4. AFFECTATION À UNE CLASSE
    // Appelé dans : Admin\InscriptionController::affecter()
    // ──────────────────────────────────────────────────────────
    public function envoyerAffectationClasse(Inscription $inscription): void
    {
        try {
            $email = $inscription->eleve->parent->user->email;
            Mail::to($email)->send(new AffectationClasseMail($inscription));
            Log::info('[MAIL] Affectation classe envoyée : ' . $inscription->eleve->prenom . ' → ' . $inscription->classe->nom);
        } catch (\Exception $e) {
            Log::error('[MAIL] Erreur affectation : ' . $e->getMessage());
        }
    }

    // ──────────────────────────────────────────────────────────
    // 5. RAPPEL ÉCHÉANCE PAIEMENT
    // Appelé dans : Admin\NotificationController::send()
    //              ou via une commande planifiée (Scheduler)
    // ──────────────────────────────────────────────────────────
    public function envoyerRappelEcheance(\App\Models\Echeance $echeance): void
    {
        try {
            $email = $echeance->parent->user->email;
            Mail::to($email)->send(new RappelEcheanceMail($echeance));
            Log::info('[MAIL] Rappel échéance envoyé à : ' . $email);
        } catch (\Exception $e) {
            Log::error('[MAIL] Erreur rappel échéance : ' . $e->getMessage());
        }
    }
}
