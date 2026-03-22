<?php
//==========================================================================
// app/Mail/AffectationClasseMail.php
// Commande : php artisan make:mail AffectationClasseMail
//==========================================================================

namespace App\Mail;

use App\Models\Inscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AffectationClasseMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Inscription $inscription) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: ' ' . $this->inscription->eleve->prenom . ' a été affecté(e) à la classe ' . $this->inscription->classe->nom,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.affectation-classe');
    }
}
