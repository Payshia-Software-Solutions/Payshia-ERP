<?php

class TransactionProductionItem
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_production_items ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_production_items WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByPnId($pn_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_production_items WHERE pn_id = ?");
        $stmt->execute([$pn_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO transaction_production_items (
                product_id, quantity, cost_price, pn_id,
                created_at, created_by, is_active
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['product_id'],
            $data['quantity'],
            $data['cost_price'],
            $data['pn_id'],
            $data['created_at'],
            $data['created_by'],
            $data['is_active']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("
            UPDATE transaction_production_items SET
                product_id = ?, quantity = ?, cost_price = ?, pn_id = ?,
                created_at = ?, created_by = ?, is_active = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['product_id'],
            $data['quantity'],
            $data['cost_price'],
            $data['pn_id'],
            $data['created_at'],
            $data['created_by'],
            $data['is_active'],
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM transaction_production_items WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
