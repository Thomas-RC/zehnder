<?php

$var = [

    'DB_HOST' => 'db',
    'DB_USERNAME' => 'root',
    'DB_PASSWORD' => 'my_secret_pw_shh',
    'DB_NAME' => 'rest',
    'DB_PORT' => '3306',
    'DB_CHAR' => 'utf8',

    'SECRET_KEY' => 'Secret-Key',
    'ALGORITHM' => 'HS256'

];

foreach ($var as $key => $value)
{
    putenv("$key=$value");
}