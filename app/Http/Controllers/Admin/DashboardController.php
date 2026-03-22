<?php
//==========================================================================
// app/Http/Controllers/Admin/DashboardController.php
//==========================================================================

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inscription;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\ParentModel;
use App\Models\Notification;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $annee = config('app.annee_scolaire', '2025-2026');

        // ── Stats principales ──────────────────────────────────
        $totalInscriptions = Inscription::where('annee_scolaire', $annee)->count();
        $enAttente         = Inscription::where('annee_scolaire', $annee)->where('statut', 'en_attente')->count();
        $validees          = Inscription::where('annee_scolaire', $annee)->where('statut', 'validee')->count();
        $refusees          = Inscription::where('annee_scolaire', $annee)->where('statut', 'refusee')->count();
        $totalEleves       = Eleve::count();
        $totalClasses      = Classe::where('annee_scolaire', $annee)->where('active', true)->count();
        $totalParents      = ParentModel::count();
        $totalNonAffectes  = Inscription::where('annee_scolaire', $annee)
                                ->where('statut', 'validee')
                                ->whereNull('classe_id')
                                ->count();

        // ── Dernières inscriptions (10) ────────────────────────
        $dernieresInscriptions = Inscription::with(['eleve', 'eleve.parent', 'classe'])
            ->where('annee_scolaire', $annee)
            ->latest()
            ->take(10)
            ->get();

        // ── Inscriptions par jour (7 derniers jours) ──────────
        $inscriptionsParJour = Inscription::where('annee_scolaire', $annee)
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Construire tableau 7 jours complets
        $graphData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $graphData[] = [
                'date'  => Carbon::parse($date)->isoFormat('D MMM'),
                'total' => $inscriptionsParJour->get($date)?->total ?? 0,
            ];
        }

        // ── Taux remplissage classes ───────────────────────────
        $classes = Classe::where('annee_scolaire', $annee)
            ->where('active', true)
            ->withCount(['inscriptions as nb_eleves' => fn($q) => $q->where('statut', 'validee')])
            ->orderByDesc('nb_eleves')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'annee',
            'totalInscriptions', 'enAttente', 'validees', 'refusees',
            'totalEleves', 'totalClasses', 'totalParents', 'totalNonAffectes',
            'dernieresInscriptions', 'graphData', 'classes'
        ));
    }
}