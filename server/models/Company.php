<?php

class Company
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all companies
    public function getAllCompanies()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `company`");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single company by ID
    public function getCompanyById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `company` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new company
    public function createCompany($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO `company` (
            `company_name`, `company_address`, `company_address2`, `company_city`, 
            `company_postalcode`, `company_email`, `company_telephone`, `company_telephone2`, 
            `owner_name`, `job_position`, `description`, `vision`, 
            `mission`, `founder_message`, `org_logo`, `founder_photo`, `website`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['company_name'],
            $data['company_address'],
            $data['company_address2'],
            $data['company_city'],
            $data['company_postalcode'],
            $data['company_email'],
            $data['company_telephone'],
            $data['company_telephone2'],
            $data['owner_name'],
            $data['job_position'],
            $data['description'],
            $data['vision'],
            $data['mission'],
            $data['founder_message'],
            $data['org_logo'],
            $data['founder_photo'],
            $data['website']
        ]);

        return $this->pdo->lastInsertId();
    }

    // Update an existing company
    public function updateCompany($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE `company` SET
            `company_name` = ?, `company_address` = ?, `company_address2` = ?, 
            `company_city` = ?, `company_postalcode` = ?, `company_email` = ?, 
            `company_telephone` = ?, `company_telephone2` = ?, `owner_name` = ?, 
            `job_position` = ?, `description` = ?, `vision` = ?, `mission` = ?, 
            `founder_message` = ?, `org_logo` = ?, `founder_photo` = ?, `website` = ?
            WHERE `id` = ?");

        $stmt->execute([
            $data['company_name'],
            $data['company_address'],
            $data['company_address2'],
            $data['company_city'],
            $data['company_postalcode'],
            $data['company_email'],
            $data['company_telephone'],
            $data['company_telephone2'],
            $data['owner_name'],
            $data['job_position'],
            $data['description'],
            $data['vision'],
            $data['mission'],
            $data['founder_message'],
            $data['org_logo'],
            $data['founder_photo'],
            $data['website'],
            $id
        ]);

        return $stmt->rowCount();
    }

    // Delete a company
    public function deleteCompany($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM `company` WHERE `id` = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>