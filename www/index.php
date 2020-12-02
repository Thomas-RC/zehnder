<?php



//$d1 = new Datetime();
//$d2 = new Datetime();
//$d1->add(new DateInterval('PT5M30S'));
//echo $d2->format('U');
//echo'<br>';
//echo $d1->format('U');

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}
require_once __DIR__.'/config/env.php';
require __DIR__ . '/vendor/autoload.php';
require_once __DIR__.'/public/index.php';
