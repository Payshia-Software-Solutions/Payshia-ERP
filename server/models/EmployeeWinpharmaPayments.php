<?php

class EmployeeWinpharmaPayments
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get all active payments
    public function getAllPayments()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM employee_winpharma_payments WHERE is_active = 1 ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get payment by ID
    public function getPaymentById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM employee_winpharma_payments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new payment record
    public function createPayment($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO employee_winpharma_payments (employee_number, marking_count, payment, is_active, payment_approval, created_at, created_by, update_by) 
                                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['employee_number'],
            $data['marking_count'],
            $data['payment'],
            $data['is_active'],
            $data['payment_approval'],
            $data['created_at'],
            $data['created_by'],
            $data['update_by'] ?? null
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update an existing payment record
    public function updatePayment($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE employee_winpharma_payments SET 
                                     employee_number = ?, 
                                     marking_count = ?, 
                                     payment = ?, 
                                     is_active = ?, 
                                     payment_approval = ?, 
                                     update_by = ? 
                                     WHERE id = ?");
        $stmt->execute([
            $data['employee_number'],
            $data['marking_count'],
            $data['payment'],
            $data['is_active'],
            $data['payment_approval'],
            $data['update_by'] ?? null,
            $id
        ]);
        return $stmt->rowCount();
    }

    // Delete a payment record by ID
    public function deletePayment($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM employee_winpharma_payments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>