<?php
require_once __DIR__ . '/../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../resources/templates');
$twig = new \Twig\Environment($loader);

// General variables
$basePath = __DIR__ . '/../';

$search = isset($_POST['search']) ? (string) $_POST['search'] : '';




echo $twig->render('pages/index.twig', [
    'search' => $search
]);


