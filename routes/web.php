<?php

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
