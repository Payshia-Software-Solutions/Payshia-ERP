<?php

class Location
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all locations
    public function getAllLocations()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM master_location WHERE is_active = 1 ORDER BY location_name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single location by ID
    public function getLocationById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM master_location WHERE location_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new location
    public function createLocation($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO master_location (
            location_name, is_active, created_at, created_by,
            logo_path, address_line1, address_line2, city,
            phone_1, phone_2, pos_status, pos_token, location_type
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['location_name'],
            $data['is_active'],
            $data['created_at'],
            $data['created_by'],
            $data['logo_path'],
            $data['address_line1'],
            $data['address_line2'],
            $data['city'],
            $data['phone_1'],
            $data['phone_2'],
            $data['pos_status'],
            $data['pos_token'],
            $data['location_type']
        ]);

        return $this->pdo->lastInsertId();
    }

    // Update an existing location
    public function updateLocation($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE master_location SET 
            location_name = ?, 
            is_active = ?, 
            created_by = ?, 
            logo_path = ?, 
            address_line1 = ?, 
            address_line2 = ?, 
            city = ?, 
            phone_1 = ?, 
            phone_2 = ?, 
            pos_status = ?, 
            pos_token = ?, 
            location_type = ?
            WHERE location_id = ?");

        $stmt->execute([
            $data['location_name'],
            $data['is_active'],
            $data['created_by'],
            $data['logo_path'],
            $data['address_line1'],
            $data['address_line2'],
            $data['city'],
            $data['phone_1'],
            $data['phone_2'],
            $data['pos_status'],
            $data['pos_token'],
            $data['location_type'],
            $id
        ]);

        return $stmt->rowCount();
    }

    // Delete a location
    public function deleteLocation($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM master_location WHERE location_id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>