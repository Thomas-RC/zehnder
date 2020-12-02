<?php


namespace Http;


/**
 * Class Router
 * @package Http
 */
class Router
{
    /**
     * @param $url
     * @return bool
     */
    public static function call($url)
    {
        if (Loader::loadController($url))
            return true;

        return false;
    }

    /**
     * @return mixed|string
     */
    public static function getPath()
    {
        $controller = array_values(array_filter(explode('/', $_SERVER['REQUEST_URI'])));
        return isset($controller[2]) ? $controller[1] = '' : $controller[1];
    }

    /**
     * @param $message
     * @param $status
     */
    public static function getHeader($message, $status)
    {

        if (isset($_SERVER['HTTP_ORIGIN']))
        {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

            exit(0);
        }
        header('HTTP/1.1 ' . $status . ' ' . $message);
        header('Content-Type: application/json; charset=utf-8');
    }

    /**
     * @return mixed
     */
    public static function getParamsPost()
    {
        $json = file_get_contents('php://input');
        return json_decode($json, true);
    }

    /**
     * @return mixed
     */
    public static function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return mixed
     */
    public static function getAuthorization()
    {
        return $_SERVER['HTTP_AUTHORIZATION'];
    }


}