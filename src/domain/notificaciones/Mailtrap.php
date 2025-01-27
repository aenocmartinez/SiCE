<?php

namespace Src\domain\notificaciones;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailtrap implements MedioNotificacion
{
    public function enviar(ContenidoNotificacionDTO $contenido): void
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST'); 
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME'); 
            $mail->Password = env('MAIL_PASSWORD'); 
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', PHPMailer::ENCRYPTION_STARTTLS); 
            $mail->Port = env('MAIL_PORT', 587); 

            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME', 'Cursos de Extensión'));
            $mail->addAddress($contenido->getDestinatario()); // Dirección del destinatario

            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);
            $mail->Subject = $contenido->getAsunto();
            $mail->Body = $contenido->getMensaje();
            
            if ($contenido->getAdjunto()) {
                $mail->addAttachment($contenido->getAdjunto());
            }

            $mail->send();
            echo "Correo enviado a {$contenido->getDestinatario()}\n";
        } catch (Exception $e) {
            echo "Error al enviar el correo: {$mail->ErrorInfo}\n";
        }
    }
}
