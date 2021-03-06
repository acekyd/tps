<?php

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
    return redirect()->route('home');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/home', 'HomeController@store')->name('add-supplier');
Route::post('/confirm', 'HomeController@confirmSupplier')->name('confirm-supplier');

Route::get('/{id}/pay', 'HomeController@view_pay')->name('view-pay');
Route::post('/{id}/pay', 'HomeController@make_payment')->name('make-payment');
Route::post('/{id}/pay/confirm', 'HomeController@confirm')->name('confirm');
