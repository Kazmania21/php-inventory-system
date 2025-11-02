<?php

require __DIR__ . '/db.php';
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$databaseConnector = new DatabaseConnector(
    $_ENV['DB_SERVER'], 
    $_ENV['DB_NAME'], 
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);

$uri = trim($_SERVER['REQUEST_URI'], '/');

switch ($uri) {
    case '':
        extract(['title' => 'Inventory']);
        include __DIR__ . '/pages/home.php';
        break;

    default:
        http_response_code(404);
        echo "404 Not Found";
}

?>
