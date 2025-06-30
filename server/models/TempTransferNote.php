<?php

class TempTransferNote
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM temp_transfer_note ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM temp_transfer_note WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO temp_transfer_note 
            (user_name, product_id, quantity, order_rate, added_date, is_active, hold_status, from_location_id, to_location_id, order_unit, tax_type)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['user_name'],
            $data['product_id'],
            $data['quantity'],
            $data['order_rate'],
            $data['added_date'],
            $data['is_active'],
            $data['hold_status'],
            $data['from_location_id'],
            $data['to_location_id'],
            $data['order_unit'],
            $data['tax_type']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE temp_transfer_note SET
            user_name = ?, product_id = ?, quantity = ?, order_rate = ?, added_date = ?, is_active = ?, hold_status = ?, from_location_id = ?, to_location_id = ?, order_unit = ?, tax_type = ?
            WHERE id = ?");
        $stmt->execute([
            $data['user_name'],
            $data['product_id'],
            $data['quantity'],
            $data['order_rate'],
            $data['added_date'],
            $data['is_active'],
            $data['hold_status'],
            $data['from_location_id'],
            $data['to_location_id'],
            $data['order_unit'],
            $data['tax_type'],
            $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM temp_transfer_note WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>