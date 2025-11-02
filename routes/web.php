<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AllergeenController;
use App\Http\Controllers\ProductController;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/allergeen', [AllergeenController::class, 'index'])->name('allergeen.index');;

Route::get('/allergeen/create', [AllergeenController::class, 'create'])->name('allergeen.create');

Route::post('/allergeen', [AllergeenController::class, 'store'])->name('allergeen.store');

Route::delete('/allergeen/{id}', [AllergeenController::class, 'destroy'])->name('allergeen.destroy');

Route::get('/allergeen/{id}/edit', [AllergeenController::class, 'edit'])->name('allergeen.edit');

Route::put('/allergeen/{id}', [AllergeenController::class, 'update'])->name('allergeen.update');

Route::middleware(['auth'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('product.index');
    Route::get('/product/{id}/allergenenInfo', [ProductController::class, 'allergenenInfo'])->name('product.allergenenInfo');
    Route::get('/product/{id}/leverantieInfo', [ProductController::class, 'leverantieInfo'])->name('product.leverantieInfo');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
