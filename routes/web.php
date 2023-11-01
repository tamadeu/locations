<?php

use App\Http\Controllers\LocationsController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/countries', [LocationsController::class, "countries"])->name('countries');

Route::get('/countries/{country}', [LocationsController::class, "country"])->name('country');

Route::get('/cities', [LocationsController::class, "cities"])->name('cities');

Route::get('/cities/{city}', [LocationsController::class, "city"])->name('city');

Route::get('/states', [LocationsController::class, "states"])->name('states');

Route::get('/states/{country}/{state}', [LocationsController::class, "state"])->name('state');

Route::get('/findNearbyCities', [LocationsController::class, "findNearbyCities"])->name('findNearbyCities');