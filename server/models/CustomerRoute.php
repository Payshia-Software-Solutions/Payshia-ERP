<?php

class CustomerRoute
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get all active customer routes
    public function getAllRoutes()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `customer_route` WHERE `is_active` = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single customer route by ID
    public function getRouteById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `customer_route` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new customer route
    public function createRoute($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO `customer_route` 
            (`route_title`, `is_active`, `created_by`, `created_at`, `region_id`) 
            VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['route_title'],
            $data['is_active'],
            $data['created_by'],
            $data['created_at'],
            $data['region_id']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update an existing customer route
    public function updateRoute($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE `customer_route` SET 
            `route_title` = ?, 
            `is_active` = ?, 
            `created_by` = ?, 
            `region_id` = ?
            WHERE `id` = ?");
        $stmt->execute([
            $data['route_title'],
            $data['is_active'],
            $data['created_by'],
            $data['region_id'],
            $id
        ]);
        return $stmt->rowCount();
    }

    // Delete a customer route
    public function deleteRoute($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `customer_route` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>