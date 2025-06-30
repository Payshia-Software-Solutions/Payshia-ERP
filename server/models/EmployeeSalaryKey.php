<?php

class EmployeeSalaryKey
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get all active salary keys
    public function getAllSalaryKeys()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM employee_salary_keys WHERE is_active = 1 ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single salary key by ID
    public function getSalaryKeyById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM employee_salary_keys WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new salary key
    public function createSalaryKey($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO employee_salary_keys (key_name, is_active, created_at, created_by) 
                                     VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['key_name'],
            $data['is_active'],
            $data['created_at'],
            $data['created_by']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update an existing salary key
    public function updateSalaryKey($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE employee_salary_keys SET 
                                     key_name = ?, 
                                     is_active = ?, 
                                     updated_at = ?, 
                                     created_by = ? 
                                     WHERE id = ?");
        $stmt->execute([
            $data['key_name'],
            $data['is_active'],
            $data['updated_at'],
            $data['created_by'],
            $id
        ]);
        return $stmt->rowCount();
    }

    // Delete a salary key by ID
    public function deleteSalaryKey($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM employee_salary_keys WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>