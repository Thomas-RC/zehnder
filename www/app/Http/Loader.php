<?php

namespace Http;


use Controller\Api\ErrorController;

/**
 * Class Loader
 * @package Http
 */
class Loader
{
    /**
     * @param $url
     * @return bool
     */
    public static function loadController($url): bool
    {
        return (new Loader)->checkController($url);
    }

    /**
     * @param $url
     * @return bool
     */
    private function checkController($url): bool
    {
        $class = 'Controller\Api\\' . ucfirst($url) . 'Controller';

        if (!class_exists($class))
            $class = ErrorController::class;

        $controller = new $class();

        if (in_array(ucfirst($url), $controller->getControllers()) && method_exists($class, $url))
        {
            $controller->$url();
            return true;
        }

        $controller = new ErrorController();
        $controller->error($url);
        return false;
    }

}