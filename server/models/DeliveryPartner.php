<?php

class DeliveryPartner
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all delivery partners
    public function getAllPartners()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `delivery_partners`");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single delivery partner by ID
    public function getPartnerById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `delivery_partners` WHERE `partner_id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new delivery partner
    public function createPartner($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO `delivery_partners` 
            (`partner_name`, `phone_1_country_code`, `phone_1_suffix`, `phone_2_country_code`, `phone_2_suffix`,
             `address_l1`, `address_l2`, `city`, `district`, `contact_person`, `created_by`, `created_at`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['partner_name'],
            $data['phone_1_country_code'],
            $data['phone_1_suffix'],
            $data['phone_2_country_code'],
            $data['phone_2_suffix'],
            $data['address_l1'],
            $data['address_l2'],
            $data['city'],
            $data['district'],
            $data['contact_person'],
            $data['created_by'],
            $data['created_at']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update a delivery partner
    public function updatePartner($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE `delivery_partners` SET 
            `partner_name` = ?, 
            `phone_1_country_code` = ?, 
            `phone_1_suffix` = ?, 
            `phone_2_country_code` = ?, 
            `phone_2_suffix` = ?, 
            `address_l1` = ?, 
            `address_l2` = ?, 
            `city` = ?, 
            `district` = ?, 
            `contact_person` = ?, 
            `created_by` = ? 
            WHERE `partner_id` = ?");
        $stmt->execute([
            $data['partner_name'],
            $data['phone_1_country_code'],
            $data['phone_1_suffix'],
            $data['phone_2_country_code'],
            $data['phone_2_suffix'],
            $data['address_l1'],
            $data['address_l2'],
            $data['city'],
            $data['district'],
            $data['contact_person'],
            $data['created_by'],
            $id
        ]);
        return $stmt->rowCount();
    }

    // Delete a delivery partner
    public function deletePartner($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `delivery_partners` WHERE `partner_id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>