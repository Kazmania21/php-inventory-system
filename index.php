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
        $inventoryItemsJson = json_encode([
            "mainAlias" => "i",
            "columns" => "i.Id, i.Name, i.Quantity, i.Price, c.Name AS CategoryName",
            "orderBy" => "i.Price DESC",
            "joins" => [
                [
                    "table" => "ItemCategories",
                    "alias" => "c",
                    "type" => "LEFT",
                    "on" => "i.CategoryId = c.Id"
                ]
            ]
        ]);
        $inventoryItemsQuery = "EXEC usp_SelectDynamicJson @TableName=:table, @Json=:json";
        $inventoryItems = $databaseConnector->executeQuery(
            $inventoryItemsQuery,
            [
                'table' => 'Inventory',
                'json' => $inventoryItemsJson
            ]
        );
        extract(['title' => 'Inventory', 'inventoryItems' => $inventoryItems]);
        include __DIR__ . '/pages/home.php';
        break;

    case 'add':
        $itemCategoriesQuery = "EXEC usp_SelectDynamicJson @TableName=:table, @Json=:json";
        $itemCategories = $databaseConnector->executeQuery(
            $itemCategoriesQuery,
            [
                'table' => 'ItemCategories',
                'json' => json_encode([])
            ]
        );
        extract(['title' => 'Add Inventory Item', 'itemCategories' => $itemCategories]);
        include __DIR__ . '/pages/add.php';
        break;

    default:
        http_response_code(404);
        echo "404 Not Found";
}

?>
