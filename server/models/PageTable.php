<?php

class PageTable
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all active pages
    public function getAllPages()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM page_table WHERE is_active = 1 ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch single page by ID
    public function getPageById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM page_table WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new page
    public function createPage($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO page_table (page_name, display_name, pack_icon, is_active, type, root, open_type)
                                     VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['page_name'],
            $data['display_name'],
            $data['pack_icon'],
            $data['is_active'],
            $data['type'],
            $data['root'],
            $data['open_type']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update a page
    public function updatePage($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE page_table SET 
                                     page_name = ?, 
                                     display_name = ?, 
                                     pack_icon = ?, 
                                     is_active = ?, 
                                     type = ?, 
                                     root = ?, 
                                     open_type = ?
                                     WHERE id = ?");
        $stmt->execute([
            $data['page_name'],
            $data['display_name'],
            $data['pack_icon'],
            $data['is_active'],
            $data['type'],
            $data['root'],
            $data['open_type'],
            $id
        ]);
        return $stmt->rowCount();
    }

    // Delete a page
    public function deletePage($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM page_table WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>