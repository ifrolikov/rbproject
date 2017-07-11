<?php

namespace controllers;

use core\App;
use core\Controller;

class User extends Controller
{
    /** @var  null|string */
    public $view = 'auth';

    /**
     * @return array
     */
    public function actionAuth()
    {
        if (\models\User::getAuth()) {
            App::redirect('/settings');
        }
        return [];
    }

    /**
     * @return array
     */
    public function actionLogin()
    {
        if (!$user = \models\User::findOne([
            'email' => App::post('email'),
            'password' => md5(App::post('password'))
        ])
        ) {
            return [
                'error' => 'failed'
            ];
        }

        \models\User::auth($user);
        App::redirect('/settings');
        return [];
    }

    public function actionLogout()
    {
        \models\User::logout();
        App::redirect('/');
    }
}