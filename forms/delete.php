<?php

class DeleteInventoryForm {
    public function __construct($queryExecutor) {
        $this->queryExecutor = $queryExecutor;
    }

    public function validate() {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? null;
        if (!is_numeric($id)) die('ID must be a number');
        
        $deleteJson = json_encode([
            "where" => [
                [ "column"=> "t.Id", "op" => "=", "value" => $id ]
            ]
        ]);

        $this->queryExecutor->delete($deleteJson);
    }
}

?>
