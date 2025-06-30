<?php

class TransactionInvoice
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_invoice ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_invoice WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO transaction_invoice (
                invoice_number, invoice_date, inv_amount, grand_total, discount_amount, discount_percentage,
                customer_code, service_charge, tendered_amount, close_type, invoice_status, current_time,
                location_id, table_id, order_ready_status, created_by, is_active, steward_id,
                cost_value, remark, ref_hold, payment_status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $data['invoice_number'],
            $data['invoice_date'],
            $data['inv_amount'],
            $data['grand_total'],
            $data['discount_amount'],
            $data['discount_percentage'],
            $data['customer_code'],
            $data['service_charge'],
            $data['tendered_amount'],
            $data['close_type'],
            $data['invoice_status'],
            $data['current_time'],
            $data['location_id'],
            $data['table_id'],
            $data['order_ready_status'],
            $data['created_by'],
            $data['is_active'],
            $data['steward_id'],
            $data['cost_value'],
            $data['remark'],
            $data['ref_hold'],
            $data['payment_status']
        ]);

        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("
            UPDATE transaction_invoice SET
                invoice_number = ?, invoice_date = ?, inv_amount = ?, grand_total = ?, discount_amount = ?, discount_percentage = ?,
                customer_code = ?, service_charge = ?, tendered_amount = ?, close_type = ?, invoice_status = ?, current_time = ?,
                location_id = ?, table_id = ?, order_ready_status = ?, created_by = ?, is_active = ?, steward_id = ?,
                cost_value = ?, remark = ?, ref_hold = ?, payment_status = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['invoice_number'],
            $data['invoice_date'],
            $data['inv_amount'],
            $data['grand_total'],
            $data['discount_amount'],
            $data['discount_percentage'],
            $data['customer_code'],
            $data['service_charge'],
            $data['tendered_amount'],
            $data['close_type'],
            $data['invoice_status'],
            $data['current_time'],
            $data['location_id'],
            $data['table_id'],
            $data['order_ready_status'],
            $data['created_by'],
            $data['is_active'],
            $data['steward_id'],
            $data['cost_value'],
            $data['remark'],
            $data['ref_hold'],
            $data['payment_status'],
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM transaction_invoice WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
