<?php
class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;
    private $lastError;

    public function __construct($config_file)
    {
        $this->loadConfig($config_file);
    }

    // Load configuration from a file
    private function loadConfig($config_file)
    {
        if (!file_exists($config_file)) {
            throw new Exception("Configuration file not found.");
        }

        $config = parse_ini_file($config_file);

        if ($config === false) {
            throw new Exception("Error reading configuration file.");
        }

        $this->host = $config['host'];
        $this->db_name = $config['dbname'];
        $this->username = $config['username'];
        $this->password = $config['password'];
    }

    // Get the database connection
    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            $this->lastError = "Connection error: " . $exception->getMessage();
            echo $this->lastError;
        }

        return $this->conn;
    }

    // Execute a query
    public function executeQuery($query)
    {
        try {
            $this->conn->exec($query);
            return true;
        } catch (PDOException $exception) {
            $this->lastError = "Query error: " . $exception->getMessage();
            echo $this->lastError;
            return false;
        }
    }

    // Insert a record
    public function insert($table, $data)
    {
        $keys = array_keys($data);
        $fields = implode(',', $keys);
        $placeholders = ':' . implode(',:', $keys);

        $query = "INSERT INTO $table ($fields) VALUES ($placeholders)";

        try {
            $stmt = $this->conn->prepare($query);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    // Update a record
    public function update($table, $data, $condition)
    {
        $fields = [];
        foreach ($data as $key => $val) {
            $fields[] = "$key = :$key";
        }
        $fields = implode(", ", $fields);
        $sql = "UPDATE $table SET $fields WHERE $condition";
        $stmt = $this->conn->prepare($sql);

        foreach ($data as $key => &$val) {
            $stmt->bindParam(":$key", $val);
        }

        try {
            $stmt->execute();
            return true;
        } catch (PDOException $exception) {
            $this->lastError = "Update error: " . $exception->getMessage();
            return false;
        }
    }

    // Delete a record
    public function delete($table, $condition)
    {
        $sql = "DELETE FROM $table WHERE $condition";
        try {
            $this->conn->exec($sql);
            return true;
        } catch (PDOException $exception) {
            $this->lastError = "Delete error: " . $exception->getMessage();
            return false;
        }
    }

    // Add an alterTable method
    public function alterTable($query)
    {
        try {
            $this->conn->exec($query);
            return true;
        } catch (PDOException $exception) {
            $this->lastError = "Alter table error: " . $exception->getMessage();
            return false;
        }
    }

    // Get the last error
    public function getLastError()
    {
        return $this->lastError;
    }

    public function prepare($query)
    {
        return $this->conn->prepare($query);
    }
}
