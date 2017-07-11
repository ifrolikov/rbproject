<?php

namespace core;

abstract class Controller {
    public function __construct()
    {
        $this->beforeAction();
    }

    public function beforeAction()
    {
    }
}