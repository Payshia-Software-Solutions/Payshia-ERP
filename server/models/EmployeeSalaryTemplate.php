<?php

class EmployeeSalaryTemplate
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get all active templates
    public function getAllTemplates()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM employee_salary_templates WHERE is_active = 1 ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single template by ID
    public function getTemplateById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM employee_salary_templates WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new salary template
    public function createTemplate($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO employee_salary_templates (template_name, is_active, created_at, created_by) 
                                     VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['template_name'],
            $data['is_active'],
            $data['created_at'],
            $data['created_by']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update an existing template
    public function updateTemplate($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE employee_salary_templates SET 
                                     template_name = ?, 
                                     is_active = ?, 
                                     updated_at = ?, 
                                     created_by = ? 
                                     WHERE id = ?");
        $stmt->execute([
            $data['template_name'],
            $data['is_active'],
            $data['updated_at'],
            $data['created_by'],
            $id
        ]);
        return $stmt->rowCount();
    }

    // Delete a salary template by ID
    public function deleteTemplate($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM employee_salary_templates WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>