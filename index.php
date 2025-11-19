<?php


require_once 'code/core/Router.php';
require_once 'code/controllers/HomeController.php';
require_once 'code/controllers/AuthController.php';
require_once 'code/controllers/SearchController.php';
require_once 'code/controllers/ProfileController.php';
require_once 'code/controllers/BookController.php';
require_once 'code/controllers/OkBookController.php';
require_once 'code/controllers/CancelBookController.php';
require_once 'code/controllers/AvisController.php';
require_once 'code/controllers/AvisUserController.php';
require_once 'code/controllers/CreditsController.php';

$router = new Router();

//  Penser à ajouter /studi-ecf-main en local
$router->addRoute('/', new HomeController());
$router->addRoute('/index.php?login', new AuthController('login'));
$router->addRoute('/index.php?register', new AuthController('register'));
$router->addRoute('/index.php?search', new SearchController());
$router->addRoute('/index.php?book', new BookController());
$router->addRoute('/index.php?profile', new ProfileController());
$router->addRoute('/index.php?avisUser', new AvisUserController());
$router->addRoute('/index.php?okBook', new OkBookController());
$router->addRoute('/index.php?cancelBook', new CancelBookController());
$router->addRoute('/index.php?avis', new AvisController());
$router->addRoute('/index.php?credits', new CreditsController());




$uri = $_SERVER['REQUEST_URI'];
$router->handleRequest($uri);
