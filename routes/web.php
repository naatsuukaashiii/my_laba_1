<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\InfoController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/info/server', [InfoController::class, 'serverInfo']);
Route::get('/info/client', [InfoController::class, 'clientInfo']);
Route::get('/info/database', [InfoController::class, 'databaseInfo']);

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
