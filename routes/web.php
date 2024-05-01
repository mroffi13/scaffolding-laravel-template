<?php

use App\Http\Controllers\AccessControl\UserController;
use App\Http\Controllers\ProfileController;
use App\Models\AccessControl\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard', [
        'type_menu' => 'dashboard'
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /**
     * Route Access Control
     */

    /**
     * Route Access Control
     */
    // Route User
    Route::patch('/users/{user}/assign-acl', [UserController::class, 'assignAcl'])->name('users.assign-acl');
    Route::post('/users/get-data', [UserController::class, 'getData'])->name('users.get-data');
    Route::resource('users', UserController::class)->names([
        'index' => 'users',
        'create' => 'users.create',
        'store' => 'users.store',
        'edit' => 'users.edit',
        'update' => 'users.update',
    ]);
    /**
     * Route Access Control
     */
});

require __DIR__.'/auth.php';
