<?php
// Position.php
class Pages
{
    protected $db;
    protected $table_name = "page_table";
    protected $lastError;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Insert a new employee
    public function add($data)
    {
        return $this->db->insert($this->table_name, $data);
    }

    // Update an existing employee
    public function update($data, $id)
    {
        $condition = "id = :id";
        $data['id'] = $id; // Add the ID to the data array for binding
        return $this->db->update($this->table_name, $data, $condition);
    }

    // Delete an employee
    public function delete($id)
    {
        $condition = "id = :id";
        return $this->db->delete($this->table_name, $condition, ['id' => $id]);
    }

    // Get the last error from the database
    public function getLastError()
    {
        return $this->db->getLastError();
    }

    public function fetchAll()
    {
        try {
            $query = "SELECT * FROM " . $this->table_name;
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return [];
        }
    }

    public function fetchById($id)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->db->prepare($query); // Use the PDO connection
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return null;
        }
    }

    public function fetchByCategories($category)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE `root` LIKE '$category'";
            $stmt = $this->db->prepare($query); // Use the PDO connection
            $stmt->execute();

            $resultArray = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultArray[$row['id']] = $row;
            }
            return $resultArray;
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return null;
        }
    }
}
