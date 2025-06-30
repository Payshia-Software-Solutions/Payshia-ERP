<?php

class Country
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get all countries
    public function getAllCountries()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `countries`");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single country by ID
    public function getCountryById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `countries` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new country
    public function createCountry($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO `countries` (`sortname`, `name`, `phonecode`) VALUES (?, ?, ?)");
        $stmt->execute([
            $data['sortname'],
            $data['name'],
            $data['phonecode']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update an existing country
    public function updateCountry($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE `countries` SET `sortname` = ?, `name` = ?, `phonecode` = ? WHERE `id` = ?");
        $stmt->execute([
            $data['sortname'],
            $data['name'],
            $data['phonecode'],
            $id
        ]);
        return $stmt->rowCount();
    }

    // Delete a country by ID
    public function deleteCountry($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `countries` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>