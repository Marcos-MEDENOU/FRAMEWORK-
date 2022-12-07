<?php
// on appelle le fichier Router.php
require "../Core/Router.php";
require "../App/Controllers/Posts.php";
require "../App/Controllers/Home.php";
// instanciatation du router
$router = new Router();
$router->add("{controller}/{action}");
$router->add("admin/{controller}/{action}");
$router->add("{controller}/{id:\d+}/{action}");

$router->dispatch($_SERVER["QUERY_STRING"]);
