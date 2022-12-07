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
                echo "<pre>";
                var_dump($matches);
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
}
