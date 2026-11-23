<?php

require __DIR__ . '/../config/bootstrap.php';

use App\Controllers\ResourceController;
use App\Core\Router;

$router = new Router();

// Liste
$router->get('/', [ResourceController::class, 'index']);
$router->get('/resources', [ResourceController::class, 'index']);

// Détail
$router->get('/resources/show', [ResourceController::class, 'show']);

// Ajout
$router->get('/resources/create', [ResourceController::class, 'create']);
$router->post('/resources/store', [ResourceController::class, 'store']);

// Modification
$router->get('/resources/edit', [ResourceController::class, 'edit']);
$router->post('/resources/update', [ResourceController::class, 'update']);

// Suppression
$router->post('/resources/delete', [ResourceController::class, 'delete']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);


