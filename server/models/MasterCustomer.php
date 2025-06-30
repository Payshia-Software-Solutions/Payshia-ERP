<?php

class Customer
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllCustomers(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM master_customer WHERE is_active = 1 ORDER BY customer_id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCustomerById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM master_customer WHERE customer_id = ?");
        $stmt->execute([$id]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        return $customer ?: null;
    }

    public function createCustomer(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO master_customer (
                customer_first_name, customer_last_name, phone_number, address_line1, address_line2,
                city_id, email_address, opening_balance, created_by, created_at,
                company_id, location_id, is_active, credit_limit, credit_days,
                region_id, route_id, area_id
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['customer_first_name'],
            $data['customer_last_name'],
            $data['phone_number'],
            $data['address_line1'],
            $data['address_line2'],
            $data['city_id'],
            $data['email_address'] ?? null,
            $data['opening_balance'],
            $data['created_by'],
            $data['created_at'],
            $data['company_id'],
            $data['location_id'],
            $data['is_active'],
            $data['credit_limit'],
            $data['credit_days'],
            $data['region_id'],
            $data['route_id'],
            $data['area_id']
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function updateCustomer(int $id, array $data): int
    {
        $stmt = $this->pdo->prepare("
            UPDATE master_customer SET
                customer_first_name = ?,
                customer_last_name = ?,
                phone_number = ?,
                address_line1 = ?,
                address_line2 = ?,
                city_id = ?,
                email_address = ?,
                opening_balance = ?,
                created_by = ?,
                created_at = ?,
                company_id = ?,
                location_id = ?,
                is_active = ?,
                credit_limit = ?,
                credit_days = ?,
                region_id = ?,
                route_id = ?,
                area_id = ?
            WHERE customer_id = ?
        ");
        $stmt->execute([
            $data['customer_first_name'],
            $data['customer_last_name'],
            $data['phone_number'],
            $data['address_line1'],
            $data['address_line2'],
            $data['city_id'],
            $data['email_address'] ?? null,
            $data['opening_balance'],
            $data['created_by'],
            $data['created_at'],
            $data['company_id'],
            $data['location_id'],
            $data['is_active'],
            $data['credit_limit'],
            $data['credit_days'],
            $data['region_id'],
            $data['route_id'],
            $data['area_id'],
            $id
        ]);
        return $stmt->rowCount();
    }

    public function deleteCustomer(int $id): int
    {
        $stmt = $this->pdo->prepare("DELETE FROM master_customer WHERE customer_id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>