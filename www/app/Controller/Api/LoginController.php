<?php


namespace Controller\Api;


use Http\Validate;
use Middleware\Auth;
use Model\User;

class LoginController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login()
    {
        if($this->method !== 'POST')
        {
            return $this->sendError('Method '.$this->method.' is not supported');
        }

        $validator = Validate::check($this->request,[
            'email',
            'password'
        ]);

        if(!empty($validator))
        {
            return $this->sendError($validator['data']);
        }

        if(!Auth::validateUser($this->request['email'], $this->request['password']))
        {
            return $this->sendError('Invalid login credentials');
        }

        if(!Auth::validateStatus($this->request['email']))
        {
            return $this->sendError('Account is not activated. Please check your email address');
        }

        $user = new User();
        $userData = $user->checkUser($this->request['email']);
        $user->save($userData);

        $success = [];
        $success['user_id'] = $user->getId();
        $success['user_name'] = $user->getName();
        $success['token'] = 'Bearer '.Auth::generateToken($user);
        return $this->sendResponse($success, 'User login successfully');

    }

}