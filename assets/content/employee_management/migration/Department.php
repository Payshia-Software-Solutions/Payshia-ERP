<?php
// Department.php

// Include the Position class
include_once 'Position.php';
class Department extends Position
{
    protected $table_name = "employee_departments"; // Override table name if needed

    public function __construct($db)
    {
        parent::__construct($db); // Call the parent constructor to initialize $db
    }

    // Department specific methods can be added here
}
