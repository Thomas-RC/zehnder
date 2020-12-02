<?php

namespace Controller\Api;

use Middleware\Auth;
use Model\User;

/**
 * Class UserController
 * @package Controller\Api
 */
class UserController extends BaseController
{

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     */
    public function user()
    {

        $userId = Auth::validateToken();
        if (!$userId)
        {
            return $this->sendError('You must login first');
        }

        $user = new User();
        $userData = $user->allDataUser($userId);
        $user->save($userData);

        $success = [];
        $success['user_id'] = $user->getId();
        $success['user_name'] = $user->getName();
        $success['email'] = $user->getEmail();
        $success['active'] = $user->getActive();

        return $this->sendResponse($success, 'User login successfully');

    }
}