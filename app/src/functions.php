<?php

require_once $basePath . 'config/database.php';
require_once $basePath . 'vendor/autoload.php';
require_once $basePath . 'src/Models/Events.php';
require_once $basePath . 'src/Models/Tickets.php';
require_once $basePath . 'src/Models/Users.php';


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

$stmt = $connection->prepare('SELECT * FROM events');
$stmt->execute();
$collections = $stmt->fetchAllAssociative();


function getEventObjects(): array
{
    global $collections;
    $events = [];
    for ($i = 0; $i <= count($collections) - 1; $i++) {
        $maand = '';
        $sub = substr($collections[$i]['startTime'], 0, 2);
        $sub2 = substr($collections[$i]['startTime'], 3, 2);
        switch ($sub2) {
            case '01':
                $maand = 'Jan';
                break;
            case '02':
                $maand = 'Feb';
                break;
            case '03':
                $maand = 'Mar';
                break;
            case '04':
                $maand = 'Apr';
                break;
            case '05':
                $maand = 'Mei';
                break;
            case '06':
                $maand = 'Jun';
                break;
            case '07':
                $maand = 'Jul';
                break;
            case '08':
                $maand = 'Aug';
                break;
            case '09':
                $maand = 'Sep';
                break;
            case '10':
                $maand = 'Okt';
                break;
            case '11':
                $maand = 'Nov';
                break;
            case '12':
                $maand = 'Dec';
                break;
        }

        $events[] = new Events(
            $collections[$i]['eventID'],
            $collections[$i]['eventName'],
            $collections[$i]['standaardPrijsTicket'],
            $collections[$i]['startTime'],
            $sub,
            $maand,
            $collections[$i]['endTime'],
            $collections[$i]['description'],
            $collections[$i]['locatie']
        );
    }
    return $events;
}

$stmt2 = $connection->prepare('SELECT * FROM tickets');
$stmt2->execute();
$collections2 = $stmt2->fetchAllAssociative();

function getTicketObjects(): array
{
    global $collections2;
    $tickets = [];
    for ($i = 0; $i <= count($collections2) - 1; $i++) {
        $tickets[] = new Tickets(
            $collections2[$i]['ticketID'],
            $collections2[$i]['ticketName'],
            $collections2[$i]['ticketPrice'],
            $collections2[$i]['reason'],
            $collections2[$i]['events_eventID'],
            $collections2[$i]['soort']
        );
    }
    return $tickets;
}

$stmt3 = $connection->prepare('SELECT * FROM sellers');
$stmt3->execute();
$collections3 = $stmt3->fetchAllAssociative();

function getUserObjects(): array
{
    global $collections3;
    $users = [];
    for ($i = 0; $i <= count($collections3) - 1; $i++) {
        $users[] = new Users(
            $collections3[$i]['sellerID'],
            $collections3[$i]['sellerName'],
            $collections3[$i]['sellerMail'],
            $collections3[$i]['sellerPassword']
        );
    }
    return $users;
}

function getTicket(int $id): array
{
    global $connection;
    $stmt = $connection->prepare('SELECT * FROM tickets WHERE ticketID = ?');
    $stmt->execute([$id]);
    $collections = $stmt->fetchAllAssociative();
    $ticket = new Tickets(
        $collections[0]['ticketID'],
        $collections[0]['ticketName'],
        $collections[0]['ticketPrice'],
        $collections[0]['reason'],
        $collections[0]['events_eventID'],
        $collections[0]['soort']
    );
    return $collections;
}

function getUserFromTicket(int $id): array{
    global $connection;
    $stmt = $connection->prepare('SELECT sellers_sellerID FROM tickets_has_sellers WHERE tickets_ticketID = ?');
    $stmt->execute([$id]);
    $collections = $stmt->fetchAllAssociative();
    $nummer = $collections[0];
    $stmt2 = $connection->prepare('SELECT sellerName, sellerMail FROM sellers WHERE sellerID = ?');
    $stmt2->execute([(int) $nummer]);
    $collections2 = $stmt2->fetchAllAssociative();
    return $collections2;
}

function searchEvents(string $term): array{
    global $connection;
    $events = [];
    $stmt = $connection->prepare("SELECT * FROM `events` WHERE `eventName` LIKE :needle");
    $needle = '%' . $term . '%';
    $stmt->bindValue(':needle', $needle, PDO::PARAM_STR);
    $stmt->execute();
    $collections = $stmt->fetchAllAssociative();
    for ($i = 0; $i <= count($collections) - 1; $i++){
        $maand = '';
        $sub = substr($collections[$i]['startTime'], 0, 2);
        $sub2 = substr($collections[$i]['startTime'], 3, 2);
        switch ($sub2) {
            case '01':
                $maand = 'Jan';
                break;
            case '02':
                $maand = 'Feb';
                break;
            case '03':
                $maand = 'Mar';
                break;
            case '04':
                $maand = 'Apr';
                break;
            case '05':
                $maand = 'Mei';
                break;
            case '06':
                $maand = 'Jun';
                break;
            case '07':
                $maand = 'Jul';
                break;
            case '08':
                $maand = 'Aug';
                break;
            case '09':
                $maand = 'Sep';
                break;
            case '10':
                $maand = 'Okt';
                break;
            case '11':
                $maand = 'Nov';
                break;
            case '12':
                $maand = 'Dec';
                break;
        }

        $events[] = new Events(
            $collections[$i]['eventID'],
            $collections[$i]['eventName'],
            $collections[$i]['standaardPrijsTicket'],
            $collections[$i]['startTime'],
            $sub,
            $maand,
            $collections[$i]['endTime'],
            $collections[$i]['description'],
            $collections[$i]['locatie']
        );
    }
    return $events;
}