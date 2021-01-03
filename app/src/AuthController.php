<?php


class AuthController
{
    protected \Twig\Environment $twig;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../resources/templates');
        $this->twig = new \Twig\Environment($loader);
    }

    public function showEvents()
    {
        require_once 'index.php';
        echo $this->twig->render('/pages/index.twig', [
            'search' => $search,
            'events' => $event
        ]);
        exit();
    }

    public function events()
    {
        require_once 'index.php';
        echo $this->twig->render('/pages/index.twig', [
            'search' => $search,
            'events' => $event
        ]);
        exit();
    }

    public function showLogin(){
        require_once 'login.php';
        echo $this->twig->render('/pages/index.twig', [
            'gebruikersnaam' => $gebruikersnaam,
            'wachtwoord' => $wachtwoord,
            'errorGebruikersnaam' => $errorGebruikersnaam,
            'errorWachtwoord' => $errorWachtwoord
        ]);
        exit();
    }

    public function login(){
        require_once 'login.php';
        echo $this->twig->render('/pages/index.twig', [
            'gebruikersnaam' => $gebruikersnaam,
            'wachtwoord' => $wachtwoord,
            'errorGebruikersnaam' => $errorGebruikersnaam,
            'errorWachtwoord' => $errorWachtwoord
        ]);
        exit();
    }
}