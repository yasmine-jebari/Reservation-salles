<?php
date_default_timezone_set('Africa/Casablanca');

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../bootstrap.php';

$routes = require __DIR__ . '/../routes.php';
$url = trim($_GET['url'] ?? '', '/');

if (!isset($routes[$url])) {
    http_response_code(404);
    echo 'Page introuvable';
    exit;
}

[$classe, $methode] = $routes[$url];
require_once __DIR__ . '/../src/Http/' . $classe . '.php';

$controleur = new $classe();
$controleur->$methode();