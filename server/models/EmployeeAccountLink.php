<?php

class EmployeeAccountLink
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllLinks()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `employee_account_links` WHERE `is_active` = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLinkById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `employee_account_links` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createLink($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO `employee_account_links` 
            (`account_type`, `employee_id`, `user_id`, `is_active`, `created_at`, `created_by`) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['account_type'],
            $data['employee_id'],
            $data['user_id'],
            $data['is_active'],
            $data['created_at'],
            $data['created_by']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function updateLink($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE `employee_account_links` SET 
            `account_type` = ?, 
            `employee_id` = ?, 
            `user_id` = ?, 
            `is_active` = ?, 
            `updated_at` = ?, 
            `created_by` = ? 
            WHERE `id` = ?");
        $stmt->execute([
            $data['account_type'],
            $data['employee_id'],
            $data['user_id'],
            $data['is_active'],
            $data['updated_at'],
            $data['created_by'],
            $id
        ]);
        return $stmt->rowCount();
    }

    public function deleteLink($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `employee_account_links` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>