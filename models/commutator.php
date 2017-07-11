<?php

namespace models;

use core\Data;

/**
 * Class Commutator
 * @package models
 */
class Commutator extends Data
{
    /** @var  string */
    public $name;
    /** @var  string */
    public $value;

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['name', 'string'],
            ['value', 'enum', 'in' => ['on', 'off']]
        ]);
    }
}