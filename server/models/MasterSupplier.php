<?php

class Supplier
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get all active suppliers
    public function getAllSuppliers()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM master_supplier WHERE is_active = 1 ORDER BY supplier_name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get supplier by ID
    public function getSupplierById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM master_supplier WHERE supplier_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create new supplier
    public function createSupplier($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO master_supplier (
            supplier_name, opening_balance, is_active, created_by, created_at,
            email, contact_person, street_name, city, zip_code, telephone, fax
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['supplier_name'],
            $data['opening_balance'],
            $data['is_active'],
            $data['created_by'],
            $data['created_at'],
            $data['email'],
            $data['contact_person'],
            $data['street_name'],
            $data['city'],
            $data['zip_code'],
            $data['telephone'],
            $data['fax']
        ]);

        return $this->pdo->lastInsertId();
    }

    // Update supplier
    public function updateSupplier($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE master_supplier SET
            supplier_name = ?, opening_balance = ?, is_active = ?, created_by = ?, 
            email = ?, contact_person = ?, street_name = ?, city = ?, zip_code = ?, 
            telephone = ?, fax = ?
            WHERE supplier_id = ?");

        $stmt->execute([
            $data['supplier_name'],
            $data['opening_balance'],
            $data['is_active'],
            $data['created_by'],
            $data['email'],
            $data['contact_person'],
            $data['street_name'],
            $data['city'],
            $data['zip_code'],
            $data['telephone'],
            $data['fax'],
            $id
        ]);

        return $stmt->rowCount();
    }

    // Delete supplier
    public function deleteSupplier($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM master_supplier WHERE supplier_id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>