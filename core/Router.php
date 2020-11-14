<?php

namespace Core;

class Router
{
    /**
     * @var array
     */
    protected array $routes = [];

    protected array $params = [];

    public function add(string $route, array $params = []) : void
    {
        // escape forward slashes
        $route  = preg_replace('/\//', '\\/', $route);
        // {controller}
        $route  = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z]+)', $route);
        // {id:}
        $route  = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        $route = '/^' . $route . '$/i';
        $this->routes[$route] = $params;
    }

    public function match(string $url){
        foreach ($this->routes as $route => $params){
            if(preg_match($route, $url, $matches)){
                foreach ($matches as $key => $match){
                    if(is_string($key)){
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    public function dispatch(string $url){

        $url = $this->removeQueryVariables($url);

        if($this->match($url)){
            $controller = $this->params['controller'];
            $controller = $this->toStudlyCaps($controller);
            $controller = "App\Controller\\$controller";

            if(class_exists($controller)){
                $controllerObject = new $controller();

                $action = $this->params['action'];
                $action = $this->toCamelCase($action);

                if(is_callable([$controllerObject, $action])){
                    $controllerObject->$action();
                }else{
                    echo "Method $action (in controller $controller not found";
                }
            }else{
                echo "Controller class $controller not found";
            }

        }else{
            echo "Route not found";
        }
    }


    /**
     * @return array
     */
    public function getRoutes() : array
    {
        return $this->routes;
    }

    /**
     * @return array
     */
    public function getParams() : array
    {
        return $this->params;
    }

    private function toStudlyCaps($string)
    {
        return str_replace(" ", '', ucwords(str_replace('-', ' ', $string)));
    }

    private function toCamelCase($string)
    {
        return lcfirst($this->toStudlyCaps($string));
    }

    private  function removeQueryVariables($url){
        if($url != ''){
            $parts = explode('?', $url, 2);
            if(strpos($parts[0], '=') === false){
                $url = $parts[0];
            }else{
                $url = '';
            }
        }
        return $url;
    }

}