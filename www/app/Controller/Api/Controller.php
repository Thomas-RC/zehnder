<?php


namespace Controller\Api;


/**
 * Class Controller
 * @package Controller\Api
 */
class Controller
{
    /**
     * @var string[]
     */
    protected $controllers = [
        'Register',
        'Login',
        'User',
        'Error',
        'Logout'
    ];

    /**
     * @return string[]
     */
    public function getControllers(): array
    {
        return $this->controllers;
    }

}