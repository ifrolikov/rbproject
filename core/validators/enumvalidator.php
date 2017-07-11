<?php

namespace core\validators;

use core\validators\abstractions\Validator;

class EnumValidator extends Validator
{
    static protected $instance;

    public function validate(string $field, array $params = []): bool
    {
        $in = (array)($params['in'] ?? []);
        return isset($this->model->$field) ? in_array($this->model->$field, $in) : false;
    }
}