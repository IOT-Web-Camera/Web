<?php

use App\Models\Camera;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CameraController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::get('/', function () {
    return view('pages.home.home');
})->name('home');

// Routes nécessitant l'authentification
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil utilisateur
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Caméras
    Route::prefix('cameras')->name('cameras.')->group(function () {

        // Liste des caméras de l'utilisateur
        Route::get('/', [CameraController::class, 'index'])->name('index');

        // Création d'une caméra
        Route::get('/create', [CameraController::class, 'create'])->name('create');
        Route::post('/', [CameraController::class, 'store'])->name('store');

        // Vue d'une caméra spécifique
        Route::get('/{camera}', [CameraController::class, 'show'])
            ->name('show')
            ->middleware('can:view,camera'); // Vérifie que l'utilisateur peut voir sa caméra

        // Suppression d'une caméra
        Route::delete('/{camera}', [CameraController::class, 'destroy'])
            ->name('destroy')
            ->middleware('can:delete,camera'); // Vérifie que l'utilisateur peut supprimer sa caméra
    });
});


Route::get('/stream/{name}', [CameraController::class, 'stream'])
    ->name('cameras.stream')
    ->middleware('auth');

Route::post('/api/mediamtx/auth', [CameraController::class, 'mediamtxAuth']);
Route::post('/api/camera/heartbeat', [CameraController::class, 'heartbeat']);


// Auth routes (login/register)
require __DIR__ . '/auth.php';
