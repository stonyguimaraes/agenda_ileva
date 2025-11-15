<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Contato.php';
require_once __DIR__ . '/../models/Telefone.php';

class ContatoController extends BaseController
{
    private $contatoModel;
    private $telefoneModel;

    public function __construct()
    {
        $this->contatoModel = new Contato();
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
        $contatos = $this->contatoModel->findAll();
        $this->sendResponse($contatos);
    }

    private function getById($id)
    {
        $contato = $this->contatoModel->findById($id);
        if (!$contato) {
            $this->sendResponse(['message' => 'Contato não encontrado'], 404);
            return;
        }
        // Anexa os telefones ao contato
        $contato['telefones'] = $this->telefoneModel->findByContatoId($id);
        $this->sendResponse($contato);
    }

    private function create()
    {
        $data = $this->getJsonData();
        $newId = $this->contatoModel->create($data);
        $this->sendResponse(['id' => $newId, 'message' => 'Contato criado com sucesso.'], 201);
    }

    private function update($id)
    {
        if (!$id) {
            $this->sendResponse(['message' => 'ID não fornecido'], 400);
            return;
        }
        $data = $this->getJsonData();
        $this->contatoModel->update($id, $data);
        $this->sendResponse(['message' => 'Contato atualizado com sucesso.']);
    }

    private function delete($id)
    {
        if (!$id) {
            $this->sendResponse(['message' => 'ID não fornecido'], 400);
            return;
        }
        $this->contatoModel->delete($id);
        $this->sendResponse(['message' => 'Contato excluído com sucesso.']);
    }
}
