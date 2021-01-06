<?php

require_once 'DatabaseConnector.php';

class AuthController
{
    protected \Twig\Environment $twig;
    protected \Doctrine\DBAL\Connection $db;


    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../resources/templates');
        $this->twig = new \Twig\Environment($loader);
        $this->db = DatabaseConnector::getConnection();

    }

    public function showEvents()
    {
        $search = isset($_GET['search']) ? (string)$_GET['search'] : '';
        $status = false;
        $username = '';
        if (isset($_GET['search'])) {
            $event = searchEvents($_GET['search']);
        } else {
            $event = getEventObjects();
        }
        if (isset($_SESSION['user'])) {
            $status = true;
            $username = $_SESSION['user'][0]['sellerName'];
        }

        echo $this->twig->render('/pages/index.twig', [
            'events' => $event,
            'search' => $search,
            'status' => $status,
            'name' => $username
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
        $userError = '';
        $moduleAction = isset($_POST['moduleAction']) ? $_POST['moduleAction'] : '';
        $status = true;
        if ($moduleAction == 'processName') {
            if ($gebruikersnaam == '') {
                $errorGebruikersnaam = 'Geef een gebruikersnaam in.';
                $status = false;
            }
            if ($wachtwoord == '') {
                $errorWachtwoord = 'Geef een wachtwoord in.';
                $status = false;
            }
            if ($status) {
                $stmt = $this->db->prepare('SELECT * FROM sellers WHERE sellerName = ?');
                $stmt->execute([$gebruikersnaam]);
                $user = $stmt->fetchAllAssociative();
                if (($user !== false) && (password_verify($wachtwoord, $user[0]['sellerPassword']))) {
                    $_SESSION['user'] = $user;
                    header('location: /');
                    exit;
                } else {
                    $userError = 'Invalid login credentials';
                    echo $this->twig->render('/pages/login.twig', ['gebruikersnaam' => $gebruikersnaam, 'wachtwoord' => $wachtwoord, 'error' => $userError]);
                }
            }


        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('location: /');
        exit;
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
                header('location: /login');
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
        $stmt = $this->db->prepare('SELECT * FROM events WHERE eventID = ?');
        $stmt->execute([$event]);
        $collections = $stmt->fetchAllAssociative();

//query for daytickets
        $stmt2 = $this->db->prepare('SELECT * FROM tickets WHERE events_eventID = ? AND soort = "dagticket"');
        $stmt2->execute([$event]);
        $collections2 = $stmt2->fetchAllAssociative();

//query for combitickets
        $stmt3 = $this->db->prepare('SELECT * FROM tickets WHERE events_eventID = ? AND soort = "combiticket"');
        $stmt3->execute([$event]);
        $collections3 = $stmt3->fetchAllAssociative();

//query for camping
        $stmt4 = $this->db->prepare('SELECT * FROM tickets WHERE events_eventID = ? AND soort = "camping"');
        $stmt4->execute([$event]);
        $collections4 = $stmt4->fetchAllAssociative();
        $status = false;
        $name = '';
        if (isset($_SESSION['user'])) {
            $status = true;
            $name = $_SESSION['user'][0]['sellerName'];
        }

        echo $this->twig->render('pages/detail.twig', [
            'info' => $collections,
            'dagticket' => $collections2,
            'combitickets' => $collections3,
            'campingtickets' => $collections4,
            'status' => $status,
            'name' => $name
        ]);
    }

    public function showAddEvent()
    {
        $status = false;
        $name = '';
        if (isset($_SESSION['user'])) {
            $status = true;
            $name = $_SESSION['user'][0]['sellerName'];
            echo $this->twig->render('pages/add-event.twig', [
                'status' => $status,
                'name' => $name
            ]);
        } else {
            header('location: /login');
        }
    }

    public function addEvent()
    {
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
                header('location: /');
            }
        }
        $status = false;
        $name = '';
        if (isset($_SESSION['user'])) {
            $status = true;
            $name = $_SESSION['user'][0]['sellerName'];
        }
        if (isset($_SESSION['user'])) {
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
                'errorDesc' => $errorDesc,
                'status' => $status,
                'name' => $name
            ]);
        } else {
            header('location: /login');
            exit;
        }
    }


    public function ticket($event, $id)
    {
        $basePath = __DIR__ . '/../';

        require_once $basePath . 'vendor/autoload.php';
        require_once $basePath . 'src/Models/Events.php';
        require_once $basePath . 'src/functions.php';
        require_once $basePath . 'src/Models/Tickets.php';
        $ticket2 = getTicket($id);
        $user = getUserFromTicket($id);

        $status = false;
        $name = '';
        if (isset($_SESSION['user'])) {
            $status = true;
            $name = $_SESSION['user'][0]['sellerName'];
            echo $this->twig->render('pages/detailTicket.twig', [
                'ticket' => $ticket2,
                'user' => $user,
                'status' => $status,
                'name' => $name
            ]);

        } else {
            header('location: /login');
        }
    }

    public function showAddTicket()
    {
        if (isset($_SESSION['user'])) {
            echo $this->twig->render('pages/add-tickets.twig');
        } else {
            header('location: /login');
        }
    }

    public function AddTicket($event)
    {
        $basePath = __DIR__ . '/../';

        require_once $basePath . 'vendor/autoload.php';
        require_once $basePath . 'src/Models/Events.php';
        require_once $basePath . 'src/functions.php';
        require_once $basePath . 'src/Models/Tickets.php';
        $ticketNaam = isset($_POST['ticketnaam']) ? (string)$_POST['ticketnaam'] : '';
        $ticketPrijs = isset($_POST['ticketprijs']) ? $_POST['ticketprijs'] : '';
        $soort = isset($_POST['soortticket']) ? $_POST['soortticket'] : '';
        $reden = isset($_POST['reden']) ? $_POST['reden'] : '';

        $error = true;
        $errorTicket = '';
        $errorPrijs = '';
        $errorReden = '';

        if (isset($_POST['bttnpress'])) {
            if (!$ticketNaam) {
                $error = false;
                $errorTicket = 'Gelieve een naam in te vullen. ';
            }
            if (strlen($ticketNaam) > 50) {
                $error = false;
                $errorTicket .= 'Naam is te lang.';
            }
            if (!$ticketPrijs) {
                $error = false;
                $errorPrijs = 'Gelieve een prijs in te vullen. ';
            }
            if ($ticketPrijs < 0) {
                $error = false;
                $errorPrijs .= 'Een prijs kan niet negatief zijn.';
            }

            if (!$reden) {
                $error = false;
                $errorReden = 'Gelieve een reden in te vullen. ';
            }
            if (strlen($reden) > 200) {
                $error = false;
                $errorReden .= 'Reden mag maar 200 karakters lang zijn.';
            }
            if ($error) {
                $stmt = $this->db->prepare('INSERT INTO tickets (ticketName, ticketPrice, reason, events_eventID, soort, sellers_sellerID) VALUES (?,?,?,?,?,?)');
                $stmt->execute([$ticketNaam, $ticketPrijs, $reden, $event, $soort, $_SESSION['user'][0]['sellerID']]);
                header('location: /');
            }
        }
        if (isset($_SESSION['user'])) {
            echo $this->twig->render('pages/add-tickets.twig', [
                'ticketNaam' => $ticketNaam,
                'ticketPrijs' => $ticketPrijs,
                'reden' => $reden,
                'errorTicket' => $errorTicket,
                'errorPrijs' => $errorPrijs,
                'errorReden' => $errorReden,
                'test' => $soort
            ]);

        } else {
            header('location: /login');
        }


    }

    public function sell( $id){
            $stmt = $this->db->prepare('DELETE FROM tickets WHERE ticketID = ?');
            $stmt->execute([$id]);
            header('location: /');
    }

}



