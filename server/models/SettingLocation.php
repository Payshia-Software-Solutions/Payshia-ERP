<?php

class SettingLocation
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM setting_location ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM setting_location WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO setting_location (location_id, setting, default_value, custom_value, comment) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['location_id'],
            $data['setting'],
            $data['default_value'],
            $data['custom_value'],
            $data['comment']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE setting_location SET location_id = ?, setting = ?, default_value = ?, custom_value = ?, comment = ? WHERE id = ?");
        $stmt->execute([
            $data['location_id'],
            $data['setting'],
            $data['default_value'],
            $data['custom_value'],
            $data['comment'],
            $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM setting_location WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>