<?php

class Department
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllDepartments(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM master_departments WHERE is_active = 1 ORDER BY pos_display ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDepartmentById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM master_departments WHERE id = ?");
        $stmt->execute([$id]);
        $department = $stmt->fetch(PDO::FETCH_ASSOC);
        return $department ?: null;
    }

    public function createDepartment(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO master_departments (
                section_id, department_name, is_active, created_at, created_by, pos_display
            ) VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['section_id'],
            $data['department_name'],
            $data['is_active'],
            $data['created_at'],
            $data['created_by'],
            $data['pos_display']
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function updateDepartment(int $id, array $data): int
    {
        $stmt = $this->pdo->prepare("
            UPDATE master_departments SET
                section_id = ?,
                department_name = ?,
                is_active = ?,
                created_at = ?,
                created_by = ?,
                pos_display = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $data['section_id'],
            $data['department_name'],
            $data['is_active'],
            $data['created_at'],
            $data['created_by'],
            $data['pos_display'],
            $id
        ]);
        return $stmt->rowCount();
    }

    public function deleteDepartment(int $id): int
    {
        $stmt = $this->pdo->prepare("DELETE FROM master_departments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>