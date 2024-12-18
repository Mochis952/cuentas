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

Route::get('/registar/usuarios', function () {
    return view('register');
})->name('register');

Route::get('/registar/cuentas', function () {
    return view('register_account_streaming');
})->name('register_account_streaming)');

Route::get('/cuentas', function () {
    return view('account_streaming');
})->name('account_streaming)');
Route::post('/add_customer', 'App\Http\Controllers\CustomerController@add_customer')->name('add_customer');
Route::post('/add_account', 'App\Http\Controllers\AccountController@store')->name('add_account');
Route::get('/account_streaming/index', 'App\Http\Controllers\AccountController@index')->name('index_account_streaming');


