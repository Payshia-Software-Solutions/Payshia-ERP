<?php

class TransactionInvoiceItem
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_invoice_items ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_invoice_items WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO transaction_invoice_items (
                user_id, product_id, item_price, item_discount, quantity, added_date,
                is_active, customer_id, hold_status, table_id, invoice_number,
                cost_price, printed_status, item_remark
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $data['user_id'],
            $data['product_id'],
            $data['item_price'],
            $data['item_discount'],
            $data['quantity'],
            $data['added_date'],
            $data['is_active'],
            $data['customer_id'],
            $data['hold_status'],
            $data['table_id'],
            $data['invoice_number'],
            $data['cost_price'],
            $data['printed_status'],
            $data['item_remark']
        ]);

        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("
            UPDATE transaction_invoice_items SET
                user_id = ?, product_id = ?, item_price = ?, item_discount = ?, quantity = ?, added_date = ?,
                is_active = ?, customer_id = ?, hold_status = ?, table_id = ?, invoice_number = ?,
                cost_price = ?, printed_status = ?, item_remark = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['user_id'],
            $data['product_id'],
            $data['item_price'],
            $data['item_discount'],
            $data['quantity'],
            $data['added_date'],
            $data['is_active'],
            $data['customer_id'],
            $data['hold_status'],
            $data['table_id'],
            $data['invoice_number'],
            $data['cost_price'],
            $data['printed_status'],
            $data['item_remark'],
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM transaction_invoice_items WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
