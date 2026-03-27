<?php
use App\Models\Camera;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CameraController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function () {
    $activeCameras = Camera::where('owner_id', Auth::id())
        ->where('is_active', true)
        ->get();

    $totalUserCameras = Camera::where('owner_id', Auth::id())->count();

    return view('dashboard.index', compact('activeCameras', 'totalUserCameras'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth'])->group(function () {
    // Liste des caméras
    Route::get('/cameras', [CameraController::class, 'index'])->name('cameras.index');

    // Formulaire d'ajout
    Route::get('/cameras/create', [CameraController::class, 'create'])->name('cameras.create');

    // Enregistrement d'une nouvelle caméra
    Route::post('/cameras', [CameraController::class, 'store'])->name('cameras.store');

    // Vue d'une caméra spécifique
    Route::get('/cameras/{name}', [CameraController::class, 'show'])->name('cameras.show');

    // Suppression d'une caméra
    Route::delete('/cameras', [CameraController::class, 'destroy'])->name('cameras.destroy');
});

require __DIR__.'/auth.php';
