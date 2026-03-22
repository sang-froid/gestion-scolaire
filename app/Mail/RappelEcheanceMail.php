<?php
//==========================================================================
// app/Mail/RappelEcheanceMail.php
// Commande : php artisan make:mail RappelEcheanceMail
//==========================================================================

namespace App\Mail;

use App\Models\Echeance;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RappelEcheanceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Echeance $echeance) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: ' Rappel paiement — ' . $this->echeance->libelle . ' · ' . $this->echeance->eleve->prenom,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.rappel-echeance');
    }
}
