<?php

// Get the requested path
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// If file exists as a real file, let the server handle it (images, css, js)
if (file_exists(__DIR__ . $path) && !is_dir(__DIR__ . $path)) {
    return false;
}

// Otherwise, route all requests to index.php
require __DIR__ . '/index.php';

?>
