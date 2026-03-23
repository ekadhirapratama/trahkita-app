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

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $totalMembers = \App\Models\Member::count();
        $pendingSubmissions = \App\Models\Submission::where('status', 'pending')->count();
        $latestActivities = \App\Models\ActivityLog::with('user')->latest()->take(5)->get();
        return view('dashboard', compact('totalMembers', 'pendingSubmissions', 'latestActivities'));
    })->name('dashboard');
    
    Route::resource('members', \App\Http\Controllers\Admin\MemberController::class);
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->only(['index', 'store', 'destroy']);
    
    Route::resource('submissions', \App\Http\Controllers\Admin\SubmissionController::class)->only(['index', 'show', 'destroy']);
    Route::patch('submissions/{submission}/status', [\App\Http\Controllers\Admin\SubmissionController::class, 'updateStatus'])->name('submissions.update_status');
});

require __DIR__.'/auth.php';
