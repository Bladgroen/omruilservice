<?php
require_once __DIR__ . '/../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../resources/templates');
$twig = new \Twig\Environment($loader);

// General variables
$basePath = __DIR__ . '/../';
$boolean = '';


if ($_SERVER['QUERY_STRING'] == "login") {
    header('Location: /login.php');
}


echo $twig->render('pages/index.twig', [

]);