<?php
//==========================================================================
// app/Mail/ConfirmationInscriptionMail.php
// Commande : php artisan make:mail ConfirmationInscriptionMail
//==========================================================================

namespace App\Mail;

use App\Models\Inscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmationInscriptionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Inscription $inscription) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Dossier #' . $this->inscription->numero_dossier . ' reçu ',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.confirmation-inscription',
        );
    }
}
