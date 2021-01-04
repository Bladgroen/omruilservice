<?php
require_once __DIR__ . '/../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../resources/templates');
$twig = new \Twig\Environment($loader);

// General variables
$basePath = __DIR__ . '/../';

$errorGebruikersnaam= '';
$errorWachtwoord = '';

$gebruikersnaam = isset($_POST['gebruikersnaam']) ? (string) $_POST['gebruikersnaam'] : '';
$wachtwoord = isset($_POST['wachtwoord']) ? (string) $_POST['wachtwoord'] : '';
$moduleAction = isset($_POST['moduleAction']) ? $_POST['moduleAction'] : '';

if ($moduleAction == 'processName'){
    if ($gebruikersnaam == ''){
       $errorGebruikersnaam = 'Geef een gebruikersnaam in.';
    }
    if ($wachtwoord == ''){
        $errorWachtwoord = 'Geef een wachtwoord in.';
    }
}

