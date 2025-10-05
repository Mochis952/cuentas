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

Route::get('/usuarios/registrar', function () {
    return view('register');
})->name('register');

Route::get('/cuentas/registrar', function () {
    return view('register_account_streaming');
})->name('register_account_streaming');

Route::get('/cuentas', function () {
    return view('account_streaming');
})->name('account_streaming');

Route::get('/usuarios', function () {
    return view('user_list');
})->name('user_list');

Route::post('/add_customer', 'App\Http\Controllers\CustomerController@add_customer')->name('add_customer');
Route::post('/add_account', 'App\Http\Controllers\AccountController@store')->name('add_account');
Route::get('/account_streaming/index', 'App\Http\Controllers\AccountController@index')->name('index_account_streaming');
Route::get('/customer/index', 'App\Http\Controllers\CustomerController@index')->name('index_customer');
Route::post('/customer_account/update/{id}', 'App\Http\Controllers\CustomerAccountController@update_pay')->name('update_customer_account');
Route::delete('/customer_account/delete/{id}', 'App\Http\Controllers\CustomerAccountController@delete')->name('delete_customer_account');
Route::get('/account_streaming/available/{name_service}', 'App\Http\Controllers\AccountStreamingController@get_account_streaming_available')->name('account_streaming_available');
Route::post('/customer_account/update_profile', 'App\Http\Controllers\CustomerAccountController@update_profile')->name('update_profile_customer_account');
Route::get('chat/history/{customer_contact}', 'App\Http\Controllers\CustomerController@history_customer')->name('chat_history');
// Rutas para actualizar y eliminar cuentas de streaming
Route::post('/account-streaming/update/{id}', 'App\Http\Controllers\AccountStreamingController@update')->name('update_account_streaming');
Route::delete('/account-streaming/delete/{id}', 'App\Http\Controllers\AccountStreamingController@destroy')->name('delete_account_streaming');

