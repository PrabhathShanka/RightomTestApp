<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlanetController;


// Route::get('/', function () {
//     return view('welcome');
// });



Route::get('/', [PlanetController::class, 'index'])->name('planets.index');
Route::get('/planet/{id}', [PlanetController::class, 'show'])->name('planets.show');

