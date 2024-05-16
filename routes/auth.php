<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\ConvenioController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\FormularioInscripcionController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\OrientadorController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\SalonController;
use App\Http\Controllers\TipoSalonController;
use App\Http\Controllers\ExportarCSVController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/buscar-formularios/{estado}', [DashboardController::class, 'buscarFormulariosPorEstado'])->name('dashboard.buscar-formularios');

    Route::get('/areas/crear', [AreaController::class, 'create'])->name('areas.create');
    Route::get('/areas/{id}/editar', [AreaController::class, 'buscarPorId'])->name('areas.edit');
    Route::post('areas', [AreaController::class, 'store'])->name('areas.store');
    Route::delete('/areas/{id}', [AreaController::class, 'delete'])->name('areas.delete');
    Route::patch('/areas', [AreaController::class, 'update'])->name('areas.update');
    Route::get('/areas/{page?}', [AreaController::class, 'paginar'])->name('areas.index');

    Route::get('/cursos/{id}/editar', [CursoController::class, 'buscarPorId'])->name('cursos.edit');
    Route::get('/cursos/crear', [CursoController::class, 'create'])->name('cursos.create');
    Route::post('/cursos', [CursoController::class, 'store'])->name('cursos.store');
    Route::delete('/cursos/{id}', [CursoController::class, 'delete'])->name('cursos.delete');
    Route::patch('/cursos/actualizar', [CursoController::class, 'update'])->name('cursos.update');
    Route::get('/cursos/{page?}', [CursoController::class, 'paginar'])->name('cursos.index');
    Route::post('/cursos/buscador', [CursoController::class, 'buscador'])->name('cursos.buscador');
    Route::get('/cursos/{page}/q/{criteria}', [CursoController::class, 'paginadorBuscador'])->name('cursos.buscador-paginador');

    Route::get('/salones/{id}/editar', [SalonController::class, 'buscarPorId'])->name('salones.edit');
    Route::get('/salones/crear', [SalonController::class, 'create'])->name('salones.create');
    Route::post('/salones', [SalonController::class, 'store'])->name('salones.store');
    Route::delete('/salones/{id}', [SalonController::class, 'delete'])->name('salones.delete');
    Route::patch('/salones/actualizar', [SalonController::class, 'update'])->name('salones.update');
    Route::post('/salones/buscador', [SalonController::class, 'buscador'])->name('salones.buscador');
    Route::get('/salones/{page?}', [SalonController::class, 'paginar'])->name('salones.index');
    Route::get('/salon/{page}/q/{criteria}', [SalonController::class, 'paginadorBuscador'])->name('salones.buscador-paginador');    

    Route::get('/orientadores/{id}/editar', [OrientadorController::class, 'edit'])->name('orientadores.edit');
    Route::get('/orientadores/crear', [OrientadorController::class, 'create'])->name('orientadores.create');
    Route::post('/orientadores', [OrientadorController::class, 'store'])->name('orientadores.store');
    Route::delete('/orientadores/eliminar/{id}', [OrientadorController::class, 'delete'])->name('orientadores.delete');
    Route::patch('/orientadores/actualizar', [OrientadorController::class, 'update'])->name('orientadores.update');
    Route::post('/orientadores/buscador', [OrientadorController::class, 'buscador'])->name('orientadores.buscador');
    Route::get('/orientadores/{page?}', [OrientadorController::class, 'listarPaginado'])->name('orientadores.index');
    Route::get('/orientadores/{page}/q/{criteria}', [OrientadorController::class, 'paginadorBuscador'])->name('orientadores.buscador-paginador');
    Route::get('/orientadores/{id}/mas-informacion', [OrientadorController::class, 'show'])->name('orientadores.moreInfo');    

    Route::get('/calendario',[CalendarioController::class, 'index'])->name('calendario.index');
    Route::get('/calendario/{id}/editar', [CalendarioController::class, 'edit'])->name('calendario.edit');
    Route::get('/calendario/crear', [CalendarioController::class, 'create'])->name('calendario.create');
    Route::post('/calendario', [CalendarioController::class, 'store'])->name('calendario.store');
    Route::delete('/calendario/{id}', [CalendarioController::class, 'destroy'])->name('calendario.delete');
    Route::patch('/calendario/{id}/actualizar', [CalendarioController::class, 'update'])->name('calendario.update');

    Route::get('/calendario/{id}/cursos', [CalendarioController::class, 'cursosDelCalendario'])->name('calendario.cursos');
    Route::post('/calendario/agregar-curso',[CalendarioController::class, 'agregarCursoACalendario'])->name('calendario.agregar_curso');
    Route::get('/calendario/{calendarioId}/area/{areaId}/cursos', [CalendarioController::class, 'listarCursosPorArea'])->name('calendario.cursos_por_area');
    Route::get('/calendario/{calendarioId}/area/{areaId}/cursos-periodo', [CalendarioController::class, 'listarCursosDelCalendario'])->name('calendario.cursos_por_calendario');
    Route::delete('/calendario/{calendarioId}/curso/{cursoCalendarioId}/area/{areaId}', [CalendarioController::class, 'retirarCursoACalendario'])->name('calendario.retirar_curso');
    Route::get('/calendario/{id}/estadisticas',[CalendarioController::class, 'estadisticas'])->name('calendario.estadisticas');    
    Route::get('/calendario/{id}/descargar-participantes',[CalendarioController::class, 'descargarParticipantes'])->name('calendario.descargar-participantes');

    Route::get('/grupos/{id}/editar', [GrupoController::class, 'edit'])->name('grupos.edit');
    Route::get('/grupos/crear', [GrupoController::class, 'create'])->name('grupos.create');
    Route::post('/grupos', [GrupoController::class, 'store'])->name('grupos.store');
    Route::delete('/grupos/{id}', [GrupoController::class, 'destroy'])->name('grupos.delete');
    Route::patch('/grupos/{id}/actualizar', [GrupoController::class, 'update'])->name('grupos.update');
    Route::post('/grupos/buscador', [GrupoController::class, 'buscadorGrupos'])->name('grupos.buscador');
    Route::get('/grupos/calendario/{calendarioId}/cursos/{cursoCalendarioIdActual}',[GrupoController::class, 'listarCursosPorCalendario'])->name('grupos.cursos_calendario');
    Route::get('/grupos/lista-orientadores/{cursoCalendarioId}/{orientadorIdActual}',[GrupoController::class, 'listarOrientadoresPorCursoCalendario'])->name('grupos.orientadores_por_curso_calendario');
    Route::get('/grupos/{page?}',[GrupoController::class, 'index'])->name('grupos.index');
    Route::get('/grupos/{page}/q/{criteria}', [GrupoController::class, 'buscadorGruposPaginados'])->name('grupos.buscador-paginador');
    Route::get('/grupos/{id}/mas-informacion', [GrupoController::class, 'masInformacion'])->name('grupos.mas-info');
    Route::get('/grupos/{id}/descargar-listado-participantes', [GrupoController::class, 'descargarListadoParticipantes'])->name('grupos.descargar-listado-participantes');
    Route::get('/grupos/{id}/descargar-planilla-asistencia', [GrupoController::class, 'descargarPlanillaAsistencia'])->name('grupos.descargar-planilla-asistencia');

    Route::get('/tipo-salones/{id}/editar', [TipoSalonController::class, 'edit'])->name('tipo-salones.edit');
    Route::get('/tipo-salones/crear', [TipoSalonController::class, 'create'])->name('tipo-salones.create');
    Route::post('/tipo-salones', [TipoSalonController::class, 'store'])->name('tipo-salones.store');
    Route::delete('/tipo-salones/{id}', [TipoSalonController::class, 'destroy'])->name('tipo-salones.delete');
    Route::patch('/tipo-salones/actualizar', [TipoSalonController::class, 'update'])->name('tipo-salones.update');
    Route::get('/tipo-salones/{page?}', [TipoSalonController::class, 'paginar'])->name('tipo-salones.index');
    
    Route::get('/convenios', [ConvenioController::class, 'index'])->name('convenios.index');
    Route::get('/convenios/crear', [ConvenioController::class, 'create'])->name('convenios.create');
    Route::get('/convenios/{id}/editar', [ConvenioController::class, 'edit'])->name('convenios.edit');
    Route::post('convenios', [ConvenioController::class, 'store'])->name('convenios.store');
    Route::delete('/convenios/{id}', [ConvenioController::class, 'destroy'])->name('convenios.delete');
    Route::patch('/convenios', [ConvenioController::class, 'update'])->name('convenios.update');
    Route::get('/convenios/{id}/mas-informacion', [ConvenioController::class, 'masInformacion'])->name('convenios.mas-info');
    Route::get('/convenios/{id}/beneficiarios', [ConvenioController::class, 'formBeneficiarios'])->name('convenios.beneficiarios');
    Route::post('/convenios/importar-beneficiario', [ConvenioController::class, 'cargarBeneficiarios'])->name('convenios.cargar-beneficiarios');   
    Route::get('convenios/{id}/exportar-participantes', [ExportarCSVController::class, 'listaParticipantesConvenio'])->name('convenios.exportar-participantes');
    Route::get('/convenios/{id}/facturar-convenio', [ConvenioController::class, 'facturarConvenio'])->name('convenios.facturar');

    Route::get('/nueva-inscripcion/paso-1/existencia-participante', [FormularioInscripcionController::class, 'index'])->name('formulario-inscripcion.paso-1');
    Route::get('/nueva-inscripcion/paso-2/datos-participante/tipo-documento/{tipoDocumento}/documento/{documento}', [FormularioInscripcionController::class, 'create'])->name('formulario-inscripcion.paso-2');
    Route::get('/nueva-inscripcion/paso-3/seleccion-curso/tipo-documento/{tipoDocumento}/documento/{documento}', [FormularioInscripcionController::class, 'edit'])->name('formulario-inscripcion.paso-3');
    Route::post('/nueva-inscripcion/paso-3/buscar-grupos', [FormularioInscripcionController::class, 'buscarGruposDisponiblesParaInscripcion'])->name('formulario-inscripcion.paso-3.buscar-grupos');
    Route::get('/nueva-inscripcion/paso-3/seleccion-curso/{participanteId}/participante/{calendarioId}/periodo/{areaId}/area', [FormularioInscripcionController::class, 'buscarGruposDisponiblesParaInscripcion2'])->name('formulario-inscripcion.paso-3-1.buscar-grupos');
    Route::get('/nueva-inscripcion/paso-4/confirmar-inscripion/participante/{participanteId}/grupo/{grupoId}', [FormularioInscripcionController::class, 'vistaConfirmarInscripcion'])->name('formulario-inscripcion.paso-4');
    Route::post('/nueva-inscripcion/paso-5/confirmar-inscripcion', [FormularioInscripcionController::class, 'confirmarInscripcion'])->name('formulario-inscripcion.paso-5');
    Route::get('/descargar-formato-pago/{nombre_archivo}', [FormularioInscripcionController::class, 'descargarFormatoPagoInscripcion'])->name('formulario-inscripcion.descargar-formato-pago');

    Route::post('/nueva-inscripcion', [FormularioInscripcionController::class, 'store'])->name('formulario-inscripcion.store');
    Route::post('/nueva-inscripcion/buscar-participante', [FormularioInscripcionController::class, 'buscarParticipantePorDocumento'])->name('formulario-inscripcion.buscar_participante_por_documento');
    Route::get('/formularios', [FormularioInscripcionController::class, 'listarParticipantes'])->name('formularios.index');
    Route::post('/formularios/filtro', [FormularioInscripcionController::class, 'filtrarInscripciones'])->name('formularios.buscar-inscritos');
    Route::get('/formularios/legalizar-inscripcion/{numeroFormulario}/formulario', [FormularioInscripcionController::class, 'editLegalizarInscripcion'])->name('formularios.edit-legalizar-inscripcion');
    Route::patch('/formularios/legalizar-inscripcion', [FormularioInscripcionController::class, 'legalizarInscripcion'])->name('formularios.legalizar-inscripcion');
    Route::patch('/formularios/{numeroFormulario}/participante/{participanteId}/anular', [FormularioInscripcionController::class, 'anularInscripcion'])->name('formularios.anular-inscripcion');
    Route::get('/formularios/p/{page}/periodo/{periodo}/estado/{estado?}', [FormularioInscripcionController::class, 'buscadorFormulariosPaginados'])->name('formularios.buscador-paginador');

    Route::get('/participantes/{id}/editar', [ParticipanteController::class, 'edit'])->name('participantes.edit');
    Route::get('/participantes/crear', [ParticipanteController::class, 'create'])->name('participantes.create');
    Route::patch('/participantes', [ParticipanteController::class, 'update'])->name('participantes.update');
    Route::post('/participantes', [ParticipanteController::class, 'store'])->name('participantes.store');
    Route::get('/participantes/{participanteId}/formularios-inscritos', [ParticipanteController::class, 'listarFormularios'])->name('participantes.formularios');
    Route::post('/participantes/buscador', [ParticipanteController::class, 'buscadorParticipantes'])->name('participantes.buscador');
    Route::delete('/participantes/{participanteId}', [ParticipanteController::class, 'destroy'])->name('participantes.delete');
    Route::patch('/participantes/{numeroFormulario}/{participanteId}', [ParticipanteController::class, 'anularInscripcion'])->name('participantes.anular-inscripcion');
    Route::get('/participantes/legalizar-inscripcion/{numeroFormulario}/formulario', [ParticipanteController::class, 'editLegalizarInscripcion'])->name('participantes.edit-legalizar-inscripcion');
    Route::patch('/participantes/legalizar-inscripcion', [ParticipanteController::class, 'legalizarInscripcion'])->name('participantes.legalizar-inscripcion');
    Route::get('/participantes/{page?}', [ParticipanteController::class, 'index'])->name('participantes.index');
    Route::get('/salones/{page}/q/{criteria}', [ParticipanteController::class, 'buscadorParticipantesPaginados'])->name('participantes.buscador-paginador');

});
