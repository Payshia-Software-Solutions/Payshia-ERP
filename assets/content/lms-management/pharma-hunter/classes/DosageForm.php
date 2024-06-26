<?php
// Medicines.php
include_once 'Medicines.php';
class DosageForm extends Medicines
{
    protected $db;
    protected $table_name = "hunter_dosage";
    protected $lastError;

    public function __construct($db)
    {
        $this->db = $db;
    }
}
