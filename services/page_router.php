<?php

class PageRouter {
    function route($page, $data) {
        switch($_SERVER['REQUEST_METHOD']) {
            case "GET":
                extract($data);
                include __DIR__ . '/../pages/' . $page;
                break;

            default:
                http_response_code(405); // Method Not Allowed
                echo "Invalid request.";
        }
    }
}
