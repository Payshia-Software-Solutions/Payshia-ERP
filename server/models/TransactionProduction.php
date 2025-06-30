<?php

class TransactionProduction
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_production ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_production WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO transaction_production (
                production_cost, location_id, created_by, created_at, remark,
                production_date, pn_number, is_active
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['production_cost'],
            $data['location_id'],
            $data['created_by'],
            $data['created_at'],
            $data['remark'],
            $data['production_date'],
            $data['pn_number'],
            $data['is_active']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("
            UPDATE transaction_production SET
                production_cost = ?, location_id = ?, created_by = ?, created_at = ?,
                remark = ?, production_date = ?, pn_number = ?, is_active = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['production_cost'],
            $data['location_id'],
            $data['created_by'],
            $data['created_at'],
            $data['remark'],
            $data['production_date'],
            $data['pn_number'],
            $data['is_active'],
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM transaction_production WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
