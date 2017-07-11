<?php

namespace core;

/**
 * Class App
 * @package core
 */
class App
{
    /**
     * @var array
     */
    static private $config = [];

    /**
     * start application
     */
    static public function start()
    {
        if (static::$config) {
            return;
        }

        spl_autoload_register(function ($name) {
            $path = dirname(__DIR__ . '/../' . str_replace('\\', '/', $name));
            $name = explode('\\', $name);
            $fileName = strtolower(array_pop($name)) . '.php';
            include_once $path . '/' . $fileName;
        });


        static::$config = require_once __DIR__ . '/../config/app.php';

        try {
            $action = new Action();
            $action->exec();
        } catch (\Exception $e) {
            http_response_code($e->getCode());
            $e->getMessage() ? print($e->getMessage()) : var_dump($e->getTraceAsString());
        }
    }

    /**
     * @return array
     */
    static public function getConfig(): array
    {
        return static::$config;
    }

    /**
     * @return array
     */
    static public function getData(): array
    {
        return static::getConfig()['data'] ?? [];
    }

    /**
     * @return array
     */
    static public function getActions(): array
    {
        return static::getConfig()['actions'] ?? [];
    }

    /**
     * @param string $url
     * @param int $code
     */
    static public function redirect(string $url, $code = 302)
    {
        header('location: ' . $url, true, $code ?: 302);
        exit;
    }

    /**
     * @param null|string|integer $key
     * @param mixed $default
     * @return mixed
     */
    static public function post($key = null, $default = null)
    {
        if ($key) {
            return $_POST[$key] ?? $default;
        } else {
            return $_POST;
        }
    }

    /**
     * @param null|string|integer $key
     * @param mixed $default
     * @return mixed
     */
    static public function get($key = null, $default = null)
    {
        if ($key) {
            return $_GET[$key] ?? $default;
        } else {
            return $_GET;
        }
    }
}