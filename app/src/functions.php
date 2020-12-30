<?php

require_once $basePath . 'config/database.php';
require_once $basePath . 'vendor/autoload.php';
require_once $basePath . 'src/Models/Events.php';


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
        switch ($sub2){
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