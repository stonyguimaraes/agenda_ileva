<?php
class Telefone
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function findAll()
    {
        $sql = "SELECT t.*, c.nome AS nome_contato 
                FROM telefones t
                LEFT JOIN contatos c ON t.id_contato = c.id
                ORDER BY c.nome, t.id";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM telefones WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Novo método para buscar telefones de um contato
    public function findByContatoId($id_contato)
    {
        $stmt = $this->pdo->prepare("SELECT id, numero, tipo FROM telefones WHERE id_contato = ?");
        $stmt->execute([$id_contato]);
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        $sql = "INSERT INTO telefones (id_contato, numero, tipo) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['id_contato'],
            $data['numero'],
            $data['tipo'] ?? 'celular'
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE telefones SET numero = ?, tipo = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$data['numero'], $data['tipo'], $id]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM telefones WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
