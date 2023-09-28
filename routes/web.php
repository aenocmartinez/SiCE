<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AreaController;
use App\Http\Controllers\CursoController;

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
Route::get('/areas/{id}', [AreaController::class, 'buscarPorId'])->name('areas.buscarPorId');
Route::get('/areas/crear/{nombre}', [AreaController::class, 'create'])->name('areas.create');
Route::get('/areas/eliminar/{id}', [AreaController::class, 'delete'])->name('areas.delete');
Route::get('/areas/actualizar/{id}/{nombre}', [AreaController::class, 'update'])->name('areas.update');

Route::get('/cursos', [CursoController::class, 'index'])->name('cursos.index');
Route::get('/cursos/{id}', [CursoController::class, 'buscarPorId'])->name('cursos.buscarPorId');
Route::get('/cursos/crear/{nombre}/{modalidad}/{costo}/{area}', [CursoController::class, 'create'])->name('cursos.create');
Route::get('/cursos/eliminar/{id}', [CursoController::class, 'delete'])->name('cursos.delete');
Route::get('/cursos/actualizar/{id}/{nombre}/{modalidad}/{costo}/{area}', [CursoController::class, 'update'])->name('cursos.update');