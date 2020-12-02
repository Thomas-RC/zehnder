<?php
namespace Message;

class Sender
{
    public static function send($to, $subject, $message)
    {
        $headers = 'From: info@ros-design.com' . "\r\n" .
            'Reply-To: info@ros-design.com' . "\r\n" .
            'X-Mailer: PHP/7';

        mail($to, $subject, $message, $headers);
    }
}



