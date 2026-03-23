<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [\App\Http\Controllers\PublicController::class, 'index'])->name('home');
Route::get('/member/{id}', [\App\Http\Controllers\PublicController::class, 'profile'])->name('member.profile');
Route::get('/member/{id}/suggest', [\App\Http\Controllers\SubmissionController::class, 'suggest'])->name('member.suggest');
Route::post('/member/{id}/suggest', [\App\Http\Controllers\SubmissionController::class, 'storeSuggestion'])->name('member.suggest.store');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'admin'])->name('dashboard');

require __DIR__.'/auth.php';
