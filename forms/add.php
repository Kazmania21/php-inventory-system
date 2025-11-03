<?php

require __DIR__ . '/../db.php';
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$databaseConnector = new DatabaseConnector(
    $_ENV['DB_SERVER'], 
    $_ENV['DB_NAME'], 
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['itemName']);
    $quantity = intval($_POST['quantity']);
    $price = floatval($_POST['price']);
    $categoryId = floatval($_POST['categoryId']);

    if (empty($name)) die("Name required");
    if ($quantity < 0) die("Invalid quantity");
    if ($price < 0) die("Invalid price");

    $insertJson = json_encode([
        "columns" => "Name, Quantity, Price, CategoryId",
        "rows" => [
            [$name, $quantity, $price, $CategoryId]
        ]
    ]);
    $insertQuery = "EXEC usp_Insert @TableName=:table, @Json=:json";
    $databaseConnector->executeQuery(
        $insertQuery,
        [
            'table' => 'Inventory',
            'json' => $insertJson
        ]
    );

    header("Location: /");
    exit;
} else {
    http_response_code(405); // Method Not Allowed
    echo "Invalid request.";
}

?>
