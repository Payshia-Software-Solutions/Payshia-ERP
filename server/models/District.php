<?php

class District
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllDistricts()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `districts`");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDistrictById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `districts` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createDistrict($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO `districts` (`province_id`, `name_en`, `name_si`, `name_ta`)
                                     VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['province_id'],
            $data['name_en'],
            $data['name_si'],
            $data['name_ta']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function updateDistrict($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE `districts` SET `province_id` = ?, `name_en` = ?, `name_si` = ?, `name_ta` = ?
                                     WHERE `id` = ?");
        $stmt->execute([
            $data['province_id'],
            $data['name_en'],
            $data['name_si'],
            $data['name_ta'],
            $id
        ]);
        return $stmt->rowCount();
    }

    public function deleteDistrict($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `districts` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>