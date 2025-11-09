<?php

class AddInventoryForm {
    public function __construct($queryExecutor) {
        $this->queryExecutor = $queryExecutor;
    }

    public function validate() {
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
                [$name, $quantity, $price, $categoryId]
            ]
        ]);

        $this->queryExecutor->create($insertJson);

        header("Location: /");
        exit;
    }
  }
?>
