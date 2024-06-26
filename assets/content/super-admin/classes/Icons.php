<?php
include_once 'Pages.php';
class Icons extends Pages
{
    protected $table_name = "icons_pack"; // Override table name if needed

    public function __construct($db)
    {
        parent::__construct($db); // Call the parent constructor to initialize $db
    }
}
