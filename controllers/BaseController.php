<?php
class BaseController
{
    // Envia a resposta JSON com o código HTTP correto
    protected function sendResponse($data, $statusCode = 200)
    {
        header("Content-Type: application/json; charset=UTF-8");
        http_response_code($statusCode);
        echo json_encode($data);
    }

    // Pega os dados JSON enviados no corpo (PUT/POST)
    protected function getJsonData()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}
