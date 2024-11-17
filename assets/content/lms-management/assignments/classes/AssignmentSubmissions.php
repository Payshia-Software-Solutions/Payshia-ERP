<?php
// AssignmentSubmissions.php
class AssignmentSubmissions extends Assignments
{
    protected $db;
    protected $table_name = "course_assignments_submissions";
    protected $lastError;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function fetchAllByAssignmentId($assignment_id)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE `assignment_id`  = :assignment_id";
            $stmt = $this->db->prepare($query); // Use the PDO connection
            $stmt->bindParam(':assignment_id', $assignment_id, PDO::PARAM_STR);
            $stmt->execute();

            $resultArray = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultArray[$row['id']] = $row;
            }
            return $resultArray;
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return [];
        }
    }

    public function fetchUserByAssignmentId($assignment_id, $created_by)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE `assignment_id`  = :assignment_id AND `created_by` LIKE :created_by AND `is_active` LIKE 1";
            $stmt = $this->db->prepare($query); // Use the PDO connection
            $stmt->bindParam(':created_by', $created_by, PDO::PARAM_STR);
            $stmt->bindParam(':assignment_id', $assignment_id, PDO::PARAM_STR);
            $stmt->execute();

            $resultArray = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultArray[$row['id']] = $row;
            }
            return $resultArray;
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return [];
        }
    }

    public function fetchAllGradedByAssignmentId($assignment_id)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE `assignment_id` = :assignment_id AND `grade_status` LIKE 1";
            $stmt = $this->db->prepare($query); // Use the PDO connection
            $stmt->bindParam(':assignment_id', $assignment_id, PDO::PARAM_STR);
            $stmt->execute();

            $resultArray = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultArray[$row['id']] = $row;
            }
            return $resultArray;
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return [];
        }
    }

    public function updateGrade($data, $assignment_id, $created_by)
    {
        $condition = "`assignment_id` = :assignment_id AND `created_by` LIKE :created_by";
        $data['assignment_id'] = $assignment_id;
        $data['created_by'] = $created_by;
        return $this->db->update($this->table_name, $data, $condition);
    }
}
