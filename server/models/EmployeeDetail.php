<?php

class EmployeeDetail
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM employee_details WHERE is_active = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM employee_details WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO employee_details (
                    id, phone_number, national_identification_number, date_of_birth, gender,
                    married_status, address_line_1, address_line_2, city,
                    permanent_address_line_1, permanent_address_line_2, permanent_city,
                    employee_id, date_of_hire, employee_type, work_location, department, position,
                    nic, cover_image, grama_niladhari_certificate, police_certificate,
                    created_at, updated_at, created_by, email, is_active
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['id'],
            $data['phone_number'],
            $data['national_identification_number'],
            $data['date_of_birth'],
            $data['gender'],
            $data['married_status'],
            $data['address_line_1'],
            $data['address_line_2'],
            $data['city'],
            $data['permanent_address_line_1'],
            $data['permanent_address_line_2'],
            $data['permanent_city'],
            $data['employee_id'],
            $data['date_of_hire'],
            $data['employee_type'],
            $data['work_location'],
            $data['department'],
            $data['position'],
            $data['nic'],
            $data['cover_image'],
            $data['grama_niladhari_certificate'],
            $data['police_certificate'],
            $data['created_at'],
            $data['updated_at'],
            $data['created_by'],
            $data['email'],
            $data['is_active']
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE employee_details SET
                    phone_number = ?, national_identification_number = ?, date_of_birth = ?, gender = ?,
                    married_status = ?, address_line_1 = ?, address_line_2 = ?, city = ?,
                    permanent_address_line_1 = ?, permanent_address_line_2 = ?, permanent_city = ?,
                    employee_id = ?, date_of_hire = ?, employee_type = ?, work_location = ?, department = ?, position = ?,
                    nic = ?, cover_image = ?, grama_niladhari_certificate = ?, police_certificate = ?,
                    updated_at = ?, created_by = ?, email = ?, is_active = ?
                WHERE id = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $data['phone_number'],
            $data['national_identification_number'],
            $data['date_of_birth'],
            $data['gender'],
            $data['married_status'],
            $data['address_line_1'],
            $data['address_line_2'],
            $data['city'],
            $data['permanent_address_line_1'],
            $data['permanent_address_line_2'],
            $data['permanent_city'],
            $data['employee_id'],
            $data['date_of_hire'],
            $data['employee_type'],
            $data['work_location'],
            $data['department'],
            $data['position'],
            $data['nic'],
            $data['cover_image'],
            $data['grama_niladhari_certificate'],
            $data['police_certificate'],
            $data['updated_at'],
            $data['created_by'],
            $data['email'],
            $data['is_active'],
            $id
        ]);
        return $stmt->rowCount();
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM employee_details WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
