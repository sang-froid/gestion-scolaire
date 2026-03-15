<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Parent\InscriptionController;
use Illuminate\Support\Facades\Route;

// Redirection par défaut vers login
Route::get('/', function () {
    return redirect()->route('login');
});

//Auth::routes();


Route::get('/login',  [AuthController::class, 'showForm'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'authenticate'])->name('logout');


Route::get('/inscription',       [InscriptionController::class, 'showRegister'])->name('parent.inscription.create');
Route::post('/inscription',      [InscriptionController::class, 'store'])->name('parent.inscription.store');
 

Route::prefix('parent')
    ->name('parent.')
    ->middleware(['auth', 'isParent'])
    ->group(function () {
 
        // Dashboard
        Route::get('/dashboard', [InscriptionController::class, 'index'])
            ->name('dashboard');
 
        // Voir le détail d'un dossier (après soumission)
        Route::get('/inscription/{id}', [InscriptionController::class, 'show'])
            ->name('inscription.show');
 
        // Notifications
        Route::get('/notifications', [InscriptionController::class, 'index'])
            ->name('notifications.index');
        Route::post('/notifications/{id}/lire', [InscriptionController::class, 'marquerLue'])
            ->name('notifications.lire');
 
    });


