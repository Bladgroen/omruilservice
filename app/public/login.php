<?php
require_once __DIR__ . '/../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../resources/templates');
$twig = new \Twig\Environment($loader);

// General variables
$basePath = __DIR__ . '/../';

if ($_SERVER['QUERY_STRING'] == "signup") {
    header('Location: /signup.php');
}


echo $twig->render('pages/login.twig', [

]);