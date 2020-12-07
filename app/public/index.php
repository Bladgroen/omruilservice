<?php
require_once __DIR__ . '/../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../resources/templates');
$twig = new \Twig\Environment($loader);
$router = new \Bramus\Router\Router();

// General variables
$basePath = __DIR__ . '/../';
require_once $basePath . 'config/database.php';
require_once $basePath . 'vendor/autoload.php';

$search = isset($_POST['search']) ? (string) $_POST['search'] : '';


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

//$router->get('/index', function () use ($twig){
//    global $search;
//    global $connection;
//    $stmt = $connection->prepare('SELECT * FROM events');
//    $stmt->execute();
//    $collections = $stmt->fetchAllAssociative();
//   echo $twig->render('pages/index.twig', [ 'search' => $search, 'events' => $collections]);
//});

echo $twig->render('pages/index.twig', [
    'search' => $search,
    'events' => $collections
]);


