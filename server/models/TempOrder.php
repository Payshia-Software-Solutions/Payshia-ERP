<?php

class TempOrder
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM temp_order ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM temp_order WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO temp_order 
            (user_id, product_id, item_price, item_discount, quantity, added_date, is_active, customer_id, hold_status, table_id, printed_status, location_id, item_remark)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['user_id'],
            $data['product_id'],
            $data['item_price'],
            $data['item_discount'],
            $data['quantity'],
            $data['added_date'],
            $data['is_active'],
            $data['customer_id'],
            $data['hold_status'],
            $data['table_id'],
            $data['printed_status'],
            $data['location_id'],
            $data['item_remark']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE temp_order SET
            user_id = ?, product_id = ?, item_price = ?, item_discount = ?, quantity = ?, added_date = ?, is_active = ?, customer_id = ?, hold_status = ?, table_id = ?, printed_status = ?, location_id = ?, item_remark = ?
            WHERE id = ?");
        $stmt->execute([
            $data['user_id'],
            $data['product_id'],
            $data['item_price'],
            $data['item_discount'],
            $data['quantity'],
            $data['added_date'],
            $data['is_active'],
            $data['customer_id'],
            $data['hold_status'],
            $data['table_id'],
            $data['printed_status'],
            $data['location_id'],
            $data['item_remark'],
            $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM temp_order WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>