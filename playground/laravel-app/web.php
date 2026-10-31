<?php

use Apie\LaravelApie\Apie;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RootController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', RootController::class);

// Logs the current user out and makes sure the authentication cookie apie set is removed too.
// See config/apie.php ('logout_url') and Apie\LaravelApie\Apie::logout().
Route::get('/logout', function () {
    Apie::logout();

    return redirect('/');
})->name('logout');
