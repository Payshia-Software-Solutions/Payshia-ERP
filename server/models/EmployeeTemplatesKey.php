<?php

class EmployeeTemplatesKey
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get all active template keys
    public function getAllTemplateKeys()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM employee_templates_key WHERE is_active = 1 ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single template key by ID
    public function getTemplateKeyById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM employee_templates_key WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new template key
    public function createTemplateKey($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO employee_templates_key (template_id, set_key, is_active, created_at, created_by, pay_mode) 
                                     VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['template_id'],
            $data['set_key'],
            $data['is_active'],
            $data['created_at'],
            $data['created_by'],
            $data['pay_mode']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update an existing template key
    public function updateTemplateKey($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE employee_templates_key SET 
                                     template_id = ?, 
                                     set_key = ?, 
                                     is_active = ?, 
                                     updated_at = ?, 
                                     created_by = ?, 
                                     pay_mode = ? 
                                     WHERE id = ?");
        $stmt->execute([
            $data['template_id'],
            $data['set_key'],
            $data['is_active'],
            $data['updated_at'],
            $data['created_by'],
            $data['pay_mode'],
            $id
        ]);
        return $stmt->rowCount();
    }

    // Delete a template key by ID
    public function deleteTemplateKey($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM employee_templates_key WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>