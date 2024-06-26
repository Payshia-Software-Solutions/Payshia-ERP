<?php
// Levels.php
// Include the Position class
include_once 'Submissions.php';
class Tasks extends Submissions
{
    protected $table_name = "win_pharma_level_resources"; // Override table name if needed

    public function __construct($db)
    {
        parent::__construct($db); // Call the parent constructor to initialize $db
    }

    // Department specific methods can be added here
    // Get levels by course code
    public function GetTasks($level_id)
    {
        try {
            $query = "SELECT `resource_id`, `level_id`, `resource_title`, `resource_data`, `created_by`, `task_cover`, `is_active` FROM " . $this->table_name .
                " WHERE `level_id` LIKE :level_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':level_id', $level_id, PDO::PARAM_STR);
            $stmt->execute();

            $levels = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $levels[$row['resource_id']] = $row;
            }
            return $levels;
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return [];
        }
    }

    public function GetTaskByResourceID($resource_id)
    {
        try {
            $query = "SELECT `resource_id`, `level_id`, `resource_title`, `resource_data`, `created_by`, `task_cover`, `is_active` FROM " . $this->table_name .
                " WHERE `resource_id` LIKE :resource_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':resource_id', $resource_id, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return [];
        }
    }
}
