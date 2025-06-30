<?php

class AddonProductLink
{
    private $pdo;

    // Constructor to initialize the PDO connection
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all active addon-product links
    public function getAllLinks()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `addon_product_link` WHERE `is_active` = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single link by ID
    public function getLinkById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `addon_product_link` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new addon-product link
    public function createLink($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO `addon_product_link` (`product_code`, `reference_id`, `created_by`, `created_at`, `is_active`) 
                                     VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['product_code'],
            $data['reference_id'],
            $data['created_by'],
            $data['created_at'],
            $data['is_active']
        ]);
        return $this->pdo->lastInsertId(); // Return the ID of the newly created link
    }

    // Update an existing link
    public function updateLink($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE `addon_product_link` SET 
                                     `product_code` = ?, 
                                     `reference_id` = ?, 
                                     `created_by` = ?, 
                                     `is_active` = ? 
                                     WHERE `id` = ?");
        $stmt->execute([
            $data['product_code'],
            $data['reference_id'],
            $data['created_by'],
            $data['is_active'],
            $id
        ]);
        return $stmt->rowCount(); // Number of rows affected
    }

    // Delete a link by ID
    public function deleteLink($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `addon_product_link` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>