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

$desc = isset($_POST['desc']) ? $_POST['desc'] : '';
$error = true;
$errorNaam = '';
$errorLocatie = '';
$errorPrijs = '';
$errorDatum1 = '';
$errorDatum2 = '';
$errorDesc = '';

if (isset($_POST['bttnsubmit'])) {
    if (!$_POST['evenementnaam']) {
        $error = false;
        $errorNaam = 'Vul de naam van uw event in. ';
    }
    if (strlen($_POST['evenementnaam']) > 45) {
        $error = false;
        $errorNaam .= 'De naam van uw event is te lang. ';
    }
    if (chechEventName($_POST['evenementnaam'])) {
        $error = false;
        $errorNaam .= 'Dit event bestaal al.';
    }
    if (!$_POST['locatie']) {
        $error = false;
        $errorLocatie = 'Vul een locatie in. ';
    }
    if (strlen($_POST['locatie']) > 45) {
        $error = false;
        $errorLocatie .= 'De locatie naam is te lang.';
    }
    if (!$_POST['ticketprijs']) {
        $error = false;
        $errorPrijs = 'Gelieve een prijs in te vullen. ';
    }
    if ($_POST['ticketprijs'] < 0) {
        $error = false;
        $errorPrijs .= 'De prijs kan niet negatief zijn.';
    }
    if (!$_POST['datum1']) {
        $error = false;
        $errorDatum1 = 'Gelieve een datum in te geven. ';
    }
    if (isset($_POST['datum1'])) {
        if (checkDatum($_POST['datum1']) == false) {
            $error = false;
            $errorDatum1 .= 'Gelieve de datum juist in te geven d-m-j';
        }
    }

    if (!$_POST['datum2']) {
        $error = false;
        $errorDatum2 = 'Gelieve een datum in te geven. ';
    }
    if (isset($_POST['datum2'])) {
        if (checkDatum($_POST['datum2']) == false) {
            $error = false;
            $errorDatum2 .= 'Gelieve de datum juist in te geven d-m-j';
        }
    }

    if (!$_POST['desc']) {
        $error = false;
        $errorDesc = 'Gelieve jouw evenement te beschrijven. ';
    }
    if (strlen($_POST['desc']) > 200) {
        $error = false;
        $errorDesc .= 'De beschrijving is te lang.';
    }
    if ($error) {
        makeEvent($_POST['evenementnaam'], $_POST['locatie'], $_POST['ticketprijs'], $_POST['datum1'], $_POST['datum2'], $_POST['desc']);
        header('location: index.php');
    }
}


echo $twig->render('pages/add-event.twig', [
    'eventname' => $_POST['evenementnaam'] ?? '',
    'locatie' => $_POST['locatie'] ?? '',
    'ticketprijs' => $_POST['ticketprijs'] ?? '',
    'datum1' => $_POST['datum1'] ?? '',
    'datum2' => $_POST['datum2'] ?? '',
    'desc' => $desc,
    'errorName' => $errorNaam,
    'errorLocatie' => $errorLocatie,
    'errorPrijs' => $errorPrijs,
    'errorDatum1' => $errorDatum1,
    'errorDatum2' => $errorDatum2,
    'errorDesc' => $errorDesc
]);