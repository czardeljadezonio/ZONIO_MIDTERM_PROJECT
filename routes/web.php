<?php

use App\Http\Controllers\MemberController;
use App\Http\Controllers\PlanController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

// --- Default Laravel & Auth Routes ---

// Welcome Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Dashboard: Now points to the MemberController index, which serves as the main Gym Dashboard.
Route::get('dashboard', [MemberController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// --- Authenticated Group (Gym System & Settings) ---
Route::middleware(['auth'])->group(function () {

    // 1. Gym Management Routes (Primary: Members, Secondary: Plans)

    // Members CRUD (Primary Entity)
    Route::post('/members', [MemberController::class, 'store'])->name('members.store');
    Route::post('/members/{member}/update', [MemberController::class, 'update'])->name('members.update');
    Route::post('/members/{member}/delete', [MemberController::class, 'destroy'])->name('members.destroy');

    // Plans Management CRUD (Secondary Entity)
    Route::prefix('plans')->group(function () {
        Route::get('/', [PlanController::class, 'index'])->name('plans');
        Route::post('/', [PlanController::class, 'store'])->name('plans.store');
        Route::post('/{plan}/update', [PlanController::class, 'update'])->name('plans.update');
        Route::post('/{plan}/delete', [PlanController::class, 'destroy'])->name('plans.destroy');
    });


    // 2. Fortify / Livewire Settings Routes (Existing Routes)
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
