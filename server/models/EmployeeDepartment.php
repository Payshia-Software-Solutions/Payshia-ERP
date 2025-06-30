<?php

class EmployeeDepartment
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM employee_departments WHERE is_active = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM employee_departments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO employee_departments (department_name, is_active, created_at, created_by)
                                     VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['department_name'],
            $data['is_active'],
            $data['created_at'],
            $data['created_by']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE employee_departments SET 
                                        department_name = ?, 
                                        is_active = ?, 
                                        updated_at = ?, 
                                        created_by = ?
                                     WHERE id = ?");
        $stmt->execute([
            $data['department_name'],
            $data['is_active'],
            $data['updated_at'],
            $data['created_by'],
            $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM employee_departments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
