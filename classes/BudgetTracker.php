<?php

use League\CLImate\CLImate;

require_once 'classes/Transaction.php';
require_once('vendor/autoload.php');

class BudgetTracker
{
    private string $dataFile = 'data/transactions.json';
    private array $transactions = [];

    protected CLImate $cli;

    public function __construct()
    {
        if (file_exists($this->dataFile)) {
            $json = file_get_contents($this->dataFile);
            $this->transactions = json_decode($json, true) ?? [];
        }

        $this->cli = new CLImate();
    }

    public function addTransaction()
    {
        $this->cli->bold()->out('Add a New Transaction');

        $description = $this->cli->input('Enter a description: ')->prompt();

        // Validate numeric amount
        while (true) {
            $amountInput = $this->cli->input('Enter an amount:')->prompt();
            if (is_numeric($amountInput)) {
                $amount = (float) $amountInput;
                break;
            }
            $this->cli->error('Please enter a valid numeric amount.');
        }

        // Validate type (income/expense)
        while (true) {
            $typeInput = strtolower($this->cli->input('Enter type (income/expense):')->prompt());
            if (in_array($typeInput, ['income', 'expense'])) {
                $type = $typeInput;
                break;
            }
            $this->cli->error("Type must be either 'income' or 'expense'.");
        }

        $transaction = new Transaction($description, $amount, $type);
        $this->transactions[] = $transaction->toArray();
        $this->save();

        $this->cli->green('Transaction added successfully!');
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