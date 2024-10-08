<?php

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
})->name('laravel');
Route::get('/registar', function () {
    return view('register');
})->name('register');
route::post('/add_customer', 'App\Http\Controllers\CustomerController@add_customer')->name('add_customer');

