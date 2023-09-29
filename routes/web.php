<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AreaController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\OrientadorController;
use App\Http\Controllers\SalonController;

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

Route::view('/', 'home')->name('home');

Route::get('/areas', [AreaController::class, 'index'])->name('areas.index');
Route::get('/areas/crear', [AreaController::class, 'create'])->name('areas.create');
Route::get('/areas/{id}/editar', [AreaController::class, 'buscarPorId'])->name('areas.edit');
Route::post('areas', [AreaController::class, 'store'])->name('areas.store');
Route::delete('/areas/{id}', [AreaController::class, 'delete'])->name('areas.delete');
Route::patch('/areas', [AreaController::class, 'update'])->name('areas.update');

Route::get('/cursos', [CursoController::class, 'index'])->name('cursos.index');
Route::get('/cursos/{id}/editar', [CursoController::class, 'buscarPorId'])->name('cursos.edit');
Route::get('/cursos/create', [CursoController::class, 'create'])->name('cursos.create');
Route::post('/cursos', [CursoController::class, 'store'])->name('cursos.store');
Route::delete('/cursos/{id}', [CursoController::class, 'delete'])->name('cursos.delete');
Route::patch('/cursos/actualizar', [CursoController::class, 'update'])->name('cursos.update');

Route::get('/salones', [SalonController::class, 'index'])->name('salones.index');
Route::get('/salones/{id}/editar', [SalonController::class, 'buscarPorId'])->name('salones.edit');
Route::get('/salones/crear', [SalonController::class, 'create'])->name('salones.create');
Route::post('/salones', [SalonController::class, 'store'])->name('salones.store');
Route::delete('/salones/{id}', [SalonController::class, 'delete'])->name('salones.delete');
Route::patch('/salones/actualizar}', [SalonController::class, 'update'])->name('salones.update');
Route::post('/salones/buscador', [SalonController::class, 'buscador'])->name('salones.buscador');





Route::get('/orientadores', [OrientadorController::class, 'index'])->name('orientadores.index');
Route::get('/orientadores/{id}/editar', [OrientadorController::class, 'buscarPorId'])->name('orientadores.edit');
Route::get('/orientadores/crear', [OrientadorController::class, 'create'])->name('orientadores.create');
Route::post('/orientadores', [OrientadorController::class, 'store'])->name('orientadores.store');

Route::delete('/orientadores/eliminar/{id}', [OrientadorController::class, 'delete'])->name('orientadores.delete');
Route::patch('/orientadores/actualizar', [OrientadorController::class, 'update'])->name('orientadores.update');
Route::post('/orientadores/buscador', [OrientadorController::class, 'buscador'])->name('orientadores.buscador');
