<?php

namespace Middleware;

use Firebase\JWT\JWT;
use Http\Router;
use Model\User;

/**
 * Class Auth
 * @package Middleware
 */
class Auth
{

    /**
     * @param $email
     * @return bool
     */
    public static function validateEmail($email): bool
    {
        $user = new User();

        if ($user->checkEmail($email))
        {
            return true;
        }

        return false;
    }

    /**
     * @param $email
     * @param $password
     * @return bool
     */
    public static function validateUser($email, $password)
    {
        $user = new User();

        if (password_verify($password, $user->checkUser($email)->password))
        {
            return true;
        }

        return false;
    }

    /**
     * @param $email
     * @return bool
     */
    public static function validateStatus($email)
    {
        $user = new User();

        if ($user->checkUser($email)->active == 1)
        {
            return true;
        }

        return false;
    }

    /**
     * @param $email
     * @param $token
     * @return bool
     */
    public static function validateRemember($email, $token)
    {
        $user = new User();

        $pass = $user->checkRemember($email)->name . $user->checkRemember($email)->email;

        if ((md5($pass) === $token))
        {
            return true;
        }

        return false;
    }

    /**
     * @return int
     */
    public static function validateToken(): int
    {
        $user = new User();
        try
        {
            $authHeader = explode(" ", Router::getAuthorization());
            $token = $authHeader[1];
            JWT::$leeway = 10;
            $decoded = JWT::decode($token, env('SECRET_KEY'), [env('ALGORITHM')]);
            if ($user->checkUser($decoded->data->email)->token_expire == 2)
            {
                return 0;
            }
            return $decoded->data->id;
        } catch (\Exception $exception)
        {
            return 0;
        }

    }

    /**
     * @param User $user
     * @return String
     */
    public static function generateToken(User $user): string
    {
        $iat = time();
        $nbf = $iat + 10;
        $exp = $iat + 60;

        $token = [
            "iat" => $iat,
            "nbf" => $nbf,
            "exp" => $exp,
            "data" => [
                "id" => $user->getId(),
                "email" => $user->getEmail()
            ]
        ];

        return JWT::encode($token, env('SECRET_KEY'));

    }


}