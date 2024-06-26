<?php
// Levels.php
// Include the Position class
include_once 'Submissions.php';
class Levels extends Submissions
{
    protected $table_name = "win_pharma_level"; // Override table name if needed

    public function __construct($db)
    {
        parent::__construct($db); // Call the parent constructor to initialize $db
    }

    // Department specific methods can be added here
    // Get levels by course code
    public function getLevels($courseCode)
    {
        try {
            $query = "SELECT `level_id`, `course_code`, `level_name`, `is_active`, `created_at`, `created_by`
                        FROM " . $this->table_name . " 
                        WHERE `course_code` LIKE :courseCode 
                        ORDER BY `level_id`";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':courseCode', $courseCode, PDO::PARAM_STR);
            $stmt->execute();

            $levels = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $levels[$row['level_id']] = $row;
            }
            return $levels;
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return [];
        }
    }
}
