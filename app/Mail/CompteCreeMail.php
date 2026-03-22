<?php
//==========================================================================
// app/Mail/CompteCreeMail.php
// Commande : php artisan make:mail CompteCreeMail --markdown=emails.compte-cree
//==========================================================================

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompteCreeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User   $user,
        public string $motDePasse
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: ' Votre compte  a été créé',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.compte-cree',
        );
    }
}
