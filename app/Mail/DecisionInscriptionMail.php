<?php
//==========================================================================
// app/Mail/DecisionInscriptionMail.php
// Commande : php artisan make:mail DecisionInscriptionMail
//==========================================================================

namespace App\Mail;

use App\Models\Inscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DecisionInscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Inscription $inscription) {}

    public function envelope(): Envelope
    {
        $sujet = $this->inscription->statut === 'validee'
            ? ' Inscription validée — ' . $this->inscription->eleve->prenom
            : ' Dossier refusé — ' . $this->inscription->eleve->prenom;

        return new Envelope(subject: $sujet);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.decision-inscription');
    }
}
