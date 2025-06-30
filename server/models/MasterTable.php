<?php

class Table
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all active tables
    public function getAllTables()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM master_table WHERE is_active = 1 ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single table by ID
    public function getTableById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM master_table WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new table record
    public function createTable($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO master_table (table_name, is_active, created_at, created_by, location_id)
                                     VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['table_name'],
            $data['is_active'],
            $data['created_at'],
            $data['created_by'],
            $data['location_id']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update an existing table record
    public function updateTable($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE master_table SET 
                                     table_name = ?, 
                                     is_active = ?, 
                                     created_by = ?, 
                                     location_id = ?
                                     WHERE id = ?");
        $stmt->execute([
            $data['table_name'],
            $data['is_active'],
            $data['created_by'],
            $data['location_id'],
            $id
        ]);
        return $stmt->rowCount();
    }

    // Delete a table record by ID
    public function deleteTable($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM master_table WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>