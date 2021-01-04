<?php
require_once __DIR__ . '/../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../resources/templates');
$twig = new \Twig\Environment($loader);
$router = new \Bramus\Router\Router();

// General variables
$basePath = __DIR__ . '/../';

require_once $basePath . 'vendor/autoload.php';
require_once $basePath . 'src/Models/Events.php';
require_once $basePath . 'src/functions.php';

$router->get('/', 'AuthController@showEvents');
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/signup', 'AuthController@showSignup');
$router->post('/signup', 'AuthController@signup');
$router->get('/addEvent', 'AuthController@showAddEvent');
$router->post('/addEvent', 'AuthController@addEvent');
$router->get('/event/{event}/ticket/{id}', 'AuthController@Ticket');
$router->post('/event/{event}/ticket/{id}', 'AuthController@Ticket');
$router->get('/event/{event}', 'AuthController@showDetail');
$router->post('/event/{event}', 'AuthController@showDetail');


$router->run();








