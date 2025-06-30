<?php

class EmployeeWorklocations
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all active work locations
    public function getAllLocations()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM employee_worklocations WHERE is_active = 1 ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch single work location by ID
    public function getLocationById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM employee_worklocations WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create new work location
    public function createLocation($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO employee_worklocations (work_location_name, is_active, created_at, created_by) 
                                     VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['work_location_name'],
            $data['is_active'],
            $data['created_at'],
            $data['created_by']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update existing work location
    public function updateLocation($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE employee_worklocations SET 
                                     work_location_name = ?, 
                                     is_active = ?, 
                                     created_by = ?, 
                                     updated_at = NOW() 
                                     WHERE id = ?");
        $stmt->execute([
            $data['work_location_name'],
            $data['is_active'],
            $data['created_by'],
            $id
        ]);
        return $stmt->rowCount();
    }

    // Delete work location
    public function deleteLocation($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM employee_worklocations WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>