<?php

class CrudQueryExecutor {
    public function __construct($databaseConnector, $table) {
        $this->databaseConnector = $databaseConnector;
        $this->table = $table;
    }

    public function create($Json) {
        $query = "EXEC usp_Insert @TableName=:table, @Json=:json";
        $this->databaseConnector->executeQuery(
            $query,
            [
                'table' => $this->table,
                'json' => $Json
            ]
        );
    }
    
    public function read($Xml) {
        $query = "EXEC usp_SelectDynamicXml @TableName=:table, @Xml=:xml";
        $data = $this->databaseConnector->executeQuery(
            $query,
            [
                'table' => $this->table,
                'xml' => $Xml->asXML()
            ]
        );
        return new SimpleXMLElement($data);
    }
    
    public function update() {
        return;
    }
    
    public function delete($Json) {
        $query = "EXEC usp_Delete @TableName=:table, @Json=:json";
        $this->databaseConnector->executeQuery(
            $query,
            [
                'table' => 'Inventory',
                'json' => $Json
            ]
        );
    }
}

?>
