<?php

class TransactionExpensesTypes
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_expenses_types ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_expenses_types WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO transaction_expenses_types (type, is_active) VALUES (?, ?)");
        $stmt->execute([
            $data['type'],
            $data['is_active'] ?? 1
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE transaction_expenses_types SET type = ?, is_active = ? WHERE id = ?");
        $stmt->execute([
            $data['type'],
            $data['is_active'],
            $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM transaction_expenses_types WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
