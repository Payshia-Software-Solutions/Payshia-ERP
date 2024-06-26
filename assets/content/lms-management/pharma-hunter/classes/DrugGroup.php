<?php
// Medicines.php
include_once 'Medicines.php';
class DrugGroup extends Medicines
{
    protected $db;
    protected $table_name = "hunter_drug_group";
    protected $lastError;

    public function __construct($db)
    {
        $this->db = $db;
    }
}
