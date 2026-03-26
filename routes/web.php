<?php

use App\Http\Controllers\Admin\ClasseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EleveController;
use App\Http\Controllers\Admin\FicheController;
use App\Http\Controllers\Admin\InscriptionController as AdminInscriptionController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Parent\InscriptionController;
use App\Http\Controllers\Parent\ReInscriptionController;
use Illuminate\Support\Facades\Route;

// Redirection par défaut vers login
Route::get('/', function () {
    return redirect()->route('login');
});

//Auth::routes();


Route::get('/login',  [AuthController::class, 'showForm'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/test-mail', [App\Http\Controllers\MailTestController::class, 'test']);

Route::get('/inscription',       [InscriptionController::class, 'showRegister'])->name('parent.inscription.create');
Route::post('/inscription',      [InscriptionController::class, 'store'])->name('parent.inscription.store');



// ESPACE ADMIN
// ══════════════════════════════════════════════════════════
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'isAdmin'])
    ->group(function () {

        // ── Dashboard ──────────────────────────────────────
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // ── Inscriptions ───────────────────────────────────
        // Liste + filtres
        Route::get('/inscriptions', [AdminInscriptionController::class, 'index'])
            ->name('inscriptions.index');

        // Voir le détail d'un dossier
        Route::get('/inscriptions/{id}', [AdminInscriptionController::class, 'show'])
            ->name('inscriptions.show');

        // Valider un dossier
        Route::post('/inscriptions/{id}/valider', [AdminInscriptionController::class, 'valider'])
            ->name('inscriptions.valider');

        // Refuser un dossier
        Route::post('/inscriptions/{id}/refuser', [AdminInscriptionController::class, 'refuser'])
            ->name('inscriptions.refuser');

        // Affecter un élève à une classe
        Route::post('/inscriptions/{id}/affecter', [AdminInscriptionController::class, 'affecter'])
            ->name('inscriptions.affecter');

        // ── Classes ────────────────────────────────────────
        Route::get('/classes',            [ClasseController::class, 'index'])->name('classes.index');
        Route::get('/classes/create',     [ClasseController::class, 'create'])->name('classes.create');
        Route::post('/classes',           [ClasseController::class, 'store'])->name('classes.store');
        Route::get('/classes/{id}',       [ClasseController::class, 'show'])->name('classes.show');
        Route::get('/classes/{id}/edit',  [ClasseController::class, 'edit'])->name('classes.edit');
        Route::put('/classes/{id}',       [ClasseController::class, 'update'])->name('classes.update');
        Route::delete('/classes/{id}',    [ClasseController::class, 'destroy'])->name('classes.destroy');

        // ── Élèves ─────────────────────────────────────────
        Route::get('/eleves',       [EleveController::class, 'index'])->name('eleves.index');
        Route::get('/eleves/{id}',  [EleveController::class, 'show'])->name('eleves.show');

        // ── Notifications ──────────────────────────────────
        Route::get('/notifications',  [AdminNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications', [AdminNotificationController::class, 'send'])->name('notifications.send');

        Route::get('/fiches',                   [FicheController::class, 'index'])      ->name('fiches.index');
        Route::get('/fiches/{id}/fiche',        [FicheController::class, 'fiche'])      ->name('fiches.fiche');
        Route::get('/fiches/{id}/carte',        [FicheController::class, 'carte'])      ->name('fiches.carte');
        Route::get('/fiches/classe/{classeId}', [FicheController::class, 'ficheClasse'])->name('fiches.classe');

});

Route::prefix('parent')
    ->name('parent.')
    ->middleware(['auth', 'isParent'])
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [InscriptionController::class, 'index'])
            ->name('dashboard');

        Route::get('/list', [InscriptionController::class, 'indexParentDossier'])
            ->name('list');

        Route::get('/mes-eleves/{eleve}',  [InscriptionController::class, 'show'])->name('eleves.show');

        // Voir le détail d'un dossier (après soumission)
        Route::get('/inscription/{id}', [InscriptionController::class, 'showUerInfo'])
            ->name('inscription.show');

        // Notifications

        // ── Réinscription ──
        Route::get('/reinscription/{eleve}',  [ReInscriptionController::class, 'create'])
            ->name('reinscription.create');
        Route::post('/reinscription/{eleve}', [ReInscriptionController::class, 'store'])
            ->name('reinscription.store');

        Route::post('/notifications/tout-lire', [NotificationController::class, 'marquerToutesLues'])
            ->name('notifications.toutlire');

        Route::post('/notifications/{id}/lire', [NotificationController::class, 'marquerLue'])
            ->name('notifications.lire');

        Route::get('/notifications', [NotificationController::class, 'index'])
            ->name('notifications.index');
    });
