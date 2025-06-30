<?php

class Section
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get all active sections
    public function getAllSections()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM master_sections WHERE is_active = 1 ORDER BY section_name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get section by ID
    public function getSectionById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM master_sections WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create new section
    public function createSection($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO master_sections (
            section_name, is_active, created_at, created_by, pos_display
        ) VALUES (?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['section_name'],
            $data['is_active'],
            $data['created_at'],
            $data['created_by'],
            $data['pos_display']
        ]);

        return $this->pdo->lastInsertId();
    }

    // Update existing section
    public function updateSection($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE master_sections SET
            section_name = ?, is_active = ?, created_by = ?, pos_display = ?
            WHERE id = ?");

        $stmt->execute([
            $data['section_name'],
            $data['is_active'],
            $data['created_by'],
            $data['pos_display'],
            $id
        ]);

        return $stmt->rowCount();
    }

    // Delete section
    public function deleteSection($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM master_sections WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>