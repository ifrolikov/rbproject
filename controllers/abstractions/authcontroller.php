<?php

namespace controllers\abstractions;

use core\App;
use core\Controller;
use models\User;

abstract class AuthController extends Controller
{
    public function beforeAction()
    {
        if (!User::getAuth()) {
            App::redirect('/', 302);
        }
    }
}