<?php

namespace Controller\Api;

use Http\Validate;
use Message\Sender;
use Middleware\Auth;
use Model\User;


/**
 * Class RegisterController
 * @package Controller\Api
 */
class RegisterController extends BaseController
{

    /**
     * RegisterController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     */
    public function register()
    {
        if ($this->method !== 'POST')
        {
            return $this->sendError('Method ' . $this->method . ' is not supported');
        }

        $validator = Validate::check($this->request, [
            'name',
            'email',
            'password',
            'c_password'
        ]);

        if (!empty($validator))
        {
            return $this->sendError($validator['data']);
        }

        if (Auth::validateEmail($this->request['email']))
        {
            return $this->sendError('This email: ' . $this->request['email'] . ' is already taken');
        }

        if ($this->request['password'] !== $this->request['c_password'])
        {
            return $this->sendError('Password and confirm password do not match');
        }

        $this->request['password'] = password_hash($this->request['password'], PASSWORD_ARGON2ID);
        unset($this->request['c_password']);

        $confirmHash = md5($this->request['name'] . $this->request['email']);
        $confirmUrl = $this->request['email'] . '&' . $confirmHash;
        $this->request['remember_token'] = $confirmHash;

        $user = new User();
        $user->create($this->request);

        $message = "Click link to verify your account: https://api.ros-design.com/api/{$confirmUrl}";
        Sender::send($this->request['email'], 'Verify your Account - RosDESIGN', $message);

        $success = [];

        return $this->sendResponse($success, 'User register successfully');
    }

}