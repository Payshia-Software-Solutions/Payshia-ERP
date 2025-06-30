<?php

class TransactionGoodReceiveNoteItems
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_good_receive_note_items ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_good_receive_note_items WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO transaction_good_receive_note_items 
        (product_id, order_unit, order_rate, created_by, created_at, is_active, grn_number, received_qty, po_number) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['product_id'],
            $data['order_unit'],
            $data['order_rate'],
            $data['created_by'],
            $data['created_at'],
            $data['is_active'],
            $data['grn_number'],
            $data['received_qty'],
            $data['po_number']
        ]);

        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE transaction_good_receive_note_items SET 
            product_id = ?, order_unit = ?, order_rate = ?, created_by = ?, created_at = ?, is_active = ?, grn_number = ?, received_qty = ?, po_number = ?
            WHERE id = ?");

        return $stmt->execute([
            $data['product_id'],
            $data['order_unit'],
            $data['order_rate'],
            $data['created_by'],
            $data['created_at'],
            $data['is_active'],
            $data['grn_number'],
            $data['received_qty'],
            $data['po_number'],
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM transaction_good_receive_note_items WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
