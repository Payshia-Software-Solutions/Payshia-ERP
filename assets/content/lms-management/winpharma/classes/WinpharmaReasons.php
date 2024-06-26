<?php
// Levels.php
// Include the Position class
include_once 'Submissions.php';
class WinpharmaReasons extends Submissions
{
    protected $table_name = "winpharma_common_resons"; // Override table name if needed

    public function __construct($db)
    {
        parent::__construct($db); // Call the parent constructor to initialize $db
    }
    public function delete($id)
    {
        $condition = "id = :id";
        return $this->db->delete($this->table_name, $condition, ['id' => $id]);
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
}
