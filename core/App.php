<?php

namespace Projeto\Core;

/**
 * Class App
 * @package Projeto\Core
 *
 * @method App get($string, $param)
 */
class App
{
    /**
     * @var array
     */
    private $routes = [];

    /**
     * @return string
     */
    public function method()
    {
        return isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'cli';
    }

    /**
     * @return string
     */
    public function uri()
    {
        $self = isset($_SERVER['PHP_SELF']) ? str_replace('index.php/', '', $_SERVER['PHP_SELF']) : '';
        $uri = isset($_SERVER['REQUEST_URI']) ? explode('?', $_SERVER['REQUEST_URI'])[0] : '';

        if ($self !== $uri) {
            $peaces = explode('/', $self);
            array_pop($peaces);
            $start = implode('/', $peaces);
            $search = '/' . preg_quote($start, '/') . '/';
            $uri = preg_replace($search, '', $uri, 1);
        }

        return $uri;
    }

    /**
     * is triggered when invoking inaccessible methods in an object context.
     *
     * @param $name string
     * @param $arguments array
     * @return mixed
     * @link http://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.methods
     */
    function __call($name, $arguments)
    {
        $name = strtolower($name);
        if (!isset($this->routes[$name])) {
            $this->routes[$name] = [];
        }
        $uri = substr($arguments[0], 0, 1) !== '/' ? '/' . $arguments[0] : $arguments[0];
        $peaces = explode('/', $uri);
        foreach ($peaces as $key => $value) {
            $peaces[$key] = str_replace('*', '(.*)', $peaces[$key]);
            if (strpos($value, ':') === 0) {
                $peaces[$key] = '(\w+)';
            }
        }
        $pattern = str_replace('/', '\/', implode('/', $peaces));
        $route = '/^' . $pattern . '$/';
        $this->routes[$name][$route] = isset($arguments[1]) ? $arguments[1] : null;

        return $this;
    }

    /**
     * The __invoke method is called when a script tries to call an object as a function.
     *
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.invoke
     *
     * @param $method
     * @param $uri
     * @return mixed
     */
    function __invoke($method, $uri)
    {
        $method = strtolower($method);
        if (!isset($this->routes[$method])) {
            return null;
        }

        foreach ($this->routes[$method] as $regex => $callback) {

            if (preg_match($regex, $uri, $params)) {
                array_shift($params);
                return call_user_func_array($callback, $params);
            }
        }
        return null;
    }

}