<?php
class Contato
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function findAll()
    {

        $stmt_contatos = $this->pdo->query("SELECT id, nome, email FROM contatos ORDER BY nome");
        $contatos = $stmt_contatos->fetchAll(PDO::FETCH_ASSOC);

        if (empty($contatos)) {
            return [];
        }

        $contato_ids = array_column($contatos, 'id');

        $placeholders = implode(',', array_fill(0, count($contato_ids), '?'));

        $sql_telefones = "SELECT id, id_contato, numero, tipo 
                          FROM telefones 
                          WHERE id_contato IN ($placeholders)";

        $stmt_telefones = $this->pdo->prepare($sql_telefones);
        $stmt_telefones->execute($contato_ids);
        $all_telefones = $stmt_telefones->fetchAll(PDO::FETCH_ASSOC);

        $telefones_map = [];
        foreach ($all_telefones as $tel) {
            // Agrupa os telefones pelo id_contato
            $telefones_map[$tel['id_contato']][] = $tel;
        }

        foreach ($contatos as $key => $contato) {
            $contatos[$key]['telefones'] = $telefones_map[$contato['id']] ?? [];
        }

        return $contatos;
    }

    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM contatos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO contatos (nome, email) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$data['nome'], $data['email']]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE contatos SET nome = ?, email = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$data['nome'], $data['email'], $id]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM contatos WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
