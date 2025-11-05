<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

// Public portfolio view - accepts ?user_id parameter
Route::get('/', [PortfolioController::class, 'publicView'])->name('home');

// Contact form submission
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

// Serve images stored in resources/image/ through a simple route so existing image paths continue to work
Route::get('/images/{file}', function ($file) {
    $path = resource_path('image/' . $file);
    if (!file_exists($path)) {
        abort(404);
    }

    $mime = mime_content_type($path) ?: 'application/octet-stream';
    return response()->file($path, ['Content-Type' => $mime]);
})->where('file', '.*');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Portfolio editing routes
    Route::get('/portfolio/edit', [PortfolioController::class, 'edit'])->name('portfolio.edit');
    Route::put('/portfolio', [PortfolioController::class, 'update'])->name('portfolio.update');
});

require __DIR__.'/auth.php';
