<?php

require __DIR__ . '/db.php';
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/services/page_router.php';
require __DIR__ . '/services/crud_router.php';
require __DIR__ . '/services/crud_query_executor.php';
require __DIR__ . '/forms/add.php';
require __DIR__ . '/forms/delete.php';

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
        $crudQueryExecutor = new CrudQueryExecutor($databaseConnector, 'Inventory');
        $pageRouter = new PageRouter();

        $inventoryItemsXml = new SimpleXMLElement('<Xml/>');

        $inventoryItemsXml->addAttribute('mainAlias', 'i');
        $inventoryItemsXml->addAttribute('columns', 'i.Id, i.Name, i.Quantity, i.Price, c.Name AS CategoryName');
        $inventoryItemsXml->addAttribute('orderBy', 'i.Price DESC');

        $joins = $inventoryItemsXml->addChild('Joins');

        $join = $joins->addChild('Join');
        $join->addAttribute('table', 'ItemCategories');
        $join->addAttribute('alias', 'c');
        $join->addAttribute('type', 'LEFT');
        $join->addAttribute('on', 'i.CategoryId = c.Id');

        $inventoryItems = $crudQueryExecutor->read($inventoryItemsXml);

        $data = ['title' => 'inventory', 'inventoryItems' => $inventoryItems];
        $pageRouter->route('home.php', $data); 

        break;

    case 'add':
        $crudQueryExecutor = new CrudQueryExecutor($databaseConnector, 'ItemCategories');
        $pageRouter = new PageRouter();

        $itemCategoriesXml = new SimpleXMLElement('<Xml/>');
        $itemCategoriesXml->addAttribute('mainAlias', 'c');

        $itemCategories = $crudQueryExecutor->read($itemCategoriesXml);

        $data = ['title' => 'Add Inventory Item', 'itemCategories' => $itemCategories];
        $pageRouter->route('add.php', $data); 

        break;

    case 'api/inventory':
        $crudQueryExecutor = new CrudQueryExecutor($databaseConnector, 'Inventory');
        $insertForm = new AddInventoryForm($crudQueryExecutor);
        $deleteForm = new DeleteInventoryForm($crudQueryExecutor);
        $crudRouter = new CrudRouter($crudQueryExecutor, $insertForm, null, $deleteForm);
        $crudRouter->route();

        break;

    default:
        http_response_code(404);
        echo "404 Not Found";
}

?>
