<?php

class CustomerArea
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get all customer areas
    public function getAllAreas()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `customer_area` WHERE `is_active` = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single customer area by ID
    public function getAreaById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `customer_area` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new customer area
    public function createArea($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO `customer_area` 
            (`area_title`, `is_active`, `created_by`, `created_at`, `route_id`, `region_id`) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['area_title'],
            $data['is_active'],
            $data['created_by'],
            $data['created_at'],
            $data['route_id'],
            $data['region_id']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update an existing customer area
    public function updateArea($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE `customer_area` SET 
            `area_title` = ?, 
            `is_active` = ?, 
            `created_by` = ?, 
            `route_id` = ?, 
            `region_id` = ? 
            WHERE `id` = ?");
        $stmt->execute([
            $data['area_title'],
            $data['is_active'],
            $data['created_by'],
            $data['route_id'],
            $data['region_id'],
            $id
        ]);
        return $stmt->rowCount();
    }

    // Delete a customer area by ID
    public function deleteArea($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `customer_area` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>