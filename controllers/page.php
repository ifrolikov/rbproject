<?php

namespace controllers;

use controllers\abstractions\AuthController;
use models\Commutator;
use models\Sensor;
use models\Settings;

class Page extends AuthController
{
    public $view = 'list';

    public function actionSettings()
    {
        return ['items' => Settings::find()];
    }

    public function actionCommutator()
    {
        return ['items' => Commutator::find()];
    }

    public function actionSensor()
    {
        return ['items' => Sensor::find()];
    }
}