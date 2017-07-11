<?php

namespace models;

use core\Data;

/**
 * Class User
 * @package models
 */
class User extends Data
{
    public $email;
    public $password;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['email', 'password'], 'string']
        ]);
    }

    /**
     * Try to get authorized user
     * @return null|static
     */
    static public function getAuth()
    {
        if (!isset($_COOKIE['auth']) || !$data = $_COOKIE['auth']) {
            return null;
        } else {
            $authInfo = ['password' => substr($data, -32), 'email' => substr($data, 0, strlen($data) - 32)];
            $user = self::findOne([
                'email' => $authInfo['email']
            ]);
            if (!$user) {
                return null;
            }
            if (md5($user->password) != $authInfo['password']) {
                return null;
            }
            return $user;
        }
    }

    /**
     * Authorize user
     * @param User $user
     */
    static public function auth(User $user)
    {
        $data = implode('', [$user->email, md5($user->password)]);
        $_COOKIE['auth'] = $data;
        setcookie('auth', $data, 0, '/');
    }

    static public function logout()
    {
        unset($_COOKIE['auth']);
        setcookie('auth', null, 0, '/');
    }
}