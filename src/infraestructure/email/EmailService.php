<?php

namespace Src\infraestructure\email;

use Illuminate\Support\Facades\Mail;

class EmailService {

    public static function SendEmail($subject, $message, $recipients) {        
        Mail::send([], [], function ($email) use ($subject, $message, $recipients) {
            $email->to($recipients)
                    ->subject($subject)
                    ->setBody($message, 'text/html');
        });
    }
}