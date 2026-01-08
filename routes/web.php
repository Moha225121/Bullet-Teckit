<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UserTicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [EventController::class, 'index']);
// Public events listing
Route::get('events', [EventController::class, 'index'])->name('events.index');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // User dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Event management (Admin / Organizer only) — exclude index so it remains public
    Route::resource('events', EventController::class)
        ->except(['index', 'show'])
        ->middleware('role:admin,organizer');

    // Reports (Admin / Organizer only)
    Route::middleware('role:admin,organizer')->group(function () {
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
    });

    // Book tickets for an event (limit 2 per booking) - any authenticated user
    Route::post('events/{event}/book', [BookingController::class, 'store'])->name('events.book');

    // My tickets with QR display
    Route::get('my-tickets', [UserTicketController::class, 'index'])->name('tickets.mine');
    Route::get('tickets/{ticket}/download', [UserTicketController::class, 'download'])->name('tickets.download');

    // Profile (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

/*
|--------------------------------------------------------------------------
| Authentication Routes (Laravel Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
