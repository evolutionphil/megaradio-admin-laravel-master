<?php

use App\Http\Controllers\Admin\AdsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DownloadLanguageImportSampleController;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\RadioStationController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\StationRequestController;
use App\Http\Controllers\Admin\StationSubmissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\JobController;
use App\Models\RadioStation;
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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/profile/edit', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::put('/profile/edit', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])
            ->name('profile.edit');

        Route::get('/radio-stations/sync', [RadioStationController::class, 'sync'])
            ->name('radio-stations.sync');

        Route::get('/radio-stations/{radio_station}/linked-stations', [RadioStationController::class, 'linkedStations'])
            ->name('radio-stations.linked-stations');

        Route::get('/radio-stations/{radio_station}/restore', [RadioStationController::class, 'restore'])
            ->name('radio-stations.restore');
        Route::resource('/radio-stations', RadioStationController::class);

        Route::get('/genres/sitemap', [GenreController::class, 'genrateSitemap'])
            ->name('genres.sitemap');
        Route::resource('/genres', GenreController::class);

        Route::post('languages/phrases', [LanguageController::class, 'addNewPhrase'])
            ->name('languages.phrases.store');

        Route::get('download-language-import-sample', DownloadLanguageImportSampleController::class)
            ->name('download-language-import-sample');

        Route::post('languages/import', [LanguageController::class, 'import'])
            ->name('languages.import');

        Route::get('languages/{language}/generate-sitemap', [LanguageController::class, 'generateSitemap'])
            ->name('languages.generate-sitemap');

        Route::post('languages/{language}/auto-translate', [LanguageController::class, 'autoTranslate'])
            ->name('languages.auto-translate');

        Route::post('languages/translate', [LanguageController::class, 'translate'])
            ->name('languages.translate');

        Route::resource('languages', LanguageController::class);

        Route::get('settings', [SettingsController::class, 'show'])->name('settings.show');

        Route::get('settings/api', [SettingsController::class, 'group'])->name('settings.group');

        Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');

        Route::resource('feedbacks', FeedbackController::class)->only('index', 'show');

        Route::resource('users', UserController::class);

        Route::resource('ads', AdsController::class);

        Route::post('pages/{page}/translations', [PageController::class, 'saveTranslation'])
            ->name('pages.translations.store');
        Route::resource('pages', PageController::class);

        Route::resource('station-requests', StationRequestController::class)->only(['index', 'destroy']);

        Route::get('station-submissions/{station_submission}/approve', [StationSubmissionController::class, 'approve'])
            ->name('station-submissions.approve');

        Route::resource('station-submissions', StationSubmissionController::class)->only(['index', 'destroy']);
    });

Route::get('/jobs/dispatch', [JobController::class, 'dispatchJob'])
    ->name('jobs.dispatch');

Route::get('test', function () {
    $query = RadioStation::where('favicon', 'like', 'stations/%')
        ->orderBy('is_working', -1);

    $query->select(['id'])
        ->chunk(50, function ($stations) {
            CheckFaviconExistsJob::dispatch($stations->pluck('id'));
        });
});

require __DIR__.'/auth.php';
