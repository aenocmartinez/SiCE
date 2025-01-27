<?php

// Cargar el autoload de Composer
require __DIR__ . '/../vendor/autoload.php';

// Inicializar la aplicación de Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Src\domain\Calendario;
use Src\usecase\notificaciones\RecordatorioInicioDeClaseUseCase;

// Ruta del archivo de log
$logFile = __DIR__ . '/../storage/logs/processRecordatorio.log';

// Función para escribir en el log
function logMessage($message, $logFile)
{
    file_put_contents($logFile, date('[Y-m-d H:i:s] ') . $message . PHP_EOL, FILE_APPEND);
}

// Log inicial
logMessage("Iniciando script processRecordatorio.php", $logFile);

// Obtener los argumentos desde la línea de comandos
$options = getopt('', ['periodo:']);

if (!isset($options['periodo'])) {
    logMessage("No se proporcionó un ID de periodo.", $logFile);
    exit(1);
}

$periodoId = $options['periodo'];
logMessage("Periodo recibido: {$periodoId}", $logFile);

// Recuperar el periodo
$periodo = Calendario::buscarPorId($periodoId);

if (!$periodo || !$periodo->existe()) {
    logMessage("No se encontró el periodo con ID {$periodoId}.", $logFile);
    exit(1);
}

// Ejecutar el caso de uso
try {
    logMessage("Ejecutando caso de uso RecordatorioInicioDeClaseUseCase", $logFile);
    (new RecordatorioInicioDeClaseUseCase())->Ejecutar($periodo);
    logMessage("El envío de correos se completó correctamente.", $logFile);
} catch (\Exception $e) {
    logMessage("Error durante el envío de correos: " . $e->getMessage(), $logFile);
    exit(1);
}

logMessage("Finalizando script processRecordatorio.php", $logFile);
exit(0);
