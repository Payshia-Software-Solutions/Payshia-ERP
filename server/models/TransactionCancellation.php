<?php

class TransactionCancellation
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_cancellation ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM transaction_cancellation WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO transaction_cancellation 
            (cancellation_type, ref_key, reason, created_by, created_at)
            VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['cancellation_type'],
            $data['ref_key'],
            $data['reason'],
            $data['created_by'],
            $data['created_at']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE transaction_cancellation SET
            cancellation_type = ?, ref_key = ?, reason = ?, created_by = ?, created_at = ?
            WHERE id = ?");
        $stmt->execute([
            $data['cancellation_type'],
            $data['ref_key'],
            $data['reason'],
            $data['created_by'],
            $data['created_at'],
            $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM transaction_cancellation WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>