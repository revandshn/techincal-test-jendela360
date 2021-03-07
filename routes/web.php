<?php

use App\Http\Controllers\WEB\Auth\AuthController;
use App\Http\Controllers\WEB\Feature\CarController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});
