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


$search = isset($_GET['search']) ? (string)$_GET['search'] : '';

if (isset($_GET['search'])) {
    $event = searchEvents($_GET['search']);
} else {
    $event = getEventObjects();
}


echo $twig->render('pages/index.twig', [
    'search' => $search,
    'events' => $event
]);


