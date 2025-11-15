<?php

require_once __DIR__ . '/../config/Database.php';

$route = $_GET['route'] ?? '';
$parts = explode('/', $route);
$resource = $parts[0] ?? null;
$id = $parts[1] ?? null;

$method = $_SERVER['REQUEST_METHOD'];

switch ($resource) {
    case 'contato':
        require_once __DIR__ . '/../controllers/ContatoController.php';
        $controller = new ContatoController();
        $controller->handleRequest($method, $id);
        break;

    case 'telefone':
        require_once __DIR__ . '/../controllers/TelefoneController.php';
        $controller = new TelefoneController();
        $controller->handleRequest($method, $id);
        break;

    case '':
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
        echo json_encode(['message' => 'API da Agenda iLeva está online.']);
        break;

    default:
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(404);
        echo json_encode(['message' => 'Endpoint não encontrado.']);
        break;
}
