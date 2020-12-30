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


$search = isset($_POST['search']) ? (string) $_POST['search'] : '';

$event = getEventObjects();

echo $twig->render('pages/index.twig', [
    'search' => $_POST['search'] ?? '',
    'events' => $event
]);


