<?php

class IconsPack
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get all icons
    public function getAllIcons()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM icons_pack ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single icon by ID
    public function getIconById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM icons_pack WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new icon record
    public function createIcon($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO icons_pack (pack_prefix, icon_name, category) VALUES (?, ?, ?)");
        $stmt->execute([
            $data['pack_prefix'],
            $data['icon_name'],
            $data['category']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update an existing icon
    public function updateIcon($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE icons_pack SET pack_prefix = ?, icon_name = ?, category = ? WHERE id = ?");
        $stmt->execute([
            $data['pack_prefix'],
            $data['icon_name'],
            $data['category'],
            $id
        ]);
        return $stmt->rowCount();
    }

    // Delete an icon by ID
    public function deleteIcon($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM icons_pack WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>