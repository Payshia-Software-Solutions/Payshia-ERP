<?php

class TempGoodReceiveNote
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM temp_good_receive_note ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM temp_good_receive_note WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO temp_good_receive_note 
            (product_id, quantity, order_unit, order_rate, created_by, created_at, is_active, po_number, received_qty, mf_date, ex_date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['product_id'],
            $data['quantity'],
            $data['order_unit'],
            $data['order_rate'],
            $data['created_by'],
            $data['created_at'],
            $data['is_active'],
            $data['po_number'],
            $data['received_qty'],
            $data['mf_date'],
            $data['ex_date']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE temp_good_receive_note SET
            product_id = ?, quantity = ?, order_unit = ?, order_rate = ?, created_by = ?, created_at = ?, is_active = ?,
            po_number = ?, received_qty = ?, mf_date = ?, ex_date = ?
            WHERE id = ?");
        $stmt->execute([
            $data['product_id'],
            $data['quantity'],
            $data['order_unit'],
            $data['order_rate'],
            $data['created_by'],
            $data['created_at'],
            $data['is_active'],
            $data['po_number'],
            $data['received_qty'],
            $data['mf_date'],
            $data['ex_date'],
            $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM temp_good_receive_note WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>