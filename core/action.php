<?php

namespace core;

class Action
{
    /** @var  string */
    public $layout = ROOT_PATH . 'views/layout/index.php';
    /** @var  string */
    public $view;
    /** @var  object */
    private $controller;
    /** @var  $method */
    private $method;
    /** @var  mixed[] */
    private $requestVariables;

    /** @var string[] */
    static public $availableRequestMethods = [
        'GET', 'POST', 'CONSOLE'
    ];

    public function __construct()
    {
        $this->findControllerMethod();
    }

    private function findControllerMethod()
    {
        global $argc;
        $request = $argc ? static::getConsoleRequest() : $_SERVER['REQUEST_URI'];
        $method = $argc ? 'CONSOLE' : $_SERVER['REQUEST_METHOD'];

        if (!in_array($method, static::$availableRequestMethods)) {
            throw new \Exception('Request method not available');
        }

        foreach (App::getActions() as $action => $controllerData) {
            if ($parsedAction = $this->parseAction($action)) {
                list($actionMethod, $actionRequest, $variables) = $parsedAction;
                if ($actionMethod == $method && preg_match('#^' . $actionRequest . '$#si', $request, $matches)) {
                    $requestVariables = [];
                    foreach ($matches as $key => $match) {
                        if ($key == 0) {
                            continue;
                        }
                        $requestVariables[$variables[$key-1]] = $matches[$key];
                    }
                    $this->requestVariables = $requestVariables;
                    list($this->controller, $this->method) = $this->parseControllerData($controllerData, $method);
                    break;
                }
            }
        }

        if (!$this->controller || !$this->method) {
            throw new \Exception('Not found', 404);
        }

        if (property_exists(get_class($this->controller), 'view') && $this->controller->view) {
            $controller = explode('\\', get_class($this->controller));
            $controllerName = strtolower(array_pop($controller));
            $this->view = ROOT_PATH . 'views/' . $controllerName . '/' . $this->controller->view . '.php';
        }

        if (property_exists(get_class($this->controller), 'layout') && $this->controller->layout) {
            $this->view = ROOT_PATH . 'views/layout/' . $this->controller->layout . '.php';
        }
    }

    public static function getConsoleRequest()
    {
        global $argv;

        unset($argv[0]);
        return '/' . implode('/', $argv);
    }

    /**
     * Return [method, request]
     * @param string $action
     * @return array|null
     */
    private function parseAction(string $action)
    {
        if (!preg_match('#^(' . implode('|', static::$availableRequestMethods) . ') #siU', $action, $match)) {
            return null;
        }
        $method = $match[1];
        $request = trim(substr($action, strlen($method) + 1));
        preg_match_all('#{(.+)}#siU', $request, $matches);
        $variables = $matches[1];
        $request = preg_replace('#{.+}#siU', '(.+)', $request);
        return [$method, $request ?: '/', $variables];
    }

    /**
     * Return [controller, method]
     * @param string $controllerData
     * @param string $requestMethod
     * @return array
     * @throws \Exception
     */
    private function parseControllerData(string $controllerData, string $requestMethod): array
    {
        list($controller, $method) = explode('.', $controllerData);
        if (!$controller || !$method) {
            throw new \Exception('Bad controller data');
        }

        $method = 'action' . ucfirst($method);
        $requestNamespace = App::getConfig()['controllers'][$requestMethod] ?? 'controllers';

        $controller = '\\' . $requestNamespace . '\\' . ucfirst($controller);
        $controller = new $controller;

        return [$controller, $method];
    }

    /**
     * Run action
     */
    public function exec()
    {
        $data = call_user_func_array(get_class($this->controller).'::'.$this->method, $this->requestVariables);
        if ($this->view) {
            if (!file_exists($this->view)) {
                throw new \Exception('View not found', 404);
            }
            ob_start();
            ob_implicit_flush(true);
            extract($data);
            require $this->view;
            $content = ob_get_clean();
            require $this->layout;
        } else {
            echo json_encode($data);
        }
    }
}