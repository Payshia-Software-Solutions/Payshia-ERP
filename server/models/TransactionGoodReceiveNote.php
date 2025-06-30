<?php

class TransactionGoodReceiveNote
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_good_receive_note ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_good_receive_note WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO transaction_good_receive_note 
            (grn_number, location_id, supplier_id, currency, tax_type, sub_total, tax_value, grand_total, created_by, created_at, is_active, grn_status, remarks, payment_status, po_number) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['grn_number'],
            $data['location_id'],
            $data['supplier_id'],
            $data['currency'],
            $data['tax_type'],
            $data['sub_total'],
            $data['tax_value'],
            $data['grand_total'],
            $data['created_by'],
            $data['created_at'],
            $data['is_active'],
            $data['grn_status'],
            $data['remarks'],
            $data['payment_status'],
            $data['po_number']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE transaction_good_receive_note SET 
            grn_number = ?, location_id = ?, supplier_id = ?, currency = ?, tax_type = ?, sub_total = ?, tax_value = ?, 
            grand_total = ?, created_by = ?, created_at = ?, is_active = ?, grn_status = ?, remarks = ?, payment_status = ?, po_number = ? 
            WHERE id = ?");
        $stmt->execute([
            $data['grn_number'],
            $data['location_id'],
            $data['supplier_id'],
            $data['currency'],
            $data['tax_type'],
            $data['sub_total'],
            $data['tax_value'],
            $data['grand_total'],
            $data['created_by'],
            $data['created_at'],
            $data['is_active'],
            $data['grn_status'],
            $data['remarks'],
            $data['payment_status'],
            $data['po_number'],
            $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM transaction_good_receive_note WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
