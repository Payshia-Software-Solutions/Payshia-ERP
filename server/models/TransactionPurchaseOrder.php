<?php

class TransactionPurchaseOrder
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get all active purchase orders
    public function getAllPurchaseOrders()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_purchase_order WHERE is_active = 1 ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get purchase order by ID
    public function getPurchaseOrderById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_purchase_order WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create new purchase order
    public function createPurchaseOrder($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO transaction_purchase_order 
            (po_number, location_id, supplier_id, currency, tax_type, sub_total, created_by, created_at, is_active, po_status, remarks) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['po_number'],
            $data['location_id'],
            $data['supplier_id'],
            $data['currency'],
            $data['tax_type'],
            $data['sub_total'],
            $data['created_by'],
            $data['created_at'],
            $data['is_active'],
            $data['po_status'],
            $data['remarks']
        ]);

        return $this->pdo->lastInsertId();
    }

    // Update purchase order
    public function updatePurchaseOrder($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE transaction_purchase_order SET 
            po_number = ?, 
            location_id = ?, 
            supplier_id = ?, 
            currency = ?, 
            tax_type = ?, 
            sub_total = ?, 
            created_by = ?, 
            is_active = ?, 
            po_status = ?, 
            remarks = ?
            WHERE id = ?");

        $stmt->execute([
            $data['po_number'],
            $data['location_id'],
            $data['supplier_id'],
            $data['currency'],
            $data['tax_type'],
            $data['sub_total'],
            $data['created_by'],
            $data['is_active'],
            $data['po_status'],
            $data['remarks'],
            $id
        ]);

        return $stmt->rowCount();
    }

    // Delete purchase order
    public function deletePurchaseOrder($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM transaction_purchase_order WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>