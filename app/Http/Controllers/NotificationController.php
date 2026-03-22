<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // ── GET /parent/notifications ─────────────────────────────
    public function index()
    {
        $parent = Auth::user()->parentModel;

        $notifications = $parent
            ->notifications()
            ->with('eleve')
            ->latest()
            ->get();

        // Stats pour les compteurs
        $total    = $notifications->count();
        $nonLues  = $notifications->whereNull('lu_le')->count();
        $paiement = $notifications->where('type', 'paiement')->count();
        $urgentes = $notifications->where('type', 'urgente')->count();

        // Filtre actif depuis l'URL (?type=paiement|urgente|info)
        $filtre = request('type', 'toutes');

        return view('parent.notifications', compact(
            'notifications', 'total', 'nonLues',
            'paiement', 'urgentes', 'filtre'
        ));
    }

    // ── POST /parent/notifications/{id}/lire (AJAX) ───────────
    public function marquerLue(int $id)
    {
        $parent = Auth::user()->parentModel;

        $notif = Notification::where('parent_id', $parent->id)
            ->findOrFail($id);

        $notif->update(['lu_le' => now()]);

        return response()->json(['ok' => true]);
    }

    // ── POST /parent/notifications/tout-lire ──────────────────
    public function marquerToutesLues()
    {
        $parent = Auth::user()->parentModel;

        $parent->notifications()
            ->whereNull('lu_le')
            ->update(['lu_le' => now()]);

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}