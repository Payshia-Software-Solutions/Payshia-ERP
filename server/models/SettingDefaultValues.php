<?php

class SettingDefaultValues
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get all setting default values
    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM setting_default_values ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single setting default value by ID
    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM setting_default_values WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new setting default value
    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO setting_default_values (settingName, defaultValue) VALUES (?, ?)");
        $stmt->execute([
            $data['settingName'],
            $data['defaultValue']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update an existing setting default value
    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE setting_default_values SET settingName = ?, defaultValue = ? WHERE id = ?");
        $stmt->execute([
            $data['settingName'],
            $data['defaultValue'],
            $id
        ]);
        return $stmt->rowCount();
    }

    // Delete a setting default value by ID
    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM setting_default_values WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>