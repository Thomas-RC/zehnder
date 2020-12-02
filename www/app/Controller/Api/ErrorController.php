<?php


namespace Controller\Api;


use Middleware\Auth;
use Model\User;

class ErrorController extends BaseController
{

    /**
     * ErrorController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function error($url)
    {
        if($this->method == 'GET')
        {
            $url = explode('&', $url);

            if(Auth::validateRemember($url[0], $url[1]))
            {
                $user = new User();
                $user->change($url[0],['active' => 1, 'remember_token' => 1]);

                $success = [];
                return $this->sendResponse($success, 'The user has been activated, you can login now. ');
            }
        }

        return $this->sendError('Please validate error');
    }
}