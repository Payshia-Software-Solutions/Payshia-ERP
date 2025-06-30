<?php

class TransactionQuotation
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get all active quotations
    public function getAllQuotations()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_quotation WHERE is_active = 1 ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single quotation by ID
    public function getQuotationById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_quotation WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new quotation
    public function createQuotation($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO transaction_quotation (
            quote_number, quote_date, quote_amount, grand_total, discount_amount, discount_percentage, 
            customer_code, service_charge, close_type, invoice_status, current_time, location_id, 
            created_by, is_active, cost_value, remark
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['quote_number'],
            $data['quote_date'],
            $data['quote_amount'],
            $data['grand_total'],
            $data['discount_amount'],
            $data['discount_percentage'],
            $data['customer_code'],
            $data['service_charge'],
            $data['close_type'] ?? 'Credit',
            $data['invoice_status'],
            $data['current_time'],
            $data['location_id'],
            $data['created_by'],
            $data['is_active'] ?? 1,
            $data['cost_value'],
            $data['remark'] ?? null
        ]);

        return $this->pdo->lastInsertId();
    }

    // Update existing quotation by ID
    public function updateQuotation($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE transaction_quotation SET
            quote_number = ?, 
            quote_date = ?, 
            quote_amount = ?, 
            grand_total = ?, 
            discount_amount = ?, 
            discount_percentage = ?, 
            customer_code = ?, 
            service_charge = ?, 
            close_type = ?, 
            invoice_status = ?, 
            current_time = ?, 
            location_id = ?, 
            created_by = ?, 
            is_active = ?, 
            cost_value = ?, 
            remark = ?
            WHERE id = ?");

        $stmt->execute([
            $data['quote_number'],
            $data['quote_date'],
            $data['quote_amount'],
            $data['grand_total'],
            $data['discount_amount'],
            $data['discount_percentage'],
            $data['customer_code'],
            $data['service_charge'],
            $data['close_type'] ?? 'Credit',
            $data['invoice_status'],
            $data['current_time'],
            $data['location_id'],
            $data['created_by'],
            $data['is_active'] ?? 1,
            $data['cost_value'],
            $data['remark'] ?? null,
            $id
        ]);

        return $stmt->rowCount();
    }

    // Delete quotation by ID
    public function deleteQuotation($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM transaction_quotation WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>