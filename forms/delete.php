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

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? null;
    if (!is_numeric($id)) die('ID must be a number');
    
    $deleteJson = json_encode([
        "where" => [
            [ "column"=> "t.Id", "op" => "=", "value" => $id ]
        ]
    ]);
    $deleteQuery = "EXEC usp_Delete @TableName=:table, @Json=:json";
    $databaseConnector->executeQuery(
        $deleteQuery,
        [
            'table' => 'Inventory',
            'json' => $deleteJson
        ]
    );

    header("Location: /");
    exit;
} else {
    http_response_code(405); // Method Not Allowed
    echo "Invalid request.";
}
?>
