<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Services
    |--------------------------------------------------------------------------
    */
    Route::resource('services', ServiceController::class);

    /*
    |--------------------------------------------------------------------------
    | Clients
    |--------------------------------------------------------------------------
    */
    Route::resource('clients', ClientController::class);

    Route::patch('/clients/{client}/requirements', [ClientController::class, 'updateRequirements'])
        ->name('clients.requirements.update');

    Route::post('/clients/{client}/documents', [ClientController::class, 'uploadDocument'])
        ->name('clients.documents.upload');

    Route::get('/clients/{client}/documents/{document}/download', [ClientController::class, 'downloadDocument'])
        ->name('clients.documents.download');

    Route::delete('/clients/{client}/documents/{document}', [ClientController::class, 'destroyDocument'])
        ->name('clients.documents.destroy');

    /*
    |--------------------------------------------------------------------------
    | Documentation & Files
    |--------------------------------------------------------------------------
    */
    Route::get('/documents', [DocumentController::class, 'index'])
        ->name('documents.index');

    /*
    |--------------------------------------------------------------------------
    | Settings / Profile
    |--------------------------------------------------------------------------
    */
    Route::get('/settings/profile', [ProfileController::class, 'show'])
        ->name('settings.profile');

    Route::put('/settings/profile', [ProfileController::class, 'update'])
        ->name('settings.profile.update');

    Route::delete('/settings/profile', [ProfileController::class, 'destroy'])
        ->name('settings.profile.destroy');
    
    Route::post('/clients/{client}/tracker', [ClientController::class, 'updateTracker'])
     ->name('clients.tracker.update');
 
// Documents index page (kung wala ka pang ganito)
    Route::get('/documents', [ClientController::class, 'documents'])
        ->name('documents.index');
    });

    Route::get('/auth/google',          [GoogleController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

    Route::get('/users',                      [UserController::class, 'index'])->name('users.index');
    Route::post('/users',                     [UserController::class, 'store'])->name('users.store');
    Route::delete('/users/{user}',            [UserController::class, 'destroy'])->name('users.destroy');
 
    // Assignments (link user → documents / services)
    Route::get('/users/{user}/assignments',   [UserController::class, 'getAssignments']);
    Route::post('/users/{user}/assignments',  [UserController::class, 'saveAssignments']);
require __DIR__.'/auth.php';    