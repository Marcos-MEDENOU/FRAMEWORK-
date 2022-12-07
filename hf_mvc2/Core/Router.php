<?php

/**
 * Router
 * PHP version 8.1
 */
class Router
{
    /**
     * L'ensemble des routes de l'application (La table des routes | Routing Table)
     *
     * @var array
     */
    protected $routes = [];

    /**
     * L'ensemble des paramètres de la route actuelle
     * 
     * @var array
     */
    protected $params = [];

    /**
     * Permet d'ajouter une route à la table des routes
     *
     * @param string $url L'url à ajouter
     * @param array $params L'ensemble des paramètres de la route
     * 
     * @return void
     */
    public function add($url, $params = [])
    {
        // remplacer les / par des \/
        $route = preg_replace("~\/~", "\/", $url);
        // remplacer les string par des regex
        $route = preg_replace("/\{([a-z-]+)\}/", "(?'\\1'[a-z-]+)", $route);
        $route = preg_replace("/\{([a-z-]+):([^\}]+)\}/", "(?'\\1'\\2)", $route);
        // ajouter des délimiteurs
        $route = "/^" . $route . "\$/i";

        $this->routes[$route] = $params;
    }

    /**
     * Permet de matcher une route
     *
     * @param string $url URL à rechercher dans la table des routes 
     * @return boolean
     */
    public function match($url)
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }


    /**
     * Renvoi toutes les routes
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }
    /**
     * Renvoi tous les paramètres
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Exécute la méthode du controller correspondant à la route
     * @param $url l'URL de la route à dispatcher
     * @return void
     */
    function dispatch($url) {
        if ($this->match($url)) {
            $controller = $this->params["controller"];
            $controller = $this->convertToPascalCase($controller);
            // var_dump($controller);
            if (class_exists($controller)) {
                $controller_object = new $controller();

                $action = $this->convertToCamelCase($this->params["action"]);
                if (method_exists($controller_object, $action)) {
                    $controller_object->$action();
                } else {
                    echo "Méthode \"$action\" inexistante dans le controlleur \"$controller\"";
                }
            } else {
                echo "Class \"$controller\" inexistante";
            }

        } else {
            echo "Route inexistante pour la route \"$url\"";
        }
    }

    /**
     * Transform une chaine de caractère en PascalCase|StudlyCase
     * @var string $str La variable à transformer
     * @return string
     */
    function convertToPascalCase($str) {
        // return str_replace(" ", "", ucwords(str_replace("-", " ", $str)));
        // return preg_replace("/\s+/", "", ucwords(preg_replace("/-/", " ", $str)));
        return preg_replace("/-/","", ucwords($str, "-"));
    }

    /**
     * Transform une chaine de caractère en camelCase
     * @var string $str La variable à transformer
     * @return string
     */
    function convertToCamelCase($str) {
        return lcfirst($this->convertToPascalCase($str));
    }

    /**
     * Dispatch la route, en créant le controlleur et en exécutant la méthode de ce dernier
     *
     * @param string $url L'URL à dispatcher
     * @return void
     */

}
