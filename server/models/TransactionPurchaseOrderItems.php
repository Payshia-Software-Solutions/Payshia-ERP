<?php

class TransactionPurchaseOrderItems
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all active purchase order items
    public function getAllItems()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_purchase_order_items WHERE is_active = 1 ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single item by ID
    public function getItemById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_purchase_order_items WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new item
    public function createItem($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO transaction_purchase_order_items 
            (product_id, quantity, order_unit, order_rate, created_by, created_at, is_active, po_number) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['product_id'],
            $data['quantity'],
            $data['order_unit'],
            $data['order_rate'],
            $data['created_by'],
            $data['created_at'],
            $data['is_active'],
            $data['po_number']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update an existing item
    public function updateItem($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE transaction_purchase_order_items SET 
            product_id = ?, 
            quantity = ?, 
            order_unit = ?, 
            order_rate = ?, 
            created_by = ?, 
            is_active = ?, 
            po_number = ?
            WHERE id = ?");
        $stmt->execute([
            $data['product_id'],
            $data['quantity'],
            $data['order_unit'],
            $data['order_rate'],
            $data['created_by'],
            $data['is_active'],
            $data['po_number'],
            $id
        ]);
        return $stmt->rowCount();
    }

    // Delete an item by ID
    public function deleteItem($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM transaction_purchase_order_items WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>