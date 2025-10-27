<?php

require_once 'classes/Transaction.php';

class BudgetTracker
{
    private $dataFile = 'data/transactions.json';
    private $transactions = [];

    public function __construct()
    {
        if (file_exists($this->dataFile)) {
            $json = file_get_contents($this->dataFile);
            $this->transactions = json_decode($json, true) ?? [];
        }
    }

    public function addTransaction()
    {
        $description = readline('Enter a description: ');
        $amount = readline('Enter an amount: ');
        $type = readline('Enter type (income/expense): ');

        if ($type !== 'income' || $type !== 'expense') {
            echo 'Type must be of type (income/expense)';
            return;
        }

        $transaction = new Transaction($description, $amount, $type);
        $this->transactions[] = $transaction->toArray();
        $this->save();

        echo "Transaction added successfully!";
    }

    public function viewTransactions()
    {
        if (empty($this->transactions)) {
            echo "No Transactions yet.\n";
            return;
        }

        foreach ($this->transactions as $transaction) {
            echo "{$transaction['date']} - {$transaction['description']} ({$transaction['type']}) : £{$transaction['amount']}\n";
        }
    }

    public function viewSummary()
    {
        $income = 0;
        $expenses = 0;

        foreach ($this->transactions as $transaction) {
            if ($transaction['type'] === 'income') {
                $income += $transaction['amount'];
            } else {
                $expenses += $transaction['amount'];
            }
        }

        $total = $income - $expenses;

        echo "------ Summary ------\n";
        echo "Total Income: £$income\n";
        echo "Total Expenses: £$expenses\n";
        echo "Balance: £$total\n";
    }

    // TODO:: add ability to edit/delete transactions
    // TODO:: add ability to export to CSV

    protected function save()
    {
        file_put_contents($this->dataFile, json_encode($this->transactions, JSON_PRETTY_PRINT));
    }
}