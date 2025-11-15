<?php
// Carrega a conexão
require_once __DIR__ . '/../config/Database.php';

// -----------------------------------------------------------------
// MUDANÇA AQUI: Lendo o parâmetro 'route' do .htaccess
// -----------------------------------------------------------------
// O .htaccess transforma "contato/1" em "index.php?route=contato/1"
$route = $_GET['route'] ?? '';
// -----------------------------------------------------------------

$parts = explode('/', $route);

$resource = $parts[0] ?? null; // 'contato' ou 'telefone'
$id = $parts[1] ?? null;       // '1' (ou nulo)

$method = $_SERVER['REQUEST_METHOD'];

// Roteamento
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

    // Se a rota for vazia (ex: .../public/)
    case '':
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(200);
        echo json_encode(['message' => 'API da Agenda iLeva está online.']);
        break;

    default:
        // Rota não encontrada
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code(404);
        echo json_encode(['message' => 'Endpoint não encontrado.']);
        break;
}
