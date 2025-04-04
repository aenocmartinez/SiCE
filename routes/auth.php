<?php

use App\Http\Controllers\AnotacionController;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
// use App\Http\Controllers\Auth\NewPasswordController;
// use App\Http\Controllers\Auth\PasswordResetLinkController;
// use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\CambiosTrasladosController;
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
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Route::get('register', [RegisteredUserController::class, 'create'])
    //             ->middleware('role:superAdmin')
    //             ->name('users.create');

    // Route::post('register', [RegisteredUserController::class, 'store'])
    //             ->middleware('role:superAdmin');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
    //             ->name('password.request');

    // Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
    //             ->name('password.email');

    // Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
    //             ->name('password.reset');

    // Route::post('reset-password', [NewPasswordController::class, 'store'])
    //             ->name('password.update');
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

    Route::get('usuarios', [UserController::class, 'index'])->middleware('role:superAdmin')->name('users.index');
    Route::get('usuarios/crear', [UserController::class, 'create'])->middleware('role:superAdmin')->name('users.create');
    Route::post('usuarios/guardar', [UserController::class, 'store'])->middleware('role:superAdmin')->name('users.store'); 
    Route::get('usuarios/{id}/edit', [UserController::class, 'edit'])->middleware('role:superAdmin')->name('users.edit');
    Route::patch('usuarios/actualizar', [UserController::class, 'update'])->middleware('role:superAdmin')->name('users.update'); 
    Route::get('usuarios/{id}/mi-perfil', [UserController::class, 'profile'])->middleware('role:superAdmin,Admin,orientador')->name('users.profile');
    Route::patch('usuarios/mi-perfil', [UserController::class, 'updateProfile'])->middleware('role:superAdmin,Admin,orientador')->name('users.update_profile');

    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('role:Admin,superAdmin,orientador')->name('dashboard');
    Route::get('/dashboard/buscar-formularios/{estado}', [DashboardController::class, 'buscarFormulariosPorEstado'])->middleware('role:Admin,superAdmin')->name('dashboard.buscar-formularios');

    Route::get('/areas/crear', [AreaController::class, 'create'])->middleware('role:Admin,superAdmin')->name('areas.create');
    Route::get('/areas/{id}/editar', [AreaController::class, 'buscarPorId'])->middleware('role:Admin,superAdmin')->name('areas.edit');
    Route::post('areas', [AreaController::class, 'store'])->middleware('role:Admin,superAdmin')->name('areas.store');
    Route::delete('/areas/{id}', [AreaController::class, 'delete'])->middleware('role:Admin,superAdmin')->name('areas.delete');
    Route::patch('/areas', [AreaController::class, 'update'])->middleware('role:Admin,superAdmin')->name('areas.update');
    Route::get('/areas/{page?}', [AreaController::class, 'paginar'])->middleware('role:Admin,superAdmin')->name('areas.index');

    Route::get('/cambios-y-traslados/crear', [CambiosTrasladosController::class, 'create'])->middleware('role:Admin,superAdmin')->name('cambios-traslados.create');
    Route::get('/cambios-y-traslados/{page?}', [CambiosTrasladosController::class, 'paginar'])->middleware('role:Admin,superAdmin')->name('cambios-traslados.index');
    Route::post('/cambios-y-traslados/buscador', [CambiosTrasladosController::class, 'buscadorCambiosYTraslados'])->middleware('role:Admin,superAdmin')->name('cambios-traslados.buscador');
    Route::get('/cambios-y-traslados/{page}/q/{criteria}', [CambiosTrasladosController::class, 'buscadorCambiosYTrasladosPaginados'])->middleware('role:Admin,superAdmin')->name('cambios-traslados.buscador-paginador');
    Route::post('/cambios-y-traslados/buscar-participante', [CambiosTrasladosController::class, 'buscarParticipantePorDocumento'])->middleware('role:Admin,superAdmin')->name('cambios-y-traslados.buscar_participante_por_documento');
    Route::get('/cambios-y-traslados/formulario/{formulario}/motivo/{motivo}', [CambiosTrasladosController::class, 'formularioDeTramite'])->middleware('role:Admin,superAdmin')->name('cambios-traslados.form-tramite');
    Route::get('/cambios-y-traslados/{area_id}/cursos-para-matricular', [CambiosTrasladosController::class, 'listarCursosParaMatricular'])->middleware('role:Admin,superAdmin')->name('cambios-traslados.cursos-para-matricular');
    Route::post('/cambios-traslados/realizar-cambio-de-curso', [CambiosTrasladosController::class, 'realizarCambioDeGrupo'])->middleware('role:Admin,superAdmin')->name('cambios_traslados.realizar_cambio_de_grupo');
    Route::post('/cambios-traslados/aplazar-inscripcion', [CambiosTrasladosController::class, 'aplazarUnaInscripcion'])->middleware('role:Admin,superAdmin')->name('cambios_traslados.aplazar_inscripcion');
    Route::post('/cambios-traslados/devolucion-inscripcion', [CambiosTrasladosController::class, 'hacerDevolucionAUnaInscripcion'])->middleware('role:Admin,superAdmin')->name('cambios_traslados.hacer_devolucion');

    Route::get('/cursos/{id}/editar', [CursoController::class, 'buscarPorId'])->middleware('role:Admin,superAdmin')->name('cursos.edit');
    Route::get('/cursos/crear', [CursoController::class, 'create'])->middleware('role:Admin,superAdmin')->name('cursos.create');
    Route::post('/cursos', [CursoController::class, 'store'])->middleware('role:Admin,superAdmin')->name('cursos.store');
    Route::delete('/cursos/{id}', [CursoController::class, 'delete'])->middleware('role:Admin,superAdmin')->name('cursos.delete');
    Route::patch('/cursos/actualizar', [CursoController::class, 'update'])->middleware('role:Admin,superAdmin')->name('cursos.update');
    Route::get('/cursos/{page?}', [CursoController::class, 'paginar'])->middleware('role:Admin,superAdmin')->name('cursos.index');
    Route::post('/cursos/buscador', [CursoController::class, 'buscador'])->middleware('role:Admin,superAdmin')->name('cursos.buscador');
    Route::get('/cursos/{page}/q/{criteria}', [CursoController::class, 'paginadorBuscador'])->middleware('role:Admin,superAdmin')->name('cursos.buscador-paginador');

    Route::get('/salones/{id}/editar', [SalonController::class, 'buscarPorId'])->middleware('role:Admin,superAdmin')->name('salones.edit');
    Route::get('/salones/crear', [SalonController::class, 'create'])->middleware('role:Admin,superAdmin')->name('salones.create');
    Route::post('/salones', [SalonController::class, 'store'])->middleware('role:Admin,superAdmin')->name('salones.store');
    Route::delete('/salones/{id}', [SalonController::class, 'delete'])->middleware('role:Admin,superAdmin')->name('salones.delete');
    Route::patch('/salones/actualizar', [SalonController::class, 'update'])->middleware('role:Admin,superAdmin')->name('salones.update');
    Route::post('/salones/buscador', [SalonController::class, 'buscador'])->middleware('role:Admin,superAdmin')->name('salones.buscador');
    Route::get('/salones/{page?}', [SalonController::class, 'paginar'])->middleware('role:Admin,superAdmin')->name('salones.index');
    Route::get('/salon/{page}/q/{criteria}', [SalonController::class, 'paginadorBuscador'])->middleware('role:Admin,superAdmin')->name('salones.buscador-paginador');    

    Route::get('/orientadores/{id}/editar', [OrientadorController::class, 'edit'])->middleware('role:Admin,superAdmin')->name('orientadores.edit');
    Route::get('/orientadores/crear', [OrientadorController::class, 'create'])->middleware('role:Admin,superAdmin')->name('orientadores.create');
    Route::post('/orientadores', [OrientadorController::class, 'store'])->middleware('role:Admin,superAdmin')->name('orientadores.store');
    Route::delete('/orientadores/eliminar/{id}', [OrientadorController::class, 'delete'])->middleware('role:Admin,superAdmin')->name('orientadores.delete');
    Route::patch('/orientadores/actualizar', [OrientadorController::class, 'update'])->middleware('role:Admin,superAdmin')->name('orientadores.update');
    Route::post('/orientadores/buscador', [OrientadorController::class, 'buscador'])->middleware('role:Admin,superAdmin')->name('orientadores.buscador');
    Route::get('/orientadores/{page?}', [OrientadorController::class, 'listarPaginado'])->middleware('role:Admin,superAdmin')->name('orientadores.index');
    Route::get('/orientadores/{page}/q/{criteria}', [OrientadorController::class, 'paginadorBuscador'])->middleware('role:Admin,superAdmin')->name('orientadores.buscador-paginador');
    Route::get('/orientadores/{id}/mas-informacion', [OrientadorController::class, 'show'])->middleware('role:Admin,superAdmin')->name('orientadores.moreInfo');    
    Route::patch('/orientadores/{orientadorId}/grupo/{grupoId}/cancelar', [OrientadorController::class, 'cancelar'])->middleware('role:Admin,superAdmin')->name('orientador.cancelar-grupo');

    Route::get('/calendario',[CalendarioController::class, 'index'])->middleware('role:Admin,superAdmin')->name('calendario.index');
    Route::get('/calendario/{id}/editar', [CalendarioController::class, 'edit'])->middleware('role:Admin,superAdmin')->name('calendario.edit');
    Route::get('/calendario/crear', [CalendarioController::class, 'create'])->middleware('role:Admin,superAdmin')->name('calendario.create');
    Route::post('/calendario', [CalendarioController::class, 'store'])->middleware('role:Admin,superAdmin')->name('calendario.store');
    Route::delete('/calendario/{id}', [CalendarioController::class, 'destroy'])->middleware('role:Admin,superAdmin')->name('calendario.delete');
    Route::patch('/calendario/{id}/actualizar', [CalendarioController::class, 'update'])->middleware('role:Admin,superAdmin')->name('calendario.update');

    Route::get('/calendario/{id}/cursos', [CalendarioController::class, 'cursosDelCalendario'])->middleware('role:Admin,superAdmin')->name('calendario.cursos');
    Route::get('/calendario/{calendarioId}/area/{areaId}/cursos', [CalendarioController::class, 'listarCursosPorArea'])->middleware('role:Admin,superAdmin')->name('calendario.cursos_por_area');
    Route::get('/calendario/{calendarioId}/area/{areaId}/cursos-periodo', [CalendarioController::class, 'listarCursosDelCalendario'])->middleware('role:Admin,superAdmin')->name('calendario.cursos_por_calendario');
    Route::get('/calendario/{id}/estadisticas',[CalendarioController::class, 'estadisticas'])->middleware('role:Admin,superAdmin')->name('calendario.estadisticas');    
    Route::get('/calendario/{id}/descargar-participantes',[CalendarioController::class, 'descargarParticipantes'])->middleware('role:Admin,superAdmin')->name('calendario.descargar-participantes');
    Route::get('/calendario/{id}/descargar-cuadro-110',[CalendarioController::class, 'generarReporteNumeroCursosYParticipantesPorJornada'])->middleware('role:Admin,superAdmin')->name('calendario.descargar-cuadro-110');
    Route::post('/calendario/abrir-cursos',[CalendarioController::class, 'darAperturaACursosDeUnPeriodo'])->middleware('role:Admin,superAdmin')->name('calendario.dar_apertura_a_cursos_de_un_periodo');
    Route::get('/calendario/{id}/cerrar',[CalendarioController::class, 'cerrarPeriodo'])->middleware('role:Admin,superAdmin')->name('calendario.cerrar');
    

    Route::get('/grupos/{id}/editar', [GrupoController::class, 'edit'])->middleware('role:Admin,superAdmin')->name('grupos.edit');
    Route::get('/grupos/crear', [GrupoController::class, 'create'])->middleware('role:Admin,superAdmin')->name('grupos.create');
    Route::post('/grupos', [GrupoController::class, 'store'])->middleware('role:Admin,superAdmin')->name('grupos.store');
    Route::delete('/grupos/{id}', [GrupoController::class, 'destroy'])->middleware('role:Admin,superAdmin')->name('grupos.delete');
    Route::patch('/grupos/{id}/actualizar', [GrupoController::class, 'update'])->middleware('role:Admin,superAdmin')->name('grupos.update');
    Route::post('/grupos/buscador', [GrupoController::class, 'buscadorGrupos'])->middleware('role:Admin,superAdmin')->name('grupos.buscador');
    Route::get('/grupos/calendario/{calendarioId}/cursos/{cursoCalendarioIdActual}',[GrupoController::class, 'listarCursosPorCalendario'])->middleware('role:Admin,superAdmin')->name('grupos.cursos_calendario');
    Route::get('/grupos/lista-orientadores/{cursoCalendarioId}/{orientadorIdActual}',[GrupoController::class, 'listarOrientadoresPorCursoCalendario'])->middleware('role:Admin,superAdmin')->name('grupos.orientadores_por_curso_calendario');
    Route::get('/grupos/{page?}',[GrupoController::class, 'index'])->middleware('role:Admin,superAdmin')->name('grupos.index');
    Route::get('grupos/{criterio}/q/{page?}', [GrupoController::class, 'buscadorGruposPaginados'])->middleware('role:Admin,superAdmin')->name('grupos.buscador-paginador');
    Route::get('/grupos/{id}/mas-informacion', [GrupoController::class, 'masInformacion'])->middleware('role:Admin,superAdmin')->name('grupos.mas-info');
    Route::get('/grupos/{id}/descargar-listado-participantes', [GrupoController::class, 'descargarListadoParticipantes'])->middleware('role:Admin,superAdmin')->name('grupos.descargar-listado-participantes');
    Route::get('/grupos/{id}/descargar-planilla-asistencia', [GrupoController::class, 'descargarPlanillaAsistencia'])->middleware('role:Admin,superAdmin')->name('grupos.descargar-planilla-asistencia');
    Route::get('/grupos/{id}/descargar-estado-legalizacion-participantes', [GrupoController::class, 'descargarReporteEstadoDeLegalizaciónDeParticipantes'])->middleware('role:Admin,superAdmin')->name('grupos.descargar-estado-legalizacion-participantes');
    Route::get('/grupos/estado-cursos/{tipo}', [GrupoController::class, 'listarCursosAbiertosOCerrados'])->middleware('role:Admin,superAdmin')->name('grupos.estado-cursos');
    Route::get('/grupos/{id}/participantes-pendientes-pago', [GrupoController::class, 'listarParticipantesPendientesDePagoPorGrupo'])->middleware('role:Admin,superAdmin')->name('grupos.participantesPendientesPago');

    Route::get('/tipo-salones/{id}/editar', [TipoSalonController::class, 'edit'])->middleware('role:Admin,superAdmin')->name('tipo-salones.edit');
    Route::get('/tipo-salones/crear', [TipoSalonController::class, 'create'])->middleware('role:Admin,superAdmin')->name('tipo-salones.create');
    Route::post('/tipo-salones', [TipoSalonController::class, 'store'])->middleware('role:Admin,superAdmin')->name('tipo-salones.store');
    Route::delete('/tipo-salones/{id}', [TipoSalonController::class, 'destroy'])->middleware('role:Admin,superAdmin')->name('tipo-salones.delete');
    Route::patch('/tipo-salones/actualizar', [TipoSalonController::class, 'update'])->middleware('role:Admin,superAdmin')->name('tipo-salones.update');
    Route::get('/tipo-salones/{page?}', [TipoSalonController::class, 'paginar'])->middleware('role:Admin,superAdmin')->name('tipo-salones.index');
    
    Route::get('/convenios', [ConvenioController::class, 'index'])->middleware('role:Admin,superAdmin')->name('convenios.index');
    Route::get('/convenios/crear', [ConvenioController::class, 'create'])->middleware('role:Admin,superAdmin')->name('convenios.create');
    Route::get('/convenios/{id}/editar', [ConvenioController::class, 'edit'])->middleware('role:Admin,superAdmin')->name('convenios.edit');
    Route::post('convenios', [ConvenioController::class, 'store'])->middleware('role:Admin,superAdmin')->name('convenios.store');
    Route::delete('/convenios/{id}', [ConvenioController::class, 'destroy'])->middleware('role:Admin,superAdmin')->name('convenios.delete');
    Route::patch('/convenios', [ConvenioController::class, 'update'])->middleware('role:Admin,superAdmin')->name('convenios.update');
    Route::get('/convenios/{id}/mas-informacion', [ConvenioController::class, 'masInformacion'])->middleware('role:Admin,superAdmin')->name('convenios.mas-info');
    Route::get('/convenios/{id}/beneficiarios', [ConvenioController::class, 'formBeneficiarios'])->middleware('role:Admin,superAdmin')->name('convenios.beneficiarios');
    Route::post('/convenios/importar-beneficiario', [ConvenioController::class, 'cargarBeneficiarios'])->middleware('role:Admin,superAdmin')->name('convenios.cargar-beneficiarios');   
    Route::get('convenios/{id}/exportar-participantes', [ExportarCSVController::class, 'listaParticipantesConvenio'])->middleware('role:Admin,superAdmin')->name('convenios.exportar-participantes');
    Route::get('/convenios/{id}/facturar-convenio', [ConvenioController::class, 'facturarConvenio'])->middleware('role:Admin,superAdmin')->name('convenios.facturar');
    Route::get('/periodos/{id}/mostrar-convenios', [ConvenioController::class, 'listarConveniosPorPeriodo'])->middleware('role:Admin,superAdmin')->name('convenios.listar-convenios-por-periodo');

    Route::get('/nueva-inscripcion/paso-1/existencia-participante', [FormularioInscripcionController::class, 'index'])->middleware('role:Admin,superAdmin')->name('formulario-inscripcion.paso-1');
    Route::get('/nueva-inscripcion/paso-2/datos-participante/tipo-documento/{tipoDocumento}/documento/{documento}', [FormularioInscripcionController::class, 'create'])->middleware('role:Admin,superAdmin')->name('formulario-inscripcion.paso-2');
    Route::get('/nueva-inscripcion/paso-3/seleccion-curso/tipo-documento/{tipoDocumento}/documento/{documento}', [FormularioInscripcionController::class, 'edit'])->middleware('role:Admin,superAdmin')->name('formulario-inscripcion.paso-3');
    Route::post('/nueva-inscripcion/paso-3/buscar-grupos', [FormularioInscripcionController::class, 'buscarGruposDisponiblesParaInscripcion'])->middleware('role:Admin,superAdmin')->name('formulario-inscripcion.paso-3.buscar-grupos');
    Route::get('/nueva-inscripcion/paso-3/seleccion-curso/{participanteId}/participante/{calendarioId}/periodo/{areaId}/area', [FormularioInscripcionController::class, 'buscarGruposDisponiblesParaInscripcion2'])->middleware('role:Admin,superAdmin')->name('formulario-inscripcion.paso-3-1.buscar-grupos');
    Route::get('/nueva-inscripcion/paso-4/confirmar-inscripion/participante/{participanteId}/grupo/{grupoId}', [FormularioInscripcionController::class, 'vistaConfirmarInscripcion'])->middleware('role:Admin,superAdmin')->name('formulario-inscripcion.paso-4');
    Route::post('/nueva-inscripcion/paso-5/confirmar-inscripcion', [FormularioInscripcionController::class, 'confirmarInscripcion'])->middleware('role:Admin,superAdmin')->name('formulario-inscripcion.paso-5');
    Route::get('/descargar-formato-pago/{nombre_archivo}', [FormularioInscripcionController::class, 'descargarFormatoPagoInscripcion'])->middleware('role:Admin,superAdmin')->name('formulario-inscripcion.descargar-formato-pago');
    Route::get('/descargar-recibo-matricula/{participanteId}/periodo/{calendarioId?}', [FormularioInscripcionController::class, 'descargarReciboMatricula'])->middleware('role:Admin,superAdmin')->name('formulario-inscripcion.descargar-recibo-matricula');

    Route::post('/nueva-inscripcion', [FormularioInscripcionController::class, 'store'])->middleware('role:Admin,superAdmin')->name('formulario-inscripcion.store');
    Route::post('/nueva-inscripcion/buscar-participante', [FormularioInscripcionController::class, 'buscarParticipantePorDocumento'])->middleware('role:Admin,superAdmin')->name('formulario-inscripcion.buscar_participante_por_documento');
    Route::get('/formularios', [FormularioInscripcionController::class, 'listarParticipantes'])->middleware('role:Admin,superAdmin')->name('formularios.index');
    Route::post('/formularios/filtro', [FormularioInscripcionController::class, 'filtrarInscripciones'])->middleware('role:Admin,superAdmin')->name('formularios.buscar-inscritos');
    Route::get('/formularios/legalizar-inscripcion/{numeroFormulario}/formulario', [FormularioInscripcionController::class, 'editLegalizarInscripcion'])->middleware('role:Admin,superAdmin')->name('formularios.edit-legalizar-inscripcion');
    Route::patch('/formularios/legalizar-inscripcion', [FormularioInscripcionController::class, 'legalizarInscripcion'])->middleware('role:Admin,superAdmin')->name('formularios.legalizar-inscripcion');
    Route::patch('/formularios/{numeroFormulario}/participante/{participanteId}/anular', [FormularioInscripcionController::class, 'anularInscripcion'])->middleware('role:Admin,superAdmin')->name('formularios.anular-inscripcion');
    Route::get('/formularios/p/{page}/periodo/{periodo}/estado/{estado?}', [FormularioInscripcionController::class, 'buscadorFormulariosPaginados'])->middleware('role:Admin,superAdmin')->name('formularios.buscador-paginador');
    Route::get('/formularios/detalle-inscripcion/{numeroFormulario}/formulario', [FormularioInscripcionController::class, 'verDetalleInscripcion'])->middleware('role:Admin,superAdmin')->name('formularios.ver-detalle-inscripcion');

    Route::get('/participantes/{id}/editar', [ParticipanteController::class, 'edit'])->middleware('role:Admin,superAdmin')->name('participantes.edit');
    Route::get('/participantes/crear', [ParticipanteController::class, 'create'])->middleware('role:Admin,superAdmin')->name('participantes.create');
    Route::patch('/participantes', [ParticipanteController::class, 'update'])->middleware('role:Admin,superAdmin')->name('participantes.update');
    Route::post('/participantes', [ParticipanteController::class, 'store'])->middleware('role:Admin,superAdmin')->name('participantes.store');
    Route::get('/participantes/{participanteId}/formularios-inscritos', [ParticipanteController::class, 'listarFormularios'])->middleware('role:Admin,superAdmin')->name('participantes.formularios');
    Route::post('/participantes/buscador', [ParticipanteController::class, 'buscadorParticipantes'])->middleware('role:Admin,superAdmin')->name('participantes.buscador');
    Route::delete('/participantes/{participanteId}', [ParticipanteController::class, 'destroy'])->middleware('role:Admin,superAdmin')->name('participantes.delete');
    Route::patch('/participantes/{numeroFormulario}/{participanteId}', [ParticipanteController::class, 'anularInscripcion'])->middleware('role:Admin,superAdmin')->name('participantes.anular-inscripcion');
    Route::get('/participantes/legalizar-inscripcion/{numeroFormulario}/formulario', [ParticipanteController::class, 'editLegalizarInscripcion'])->middleware('role:Admin,superAdmin')->name('participantes.edit-legalizar-inscripcion');
    Route::patch('/participantes/legalizar-inscripcion', [ParticipanteController::class, 'legalizarInscripcion'])->middleware('role:Admin,superAdmin')->name('participantes.legalizar-inscripcion');
    Route::get('/participantes/{page?}', [ParticipanteController::class, 'index'])->middleware('role:Admin,superAdmin')->name('participantes.index');
    Route::get('/salones/{page}/q/{criteria}', [ParticipanteController::class, 'buscadorParticipantesPaginados'])->middleware('role:Admin,superAdmin')->name('participantes.buscador-paginador');
    Route::get('/participantes/formulario/{numeroFormulario}/detalle-inscripcion', [ParticipanteController::class, 'verDetalleInscripcion'])->middleware('role:Admin,superAdmin')->name('participantes.ver-detalle-inscripcion');

    Route::get('/comentarios', [AnotacionController::class, 'index'])->middleware('role:Admin,superAdmin')->name('comentarios');
    Route::post('/comentarios', [AnotacionController::class, 'buscar_comentario'])->middleware('role:Admin,superAdmin')->name('comentarios.buscar');

    Route::get('/notificaciones/inicio-de-clases', [NotificacionController::class, 'recordarInicioDeClases'])->name('notificacion.recordarInicioClase');
    Route::get('/periodo/{periodoId}/notificaciones', [NotificacionController::class, 'notificacionesPeriodo'])->name('notificacion.periodo');    
    Route::get('/notificaciones/enviar', [NotificacionController::class, 'enviarNotificacion'])->name('notificacion.enviar');


    // Registro de asistencia
    Route::post('/asistencia/registrar', [OrientadorController::class, 'registrarAsistencia'])->middleware('role:orientador')->name('asistencia.registrar');
    Route::get('/asistencia/formulario', [OrientadorController::class, 'formularioAsistencia'])->middleware('role:orientador')->name('asistencia.formulario');
    Route::get('/asistencia/reportes', [OrientadorController::class, 'formularioReportePorCurso'])->middleware('role:orientador')->name('asistencia.formulario-reportes');
    Route::get('/asistencia/participante', [OrientadorController::class, 'formularioReporteParticipante'])->middleware('role:orientador')->name('asistencia.participante');
});
