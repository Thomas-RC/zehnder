<?php

namespace Message;

/**
 * Class Sender
 * @package Message
 */
class Sender
{
    /**
     * @param $to
     * @param $subject
     * @param $message
     */
    public static function send($to, $subject, $message)
    {
        $headers = 'From: info@ros-design.com' . "\r\n" .
            'Reply-To: info@ros-design.com' . "\r\n" .
            'X-Mailer: PHP/7';

        mail($to, $subject, $message, $headers);
    }
}



