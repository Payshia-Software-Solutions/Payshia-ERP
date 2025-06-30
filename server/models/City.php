<?php

class City
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all cities
    public function getAllCities()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `cities`");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single city by ID
    public function getCityById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `cities` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new city
    public function createCity($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO `cities` (
            `district_id`, `name_en`, `name_si`, `name_ta`,
            `sub_name_en`, `sub_name_si`, `sub_name_ta`,
            `postcode`, `latitude`, `longitude`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['district_id'],
            $data['name_en'],
            $data['name_si'],
            $data['name_ta'],
            $data['sub_name_en'],
            $data['sub_name_si'],
            $data['sub_name_ta'],
            $data['postcode'],
            $data['latitude'],
            $data['longitude']
        ]);

        return $this->pdo->lastInsertId();
    }

    // Update a city
    public function updateCity($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE `cities` SET
            `district_id` = ?,
            `name_en` = ?,
            `name_si` = ?,
            `name_ta` = ?,
            `sub_name_en` = ?,
            `sub_name_si` = ?,
            `sub_name_ta` = ?,
            `postcode` = ?,
            `latitude` = ?,
            `longitude` = ?
            WHERE `id` = ?");

        $stmt->execute([
            $data['district_id'],
            $data['name_en'],
            $data['name_si'],
            $data['name_ta'],
            $data['sub_name_en'],
            $data['sub_name_si'],
            $data['sub_name_ta'],
            $data['postcode'],
            $data['latitude'],
            $data['longitude'],
            $id
        ]);

        return $stmt->rowCount();
    }

    // Delete a city by ID
    public function deleteCity($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `cities` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>