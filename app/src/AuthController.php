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
        $search = isset($_GET['search']) ? (string)$_GET['search'] : '';

        if (isset($_GET['search'])) {
            $event = searchEvents($_GET['search']);
        } else {
            $event = getEventObjects();
        }
        echo $this->twig->render('/pages/index.twig', [
            'events' => $event,
            'search' => $search
        ]);
    }


    public function showLogin()
    {
        echo $this->twig->render('/pages/login.twig');
    }

    public function login()
    {
        $errorGebruikersnaam = '';
        $errorWachtwoord = '';

        $gebruikersnaam = isset($_POST['gebruikersnaam']) ? (string)$_POST['gebruikersnaam'] : '';
        $wachtwoord = isset($_POST['wachtwoord']) ? (string)$_POST['wachtwoord'] : '';
        $moduleAction = isset($_POST['moduleAction']) ? $_POST['moduleAction'] : '';

        if ($moduleAction == 'processName') {
            if ($gebruikersnaam == '') {
                $errorGebruikersnaam = 'Geef een gebruikersnaam in.';
            }
            if ($wachtwoord == '') {
                $errorWachtwoord = 'Geef een wachtwoord in.';
            }
        }

        echo $this->twig->render('/pages/login.twig', ['gebruikersnaam' => $gebruikersnaam, 'wachtwoord' => $wachtwoord]);

    }

    public function showSignup()
    {
        echo $this->twig->render('/pages/signup.twig');
    }

    public function signup()
    {
        $basePath = __DIR__ . '/../';
        require_once $basePath . 'src/functions.php';
        require_once $basePath . 'vendor/autoload.php';
        $error = true;
        $errorGebruikersnaam = '';
        $errorEmail = '';
        $errorWachtwoord = '';
        $errorWachtwoord2 = '';

        $gebruikersnaam = isset($_POST['gebruikersnaam']) ? (string)$_POST['gebruikersnaam'] : '';
        $email = isset($_POST['email']) ? (string)$_POST['email'] : '';
        $wachtwoord = isset($_POST['wachtwoord']) ? (string)$_POST['wachtwoord'] : '';
        $wachtwoord2 = isset($_POST['wachtwoord2']) ? (string)$_POST['wachtwoord2'] : '';
        $moduleAction = isset($_POST['moduleAction']) ? (string)$_POST['moduleAction'] : '';

        if ($moduleAction == 'processName') {
            if ($gebruikersnaam == '') {
                $errorGebruikersnaam = 'Geef een gebruikersnaam in. ';
                $error = false;
            }
            if (checkUsername($gebruikersnaam)) {
                $errorGebruikersnaam .= 'Gebruikersnaam bestaat al.';
                $error = false;
            }
            if ($email == '') {
                $errorEmail = 'Geef een email in. ';
                $error = false;
            }
            if (checkMail($email)) {
                $errorEmail = 'Deze mail is al in gebruik.';
                $error = false;
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errorEmail = 'Geef een correct email address in.';
                $error = false;
            }
            if ($wachtwoord == '') {
                $errorWachtwoord = 'Geef een wachtwoord in.';
                $error = false;
            }
            if ($wachtwoord2 != $wachtwoord) {
                $errorWachtwoord2 = 'Wachtwoorden komen niet overeen.';
                $error = false;
            }
            if ($error) {
                $password = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);
                createUser($gebruikersnaam, $email, $password);
                header('location: login.php');
            }
        }
        echo $this->twig->render('pages/signup.twig', [
            'gebruikersnaam' => $gebruikersnaam,
            'email' => $email,
            'wachtwoord' => $wachtwoord,
            'wachtwoord2' => $wachtwoord2,
            'errorGebruikersnaam' => $errorGebruikersnaam,
            'errorEmail' => $errorEmail,
            'errorWachtwoord' => $errorWachtwoord,
            'errorWachtwoord2' => $errorWachtwoord2
        ]);
    }

    public function showDetail($event)
    {
        $basePath = __DIR__ . '/../';
        require_once $basePath . 'config/database.php';
        require_once $basePath . 'vendor/autoload.php';


        $connectionParams = [
            'host' => DB_HOST,
            'dbname' => DB_NAME_FF,
            'user' => DB_USER,
            'password' => DB_PASS,
            'driver' => 'pdo_mysql',
            'charset' => 'utf8mb4'
        ];

        $connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);
        $result = $connection->connect();

//query for event
        $stmt = $connection->prepare('SELECT * FROM events WHERE eventID = ?');
        $stmt->execute([$event]);
        $collections = $stmt->fetchAllAssociative();

//query for daytickets
        $stmt2 = $connection->prepare('SELECT * FROM tickets WHERE events_eventID = ? AND soort = "dagticket"');
        $stmt2->execute([$event]);
        $collections2 = $stmt2->fetchAllAssociative();

//query for combitickets
        $stmt3 = $connection->prepare('SELECT * FROM tickets WHERE events_eventID = ? AND soort = "combiticket"');
        $stmt3->execute([$event]);
        $collections3 = $stmt3->fetchAllAssociative();

//query for camping
        $stmt4 = $connection->prepare('SELECT * FROM tickets WHERE events_eventID = ? AND soort = "camping"');
        $stmt4->execute([$event]);
        $collections4 = $stmt4->fetchAllAssociative();


        echo $this->twig->render('pages/detail.twig', [
            'info' => $collections,
            'dagticket' => $collections2,
            'combitickets' => $collections3,
            'campingtickets' => $collections4
        ]);
    }

    public function showAddEvent(){
       echo $this->twig->render('pages/add-event.twig');
    }

    public function addEvent(){
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
            if (checkEventName($_POST['evenementnaam'])) {
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
        echo $this->twig->render('pages/add-event.twig', [
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
    }



    public function ticket($event, $id){
        $basePath = __DIR__ . '/../';

        require_once $basePath . 'vendor/autoload.php';
        require_once $basePath . 'src/Models/Events.php';
        require_once $basePath . 'src/functions.php';
        require_once $basePath . 'src/Models/Tickets.php';
        $ticket2 = getTicket($id);
        $user = getUserFromTicket($id);



        echo $this->twig->render('pages/detailTicket.twig', [
            'ticket' => $ticket2,
            'user' => $user
        ]);
    }


}



