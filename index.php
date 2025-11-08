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
        /*$inventoryItemsJson = json_encode([
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
        ]);*/
        
        $inventoryItemsXml = new SimpleXMLElement('<Xml/>');

        // Add attributes to root
        $inventoryItemsXml->addAttribute('mainAlias', 'i');
        $inventoryItemsXml->addAttribute('columns', 'i.Id, i.Name, i.Quantity, i.Price, c.Name AS CategoryName');
        $inventoryItemsXml->addAttribute('orderBy', 'i.Price DESC');

        // Create <Joins>
        $joins = $inventoryItemsXml->addChild('Joins');

        // Add a <Join /> element with attributes
        $join = $joins->addChild('Join');
        $join->addAttribute('table', 'ItemCategories');
        $join->addAttribute('alias', 'c');
        $join->addAttribute('type', 'LEFT');
        $join->addAttribute('on', 'i.CategoryId = c.Id');

        $inventoryItemsQuery = "EXEC usp_SelectDynamicXml @TableName=:table, @Xml=:xml";
        $inventoryItems = $databaseConnector->executeQuery(
            $inventoryItemsQuery,
            [
                'table' => 'Inventory',
                'xml' => $inventoryItemsXml->asXML()
            ]
        );
        $inventoryItems = new SimpleXMLElement($inventoryItems);
        extract(['title' => 'Inventory', 'inventoryItems' => $inventoryItems]);
        include __DIR__ . '/pages/home.php';
        break;

    case 'add':
        $itemCategoriesXml = new SimpleXMLElement('<Xml/>');
        $itemCategoriesXml->addAttribute('mainAlias', 'c');
        $itemCategoriesQuery = "EXEC usp_SelectDynamicXml @TableName=:table, @Xml=:xml";
        $itemCategories = $databaseConnector->executeQuery(
            $itemCategoriesQuery,
            [
                'table' => 'ItemCategories',
                'xml' => $itemCategoriesXml->asXML()
            ]
        );
        $itemCategories = new SimpleXMLElement($itemCategories);
        extract(['title' => 'Add Inventory Item', 'itemCategories' => $itemCategories]);
        include __DIR__ . '/pages/add.php';
        break;

    default:
        http_response_code(404);
        echo "404 Not Found";
}

?>
