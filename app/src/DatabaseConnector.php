<?php



class DatabaseConnector
{
    static function getConnection()
    {

        $connectionParams = [
            'host' => DB_HOST,
            'dbname' => DB_NAME_FF,
            'user' => DB_USER,
            'password' => DB_PASS,
            'driver' => 'pdo_mysql',
            'charset' => 'utf8mb4'
        ];

        $connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams);
        return $connection;
    }
}