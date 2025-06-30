<?php

class Categories
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllCategories(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM master_categories WHERE is_active = 1 ORDER BY pos_display ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM master_categories WHERE id = ?");
        $stmt->execute([$id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        return $category ?: null;
    }

    public function createCategory(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO master_categories 
                (section_id, department_id, category_name, is_active, created_at, created_by, pos_display) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['section_id'],
            $data['department_id'],
            $data['category_name'],
            $data['is_active'],
            $data['created_at'],
            $data['created_by'],
            $data['pos_display']
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function updateCategory(int $id, array $data): int
    {
        $stmt = $this->pdo->prepare("
            UPDATE master_categories SET 
                section_id = ?, 
                department_id = ?, 
                category_name = ?, 
                is_active = ?, 
                created_by = ?, 
                pos_display = ? 
            WHERE id = ?
        ");
        $stmt->execute([
            $data['section_id'],
            $data['department_id'],
            $data['category_name'],
            $data['is_active'],
            $data['created_by'],
            $data['pos_display'],
            $id
        ]);
        return $stmt->rowCount();
    }

    public function deleteCategory(int $id): int
    {
        $stmt = $this->pdo->prepare("DELETE FROM master_categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>