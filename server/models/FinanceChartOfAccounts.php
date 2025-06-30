<?php

class FinanceChartOfAccounts
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all accounts
    public function getAllAccounts()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM finance_chart_of_accounts ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single account by ID
    public function getAccountById($account_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM finance_chart_of_accounts WHERE account_id = ?");
        $stmt->execute([$account_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new account
    public function createAccount($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO finance_chart_of_accounts (account_name, account_type, created_by, created_at)
                                     VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['account_name'],
            $data['account_type'],
            $data['created_by'],
            $data['created_at']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update an existing account
    public function updateAccount($account_id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE finance_chart_of_accounts SET 
                                     account_name = ?, 
                                     account_type = ?, 
                                     created_by = ? 
                                     WHERE account_id = ?");
        $stmt->execute([
            $data['account_name'],
            $data['account_type'],
            $data['created_by'],
            $account_id
        ]);
        return $stmt->rowCount();
    }

    // Delete an account
    public function deleteAccount($account_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM finance_chart_of_accounts WHERE account_id = ?");
        $stmt->execute([$account_id]);
        return $stmt->rowCount();
    }
}
?>