<?php
require_once __DIR__ . '/../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../resources/templates');
$twig = new \Twig\Environment($loader);

// General variables
$basePath = __DIR__ . '/../';
require_once $basePath . 'src/functions.php';
require_once $basePath . 'vendor/autoload.php';
$error = true;
$errorGebruikersnaam = '';
$errorEmail = '';
$errorWachtwoord = '';
$errorWachtwoord2 = '';

$gebruikersnaam = isset($_POST['gebruikersnaam']) ? (string) $_POST['gebruikersnaam'] : '';
$email = isset($_POST['email']) ? (string) $_POST['email'] : '';
$wachtwoord = isset($_POST['wachtwoord']) ? (string) $_POST['wachtwoord'] : '';
$wachtwoord2 = isset($_POST['wachtwoord2']) ? (string) $_POST['wachtwoord2'] : '';
$moduleAction = isset($_POST['moduleAction']) ? (string) $_POST['moduleAction'] : '';

if ($moduleAction == 'processName'){
    if ($gebruikersnaam == ''){
        $errorGebruikersnaam = 'Geef een gebruikersnaam in. ';
        $error = false;
    }
    if (checkUsername($gebruikersnaam)){
        $errorGebruikersnaam .= 'Gebruikersnaam bestaat al.';
        $error = false;
    }
    if ($email == ''){
        $errorEmail = 'Geef een email in. ';
        $error = false;
    }
    if (checkMail($email)){
        $errorEmail = 'Deze mail is al in gebruik.';
        $error = false;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errorEmail = 'Geef een correct email address in.';
        $error = false;
    }
    if ($wachtwoord == ''){
        $errorWachtwoord = 'Geef een wachtwoord in.';
        $error = false;
    }
    if ($wachtwoord2 != $wachtwoord){
        $errorWachtwoord2 = 'Wachtwoorden komen niet overeen.';
        $error = false;
    }
    if ($error){
       $password = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);
        createUser($gebruikersnaam, $email, $password);
        header('location: login.php');
    }
}



echo $twig->render('pages/signup.twig', [
    'gebruikersnaam' => $gebruikersnaam,
    'email' => $email,
    'wachtwoord' => $wachtwoord,
    'wachtwoord2' => $wachtwoord2,
    'errorGebruikersnaam' => $errorGebruikersnaam,
    'errorEmail' => $errorEmail,
    'errorWachtwoord' => $errorWachtwoord,
    'errorWachtwoord2' => $errorWachtwoord2
]);

