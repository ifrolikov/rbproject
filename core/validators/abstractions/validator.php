<?php

namespace core\validators\abstractions;

use core\Data;

abstract class Validator
{
    protected $model;
    static protected $instance;

    private function __construct()
    {
    }

    private function __wakeup()
    {

    }

    private function __clone()
    {

    }

    static public function getInstance(Data $data) {
        if (!static::$instance) {
            static::$instance = new static();
        }
        static::$instance->model = $data;
        return static::$instance;
    }

    abstract public function validate(string $field, array $params = []): bool;
}