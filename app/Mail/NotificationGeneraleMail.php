<?php
//==========================================================================
// app/Mail/NotificationGeneraleMail.php
// Commande : php artisan make:mail NotificationGeneraleMail
//==========================================================================

namespace App\Mail;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationGeneraleMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Notification $notif) {}

    public function envelope(): Envelope
    {
        $emoji = match($this->notif->type) {
            'paiement' => '💰',
            'urgente'  => '🚨',
            default    => 'ℹ️',
        };

        return new Envelope(
            subject: $emoji . ' ' . $this->notif->sujet . ' — EduGest',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.notification-generale');
    }
}