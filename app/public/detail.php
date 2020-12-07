<?php

require_once __DIR__ . '/../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../resources/templates');
$twig = new \Twig\Environment($loader);

// General variables
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
$stmt->execute([$_GET['event']]);
$collections = $stmt->fetchAllAssociative();

//query for daytickets
$stmt2 = $connection->prepare('SELECT * FROM tickets WHERE events_eventID = ? AND soort = "dagticket"');
$stmt2->execute([$_GET['event']]);
$collections2 = $stmt2->fetchAllAssociative();

//query for combitickets
$stmt3 = $connection->prepare('SELECT * FROM tickets WHERE events_eventID = ? AND soort = "combiticket"');
$stmt3->execute([$_GET['event']]);
$collections3 = $stmt3->fetchAllAssociative();

//query for camping
$stmt4 = $connection->prepare('SELECT * FROM tickets WHERE events_eventID = ? AND soort = "camping"');
$stmt4->execute([$_GET['event']]);
$collections4 = $stmt4->fetchAllAssociative();


echo $twig->render('pages/detail.twig', [
    'info' => $collections,
    'dagticket' => $collections2,
    'combitickets' => $collections3,
    'campingtickets' => $collections4
]);