<?php
class Employee
{
    private $db;
    private $table_name = "employee_details";
    private $lastError;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Insert a new employee
    public function addEmployee($data)
    {
        return $this->db->insert($this->table_name, $data);
    }

    // Update an existing employee
    public function updateEmployee($data, $employee_id)
    {
        $condition = "employee_id = :employee_id";
        $data['employee_id'] = $employee_id; // Add the ID to the data array for binding
        return $this->db->update($this->table_name, $data, $condition);
    }

    // Delete an employee
    public function deleteEmployee($employee_id)
    {
        $condition = "employee_id = :employee_id";
        return $this->db->delete($this->table_name, $condition, ['employee_id' => $employee_id]);
    }

    // Get the last error from the database
    public function getLastError()
    {
        return $this->db->getLastError();
    }

    // Add a method to alter the employee table
    public function alterEmployeeTable($query)
    {
        return $this->db->alterTable($query);
    }

    // Fetch all employees
    public function fetchAllEmployees()
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

    //Get Employee by Id
    public function fetchEmployeeById($employee_id)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE employee_id = :employee_id";
            $stmt = $this->db->prepare($query); // Use the PDO connection
            $stmt->bindParam(':employee_id', $employee_id, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return null;
        }
    }


    // Generate new employee ID
    public function generateNewEmployeeID()
    {
        try {
            $query = "SELECT MAX(CAST(SUBSTRING(employee_id, 4) AS UNSIGNED)) AS max_id FROM " . $this->table_name;
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $max_id = $result['max_id'];
            $new_id = $max_id + 1;
            $new_employee_id = 'EMP' . str_pad($new_id, 4, '0', STR_PAD_LEFT);

            return $new_employee_id;
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return null;
        }
    }
}
