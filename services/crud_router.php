<?php

class CrudRouter {
    function __construct($queryExecutor, $insertForm, $updateForm, $deleteForm) {
        $this->queryExecutor = $queryExecutor;
        $this->insertForm = $insertForm;
        $this->updateForm = $updateForm;
        $this->deleteForm = $deleteForm;
    }

    function route($Xml = new SimpleXMLElement('<Xml/>')) {
        switch($_SERVER['REQUEST_METHOD']) {
            case "GET":
                header("Content-Type: application/xml");

                $data = $this->queryExecutor->read($Xml)->asXML();

                echo $data;
                break;

            case "POST":
                $this->insertForm->validate();
                break;
            
            case "UPDATE":
                $this->updateForm->validate();
                break;
            
            case "DELETE":
                $this->deleteForm->validate();
                break;

            default:
                http_response_code(405); // Method Not Allowed
                echo "Invalid request.";
        }
    }
}
