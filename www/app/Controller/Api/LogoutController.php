<?php


namespace Controller\Api;


use Middleware\Auth;
use Model\User;

class LogoutController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function logout()
    {

        if($this->method !== 'GET')
        {
            return $this->sendError('Method '.$this->method.' is not supported');
        }

        $userId = Auth::validateToken();

        if (!$userId)
        {
            return $this->sendError('You must login first');
        }

        $user = new User();
        $userData = $user->allDataUser($userId);
        $user->save($userData);

        $user->change($user->getEmail(), ['token_expire' => 2]);
        $success = [];


        return $this->sendResponse($success, 'User logout successfully');
    }

}