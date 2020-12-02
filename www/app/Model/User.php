<?php

namespace Model;

use Database\DBdriver;

/**
 * Class User
 * @package Model
 */
class User extends Model
{
    /**
     * @var string
     */
    private $_table = 'users';

    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $name;
    /**
     * @var
     */
    private $email;
    /**
     * @var
     */
    private $password;
    /**
     * @var
     */
    private $token;
    /**
     * @var
     */
    private $active;
    /**
     * @var
     */
    private $created;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->_conn = new DBdriver();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active): void
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created): void
    {
        $this->created = $created;
    }

    /**
     * @param $user
     */
    public function save($user)
    {
        $this->setId($user->id);
        $this->setName($user->name);
        $this->setEmail($user->email);
        $this->setToken($user->remember_token);
        $this->setActive($user->active);
    }

    /**
     * @param $request
     * @return array|bool|mixed
     */
    public function create($request)
    {
        return $this->_conn->insert($this->_table, $request);
    }

    /**
     * @param $email
     * @param $request
     * @return array|bool|mixed
     */
    public function change($email, $request)
    {
        $this->_conn->where('email = ?', [$email]);
        return $this->_conn->update($this->_table, $request);
    }

    /**
     * @param $id
     * @return array|bool|mixed
     */
    public function allDataUser($id)
    {
        $this->_conn->where('id = ?', [$id]);
        return $this->_conn->get($this->_table, '*', 'selectOne');
    }

    /**
     * @param $email
     * @return array|bool|mixed
     */
    public function checkUser($email)
    {
        $this->_conn->where('email = ?', [$email]);
        return $this->_conn->get($this->_table, '*', 'selectOne');
    }

    /**
     * @param $email
     * @return array|bool|mixed
     */
    public function checkEmail($email)
    {
        $this->_conn->where('email = ?', [$email]);
        return $this->_conn->get($this->_table, 'email', 'selectOne');
    }

    /**
     * @param $email
     * @return array|bool|mixed
     */
    public function checkRemember($email)
    {
        $this->_conn->where('email = ?', [$email]);
        return $this->_conn->get($this->_table, 'name, email, password', 'selectOne');
    }


}