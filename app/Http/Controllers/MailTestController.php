<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MailTestController extends Controller
{

 public function test()
    {
        try {
            Mail::raw('Ceci est un email de test Laravel', function ($message) {
                $message->to('irenelokossou16@gmail.com') // 👉 mets ton email ici
                        ->subject('Test envoi mail');
            });

            return "✅ Mail envoyé avec succès";
        } catch (\Exception $e) {
            Log::error('[MAIL TEST] ' . $e->getMessage());
            return "❌ Erreur : " . $e->getMessage();
        }
    }
    //
}
