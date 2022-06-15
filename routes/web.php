<?php

use App\Http\Controllers\MemberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you caGETn register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', static function () {
    return view('welcome');
});

/*
 * This route generate a temporary download link with 30 seconds timespan
 * temporaryUrl - Settings configured in App\Providers\AppServiceProvider
 * temporaryUrl method accepts a path and a DateTime instance
 * specifying when the URL should expire
 */
Route::get('temp-file', static function () {
    return Storage::disk('public')->temporaryUrl('temp.txt', now()->addSeconds(30));
});

/*
 * Temporary download link - Settings configured in App\Providers\AppServiceProvider
 * [signed] middleware; If route doesn't hasValidSignature then abort(403)
 */
Route::middleware(['signed'])->get('download/temp/{path}', function (string $path) {
    return Storage::disk('public')->download($path);
})->name('download.temp');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', static function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/members/get-inner-html', [MemberController::class, 'showTable'])->name('members.getInnerHTML');
    Route::resource('members', MemberController::class);
});

