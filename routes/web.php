<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\ShortUrlsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Companies routes
    Route::resource('companies', CompaniesController::class);

    // Clients routes
    Route::resource('clients', ClientsController::class);

    // Short urls routes
    Route::resource('short-urls', ShortUrlsController::class);
});

// Publicly Accessible Route
Route::get('/s/{shortUrl}', [ShortUrlsController::class, 'redirectToPublicLongUrl'])->name('short-urls.redirect');

require __DIR__.'/auth.php';
