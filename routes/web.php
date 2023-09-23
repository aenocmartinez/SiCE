<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AreaController;

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
    return view('welcome');
});


Route::get('/areas', [AreaController::class, 'index'])->name('areas.index');
Route::get('/areas/create/{nombre}', [AreaController::class, 'create'])->name('areas.create');