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

        // TODO:: break here if type is not income or expense

        $transaction = new Transaction($description, $amount, $type);
        $this->transactions = $transaction->toArray();
        $this->save();

        echo "Transaction added successfully!";
    }

    protected function save()
    {
        file_put_contents($this->dataFile, json_encode($this->transactions, JSON_PRETTY_PRINT));
    }
}