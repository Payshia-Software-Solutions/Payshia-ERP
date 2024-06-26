<?php
// Submissions.php
class Submissions
{
    protected $db;
    protected $table_name = "win_pharma_submission";
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
    public function update($data, $submission_id)
    {
        $condition = "submission_id = :submission_id";
        $data['submission_id'] = $submission_id; // Add the ID to the data array for binding
        return $this->db->update($this->table_name, $data, $condition);
    }

    // Delete an employee
    public function delete($id)
    {
        $condition = "submission_id = :id";
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
            $query = "SELECT * FROM " . $this->table_name . " WHERE submission_id = :id";
            $stmt = $this->db->prepare($query); // Use the PDO connection
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return null;
        }
    }

    public function fetchAllByCondition($defaultCourse, $requestStatus)
    {
        try {
            if ($requestStatus == "All") {
                $query = "SELECT `submission_id`, `index_number`, `level_id`, `resource_id`, `submission`, `grade`, `grade_status`, `attempt`, `course_code`,`date_time`, `reason`, `update_by`, `update_at` FROM `win_pharma_submission` WHERE `course_code` LIKE '$defaultCourse' ORDER BY CASE WHEN grade_status = 'Pending' THEN 0 ELSE 1 END, submission_id ASC";
            } else {
                $query = "SELECT `submission_id`, `index_number`, `level_id`, `resource_id`, `submission`, `grade`, `grade_status`, `attempt`, `course_code`,`date_time`, `reason`, `update_by`, `update_at`  FROM `win_pharma_submission` WHERE `course_code` LIKE '$defaultCourse' AND `grade_status` LIKE '$requestStatus' ORDER BY CASE WHEN grade_status = 'Pending' THEN 0 ELSE 1 END, submission_id ASC";
            }

            $stmt = $this->db->prepare($query); // Use the PDO connection
            $stmt->execute();

            $submissions = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $submissions[$row['submission_id']] = $row;
            }
            return $submissions;
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return null;
        }
    }

    public function fetchSubmissionByInstructor($userAccount, $requestStatus = 'All')
    {
        try {

            if ($requestStatus == "All") {
                $query = "SELECT `submission_id`, `index_number`, `level_id`, `resource_id`, `submission`, `grade`, `grade_status`, `attempt`, `course_code`,`date_time`, `reason`, `update_by`, `update_at`  FROM `win_pharma_submission` WHERE `update_by` LIKE '$userAccount' ORDER BY CASE WHEN grade_status = 'Pending' THEN 0 ELSE 1 END, submission_id ASC";
            } else {
                $query = "SELECT `submission_id`, `index_number`, `level_id`, `resource_id`, `submission`, `grade`, `grade_status`, `attempt`, `course_code`,`date_time`, `reason`, `update_by`, `update_at`  FROM `win_pharma_submission` WHERE `update_by` LIKE '$userAccount' AND `grade_status` LIKE '$requestStatus' ORDER BY CASE WHEN grade_status = 'Pending' THEN 0 ELSE 1 END, submission_id ASC";
            }

            $stmt = $this->db->prepare($query); // Use the PDO connection
            $stmt->execute();

            $submissions = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $submissions[$row['submission_id']] = $row;
            }
            return $submissions;
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return null;
        }
    }
}
