<?php
// Medicines.php
include_once 'Medicines.php';
class Rack extends Medicines
{
    protected $db;
    protected $table_name = "hunter_category";
    protected $lastError;

    public function __construct($db)
    {
        $this->db = $db;
    }
}
