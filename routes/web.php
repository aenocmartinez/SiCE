<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

use App\Http\Controllers\InscripcionPublicaController;
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

// Route::view('/', 'home')->name('home');

require __DIR__.'/auth.php';


Route::get('/', [HomeController::class, 'index'])->name('home');


// Version pública


Route::get('/public/inscripcion', [InscripcionPublicaController::class, 'index'])->name('public.inicio');
Route::post('/public/inscripcion/consultar-participante', [InscripcionPublicaController::class, 'consultarExistencia'])->name('public.consultar-existencia');
Route::get('/public/inscripcion/formulario-participante', [InscripcionPublicaController::class, 'formularioParticipante'])->name('public.formulario-participante');
Route::post('/public/inscripcion/guardar-datos-participante', [InscripcionPublicaController::class, 'guardarDatosParticipante'])->name('public.guardar-datos-participante');

Route::get('/public/inscripcion/participante/{participanteId}/grupo/{grupoId}', [InscripcionPublicaController::class, 'formularioInscripcion'])->name('public.inscribir-participante-a-grupo');
Route::post('/public/inscripcion/confirmar', [InscripcionPublicaController::class, 'confirmarInscripcion'])->name('public.confirmar-inscripcion');