<?php

namespace core\validators;

use core\validators\abstractions\Validator;

class StringValidator extends Validator
{
    static protected $instance;

    public function validate(string $field, array $params = []): bool
    {
        return isset($this->model->$field) ? is_string($this->model->$field) : false;
    }
}