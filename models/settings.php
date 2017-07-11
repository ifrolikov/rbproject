<?php

namespace models;

use core\Data;

/**
 * Class Settings
 * @package models
 */
class Settings extends Data
{
    /** @var  string */
    public $value;

    public function rules()
    {
        return [
            [['id', 'value'], 'string']
        ];
    }
}