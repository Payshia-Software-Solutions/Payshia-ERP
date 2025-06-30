<?php

class Unit
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get all active units
    public function getAllUnits()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM master_unit WHERE is_active = 1 ORDER BY unit_id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get single unit by ID
    public function getUnitById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM master_unit WHERE unit_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new unit
    public function createUnit($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO master_unit (unit_name, is_active, created_by, created_at)
                                     VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['unit_name'],
            $data['is_active'],
            $data['created_by'],
            $data['created_at']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update an existing unit
    public function updateUnit($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE master_unit SET 
                                     unit_name = ?, 
                                     is_active = ?, 
                                     created_by = ?, 
                                     created_at = ?
                                     WHERE unit_id = ?");
        $stmt->execute([
            $data['unit_name'],
            $data['is_active'],
            $data['created_by'],
            $data['created_at'],
            $id
        ]);
        return $stmt->rowCount();
    }

    // Delete unit by ID
    public function deleteUnit($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM master_unit WHERE unit_id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>