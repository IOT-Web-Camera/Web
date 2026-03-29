<?php

use App\Http\Controllers\CameraEventController;
use App\Http\Controllers\CommandController;
use App\Models\Camera;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CameraController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::get('/', function () {
    return view('pages.home.home');
})->name('home');


Route::prefix('support')->name('support.')->group(function () {
    Route::get('/faq', [SupportController::class, 'faq'])->name('faq');
    Route::get('/contact', [SupportController::class, 'contact'])->name('contact');
});


// Routes nécessitant l'authentification
Route::middleware('auth')->group(function () {

    // Page Événements & Statistiques
    Route::get('/events', [CameraEventController::class, 'eventsPage'])
        ->name('events');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Changement de mot de passe
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('password.update');

    // Profil utilisateur
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Routes commandes
    Route::prefix('dashboard/cameras/{name}/cmd')->name('cameras.cmd.')->group(function () {
        Route::post('/led',    [CommandController::class, 'led'])->name('led');
        Route::post('/move',   [CommandController::class, 'move'])->name('move');
        Route::post('/reboot', [CommandController::class, 'reboot'])->name('reboot');
    });

    // Caméras
    Route::prefix('cameras')->name('cameras.')->group(function () {
        Route::get('/', [CameraController::class, 'index'])->name('index');
        Route::get('/create', [CameraController::class, 'create'])->name('create');
        Route::post('/', [CameraController::class, 'store'])->name('store');
        Route::get('/{camera}', [CameraController::class, 'show'])->name('show');
        Route::delete('/{camera}', [CameraController::class, 'destroy'])->name('destroy');
    });
});

// Stream
Route::get('/stream/{name}', [CameraController::class, 'stream'])
    ->name('cameras.stream')
    ->middleware('auth');



// Auth routes
require __DIR__ . '/auth.php';

// API routes
require __DIR__ . '/api.php';
