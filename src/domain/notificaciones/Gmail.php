<?php

namespace Src\domain\notificaciones;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Gmail implements MedioNotificacion
{
    public function enviar(ContenidoNotificacionDTO $contenido): void
    {
        $mail = new PHPMailer(true);

        try {
            // Configuración para el servidor SMTP de Gmail
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';                     
            $mail->SMTPAuth   = true;                                 
            $mail->Username   = env('MAIL_GMAIL');
            $mail->Password   = env('MAIL_GMAIL_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       
            $mail->Port       = 587;  
            // $mail->SMTPDebug = 3; // O 3 para más detalles                                

            $mail->setFrom(env('MAIL_GMAIL'), 'Cursos de Extensión');
            $mail->addAddress($contenido->getDestinatario());
           
            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);
            $mail->Subject = $contenido->getAsunto();
            $mail->Body    = $contenido->getMensaje();
            
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
