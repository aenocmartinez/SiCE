<?php

use App\Http\Controllers\CertificadoAsistenciaController;
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

Route::get('/public/inscripcion/{participanteId}/participante/{grupoId}/grupo/{formularioId}/formulario', [InscripcionPublicaController::class, 'formularioInscripcion'])->name('public.inscribir-participante-a-grupo');
Route::post('/public/inscripcion/confirmar-v2', [InscripcionPublicaController::class, 'confirmarInscripcion2'])->name('public.confirmar-inscripcion2');
Route::post('/public/inscripcion/confirmar', [InscripcionPublicaController::class, 'confirmarInscripcion'])->name('public.confirmar-inscripcion');



Route::post('/public/inscripcion/cargar-comprobante-pago', [InscripcionPublicaController::class, 'uploadPDF'])->name('upload.pdf');

Route::get('/public/inscripcion/{participanteId}/descargar-recibo-matricula', [InscripcionPublicaController::class, 'descargarReciboMatricula'])->name('public.descargar-recibo-matricula');

Route::get('/public/inscripcion/{participanteId}/selecionar-curso', [InscripcionPublicaController::class, 'seleccionarCursoMatricula'])->name('public.seleccionar-curso');

Route::get('/public/inscripcion/salida-segura', [InscripcionPublicaController::class, 'salidaSegura'])->name('public.salidaSegura');

Route::get('/public/inscripcion/{participanteId}/participante/{grupoId}/grupo/{formularioId}/agregar-curso', [InscripcionPublicaController::class, 'agregarCursoParaMatricular'])->name('public.agregar_curso_a_matricula');
Route::get('/public/inscripcion/{participanteId}/participante/{grupoId}/grupo/{formularioId}/quitar-curso', [InscripcionPublicaController::class, 'quitarCursoParaMatricular'])->name('public.quitar-curso');

Route::get('/public/inscripcion/{participanteId}/participante/pagar-matricula', [InscripcionPublicaController::class, 'pagarMatricula'])->name('public.pagar-matricula');

Route::get('/public/preinscripcion/{participanteId}/participante/{grupoId}/curso', [InscripcionPublicaController::class, 'realizarPreinscripcion'])->name('public.presinscribirse');


// Descargar certifiado de participación
Route::get('/certificado-asistencia', [CertificadoAsistenciaController::class, 'formulario'])
    ->name('certificado.asistencia.formulario');

Route::post('/certificado-asistencia/verificar', [CertificadoAsistenciaController::class, 'verificar'])
    ->name('certificado.asistencia.verificar');

Route::post('/certificado-asistencia/descargar', [CertificadoAsistenciaController::class, 'descargar'])
    ->name('certificado.asistencia.descargar');