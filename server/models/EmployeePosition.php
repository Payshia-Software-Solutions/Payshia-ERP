<?php

class EmployeePosition
{
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all active positions
    public function getAllPositions()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM employee_position WHERE is_active = 1 ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single position by ID
    public function getPositionById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM employee_position WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new position
    public function createPosition($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO employee_position (position_name, is_active, created_at, created_by) 
                                     VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['position_name'],
            $data['is_active'],
            $data['created_at'],
            $data['created_by']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update an existing position
    public function updatePosition($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE employee_position SET 
                                     position_name = ?, 
                                     is_active = ?, 
                                     updated_at = ?, 
                                     created_by = ? 
                                     WHERE id = ?");
        $stmt->execute([
            $data['position_name'],
            $data['is_active'],
            $data['updated_at'],
            $data['created_by'],
            $id
        ]);
        return $stmt->rowCount();
    }

    // Delete a position by ID
    public function deletePosition($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM employee_position WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>