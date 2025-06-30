<?php

class TransactionExpenses
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_expenses ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_expenses WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO transaction_expenses 
            (expense_id, ex_type, ex_description, amount, updated_by, updated_at, location_id, is_active)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['expense_id'],
            $data['ex_type'],
            $data['ex_description'],
            $data['amount'],
            $data['updated_by'],
            $data['updated_at'],
            $data['location_id'],
            $data['is_active'] ?? 1
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE transaction_expenses SET
            expense_id = ?, ex_type = ?, ex_description = ?, amount = ?, 
            updated_by = ?, updated_at = ?, location_id = ?, is_active = ?
            WHERE id = ?");
        $stmt->execute([
            $data['expense_id'],
            $data['ex_type'],
            $data['ex_description'],
            $data['amount'],
            $data['updated_by'],
            $data['updated_at'],
            $data['location_id'],
            $data['is_active'],
            $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM transaction_expenses WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>