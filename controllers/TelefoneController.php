<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Telefone.php';

class TelefoneController extends BaseController
{
    private $telefoneModel;

    public function __construct()
    {
        $this->telefoneModel = new Telefone();
    }

    public function handleRequest($method, $id)
    {
        switch ($method) {
            case 'GET':
                if ($id) {
                    $this->getById($id);
                } else {
                    $this->getAll();
                }
                break;
            case 'POST':
                $this->create();
                break;
            case 'PUT':
                $this->update($id);
                break;
            case 'DELETE':
                $this->delete($id);
                break;
            default:
                $this->sendResponse(['message' => 'Método não permitido'], 405);
                break;
        }
    }

    private function getAll()
    {
        $telefones = $this->telefoneModel->findAll();
        $this->sendResponse($telefones);
    }

    private function getById($id)
    {
        $telefone = $this->telefoneModel->findById($id);
        if (!$telefone) {
            $this->sendResponse(['message' => 'Telefone não encontrado'], 404);
            return;
        }
        $this->sendResponse($telefone);
    }

    private function create()
    {
        $data = $this->getJsonData();
        if (empty($data['id_contato']) || empty($data['numero'])) {
            $this->sendResponse(['message' => 'id_contato e numero são obrigatórios.'], 400);
            return;
        }
        $newId = $this->telefoneModel->create($data);
        $this->sendResponse(['id' => $newId, 'message' => 'Telefone adicionado.'], 201);
    }

    private function update($id)
    {
        if (!$id) {
            $this->sendResponse(['message' => 'ID não fornecido'], 400);
            return;
        }
        $data = $this->getJsonData();
        $this->telefoneModel->update($id, $data);
        $this->sendResponse(['message' => 'Telefone atualizado.']);
    }

    private function delete($id)
    {
        if (!$id) {
            $this->sendResponse(['message' => 'ID não fornecido'], 400);
            return;
        }
        $this->telefoneModel->delete($id);
        $this->sendResponse(['message' => 'Telefone excluído.']);
    }
}
