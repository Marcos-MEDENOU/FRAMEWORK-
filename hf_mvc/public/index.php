<?php
// on appelle le fichier Router.php
require "../Core/Router.php";
// instanciatation du router
$router = new Router();
// echo "\$router est de la classe " . get_class($router) . "<br>";
// Ajout de quelques routes
$router->add("", ["controller" => "Home", "action" => "index"]);
$router->add("posts", ["controller" => "Posts", "action" => "index"]);
$router->add("{controller}/{action}");
// posts/index
// login/add-user
// posts/show
$router->add("admin/{controller}/{action}");
$router->add("{controller}/{id:\d+}/{action}");

// afficher la table des routes
echo "<pre>";
var_dump($router->getRoutes());
echo "</pre>";

if ($router->match($_SERVER['QUERY_STRING'])) {
    echo "<pre>";
    var_dump($router->getParams());
    echo "</pre>";
} else {
    echo "): Aucune route correspondant à {$_SERVER['QUERY_STRING']}";
}