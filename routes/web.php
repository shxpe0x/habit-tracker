<?php

use App\Livewire\Challenges\Form as ChallengeForm;
use App\Livewire\Challenges\Index as ChallengesIndex;
use App\Livewire\Dashboard;
use App\Livewire\Habits\Form as HabitForm;
use App\Livewire\Habits\Index as HabitsIndex;
use App\Livewire\Statistics\Charts;
use App\Http\Controllers\StatisticsExportController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');
Route::view('/about', 'about')->name('about');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');

    Route::get('habits', HabitsIndex::class)->name('habits.index');
    Route::get('habits/create', HabitForm::class)->name('habits.create');
    Route::get('habits/{habit}/edit', HabitForm::class)->name('habits.edit');

    Route::get('challenges', ChallengesIndex::class)->name('challenges.index');
    Route::get('challenges/create', ChallengeForm::class)->name('challenges.create');
    Route::get('challenges/{challenge}/edit', ChallengeForm::class)->name('challenges.edit');

    Route::get('statistics', Charts::class)->name('statistics');
    Route::get('statistics/export', StatisticsExportController::class)->name('statistics.export');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
