<?php

namespace models;

use core\Data;

/**
 * Class Sensor
 * @package models
 */
class Sensor extends Data
{
    /** @var  string */
    public $name;
    /** @var  string */
    public $value;

    public function rules()
    {
        return [
            [['id', 'value'], 'numeric'],
            ['name', 'string']
        ];
    }
}