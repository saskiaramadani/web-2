<?php

use App\Http\Controllers\UnitKerjaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/unit-kerja', [UnitKerjaController::class, 'index']);