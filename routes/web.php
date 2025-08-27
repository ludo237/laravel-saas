<?php

declare(strict_types=1);

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InviteTeamMemberController;
use App\Http\Controllers\OAuthLoginController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', HomeController::class)->name('home');

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::delete('providers/{provider}', [OAuthLoginController::class, 'disconnect'])->name('auth.provider.disconnect');

    Route::prefix('teams')->group(function () {
        Route::get('', [TeamController::class, 'index'])->name('team.index');
        Route::post('', [TeamController::class, 'store'])->name('team.store');
        Route::get('create', [TeamController::class, 'create'])->name('team.create');

        Route::prefix('{id}')->group(function () {
            Route::get('', [TeamController::class, 'show'])->name('team.show');
            Route::match(['put', 'patch'], '', [TeamController::class, 'update'])->name('team.update');

            Route::post('invite', InviteTeamMemberController::class)->name('team.invite.create');
        });
    });

    Route::redirect('settings', '/settings/profile');

    Route::prefix('settings')->group(function () {
        Route::prefix('profile')->group(function () {
            Route::get('', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('', [ProfileController::class, 'destroy'])->name('profile.destroy');
        });

        Route::prefix('password')->group(function () {
            Route::get('', [PasswordController::class, 'edit'])->name('password.edit');
            Route::put('', [PasswordController::class, 'update'])->middleware('throttle:6,1')->name('password.update');
        });

        Route::get('appearance', fn () => Inertia::render('settings/appearance'))->name('appearance');
    });
});
