<?php
require_once 'code/core/Router.php';
require_once 'code/controllers/HomeController.php';
require_once 'code/controllers/AuthController.php';
require_once 'code/controllers/SearchController.php';
require_once 'code/controllers/ProfileController.php';

$router = new Router();

// Définir les routes
$router->addRoute('/ECF_mvc/code/public/index.php?home', new HomeController());
$router->addRoute('/ECF_mvc/code/public/index.php?login', new AuthController('login'));
$router->addRoute('/ECF_mvc/code/public/index.php?register', new AuthController('register'));
$router->addRoute('/ECF_mvc/code/public/index.php?search', new SearchController());
$router->addRoute('/ECF_mvc/code/public/index.php?profile', new ProfileController());
$router->addRoute('/ECF_mvc/code/public/index.php?book', new SearchController());

// Gérer la requête
$uri = $_SERVER['REQUEST_URI'];
$router->handleRequest($uri);