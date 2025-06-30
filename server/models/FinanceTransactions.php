<?php

class FinanceTransactions
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Get all transactions
    public function getAllTransactions()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM finance_transactions ORDER BY transaction_date DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single transaction by ID
    public function getTransactionById($transaction_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM finance_transactions WHERE transaction_id = ?");
        $stmt->execute([$transaction_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new transaction
    public function createTransaction($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO finance_transactions 
            (debit_account_id, credit_account_id, amount, transaction_date, description, ref_key, location_id, created_by, timestamp)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['debit_account_id'],
            $data['credit_account_id'],
            $data['amount'],
            $data['transaction_date'],
            $data['description'],
            $data['ref_key'],
            $data['location_id'],
            $data['created_by'],
            $data['timestamp']
        ]);
        return $this->pdo->lastInsertId();
    }

    // Update an existing transaction
    public function updateTransaction($transaction_id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE finance_transactions SET 
            debit_account_id = ?, 
            credit_account_id = ?, 
            amount = ?, 
            transaction_date = ?, 
            description = ?, 
            ref_key = ?, 
            location_id = ?, 
            created_by = ?, 
            timestamp = ?
            WHERE transaction_id = ?");
        $stmt->execute([
            $data['debit_account_id'],
            $data['credit_account_id'],
            $data['amount'],
            $data['transaction_date'],
            $data['description'],
            $data['ref_key'],
            $data['location_id'],
            $data['created_by'],
            $data['timestamp'],
            $transaction_id
        ]);
        return $stmt->rowCount();
    }

    // Delete a transaction
    public function deleteTransaction($transaction_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM finance_transactions WHERE transaction_id = ?");
        $stmt->execute([$transaction_id]);
        return $stmt->rowCount();
    }
}
?>