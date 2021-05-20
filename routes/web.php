<?php

use Illuminate\Support\Facades\Route;

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

/*Admin Routes*/
include('admin.php');
/*Front Routes*/
include('front.php');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
