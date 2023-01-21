<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'guests'], function () {
    Route::get('/', function () {
        return Inertia::render('Guests/view');
    })->name('guests');

    Route::get('/add', function () {
        return Inertia::render('Guests/add');
    })->name('guests.add');


});

Route::group(['middleware' => ['auth', 'verified'], 'prefix' => 'home'], function () {
    Route::get('/', function() {
        return Inertia::render('Home/view');
    })->name('home');
});

Route::get('/invitations', function () {
    return Inertia::render('Invitations');
})->middleware(['auth', 'verified'])->name('invitations');

Route::get('/seating', function () {
    return Inertia::render('Seating');
})->middleware(['auth', 'verified'])->name('seating');

Route::get('/settings', function () {
    return Inertia::render('Settings');
})->middleware(['auth', 'verified'])->name('settings');

Route::get('/addEvent', function () {
    return Inertia::render('Event/add');
})->middleware(['auth', 'verified'])->name('Event.add');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('invitation/{code}', [App\Http\Controllers\AppAPI\WeddingAppController::class, 'showInvitation']);

Route::get('invitation/accepted/{code}', [App\Http\Controllers\AppAPI\WeddingAppController::class, 'showInvitationAccept']);

Route::get('invitation/decline/{code}', [App\Http\Controllers\AppAPI\WeddingAppController::class, 'showInvitationDecline']);

Route::get('invitation/pending/{code}', [App\Http\Controllers\AppAPI\WeddingAppController::class, 'showInvitationPending']);

Route::get('invitation/detail/{code}', [App\Http\Controllers\AppAPI\WeddingAppController::class, 'showInvitationDetail']); 

require __DIR__.'/auth.php';
