<?php


namespace Controller\Api;


class Controller
{
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