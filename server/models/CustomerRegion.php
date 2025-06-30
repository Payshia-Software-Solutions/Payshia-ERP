<?php

class CustomerRegion
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get all active customer regions
    public function getAllRegions()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `customer_region` WHERE `is_active` = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single customer region by ID
    public function getRegionById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `customer_region` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new customer region
    public function createRegion($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO `customer_region` 
            (`region_name`, `is_active`, `created_by`, `created_at`) 
            VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['region_name'],
            $data['is_active'],
            $data['created_by'],
            $data['created_at']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update an existing customer region
    public function updateRegion($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE `customer_region` SET 
            `region_name` = ?, 
            `is_active` = ?, 
            `created_by` = ? 
            WHERE `id` = ?");
        $stmt->execute([
            $data['region_name'],
            $data['is_active'],
            $data['created_by'],
            $id
        ]);
        return $stmt->rowCount();
    }

    // Delete a customer region
    public function deleteRegion($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `customer_region` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>