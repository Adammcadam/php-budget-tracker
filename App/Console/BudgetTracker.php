<?php

namespace App\Console;

use App\Console\BaseCommand;
use App\Model\Transaction;

require_once __DIR__ . '/BaseCommand.php';
require_once __DIR__ . '/../Model/Transaction.php';

require_once('vendor/autoload.php');

class BudgetTracker extends BaseCommand
{
    private string $dataFile = 'data/transactions.json';
    private array $transactions = [];

    public function __construct()
    {
        parent::__construct();
        if (file_exists($this->dataFile)) {
            $json = file_get_contents($this->dataFile);
            $this->transactions = json_decode($json, true) ?? [];
        }
    }

    public function addTransaction(): void
    {
        $this->header("Add a New Transaction");

        $description = $this->ask("Enter a description:", fn($d) => trim($d) !== "");

        $amount = $this->ask(
            "Enter an amount:",
            fn($a) => is_numeric($a)
        );
        $amount = (float) $amount;

        $type = strtolower($this->ask(
            "Enter type (income/expense):",
            fn($t) => in_array(strtolower($t), ["income", "expense"])
        ));

        $transaction = new Transaction($description, $amount, $type);
        $this->transactions[] = $transaction->toArray();
        $this->save();

        $this->success("Transaction added successfully!");
    }

    public function viewTransactions()
    {
        if (empty($this->transactions)) {
            $this->info("No transactions as yet...");
        }

        $this->table($this->transactions);
    }

    public function viewSummary()
    {
        $income = 0;
        $expenses = 0;

        foreach ($this->transactions as $transaction) {
            if ($transaction["type"] === "income") {
                $income += $transaction["amount"];
            } else {
                $expenses += $transaction["amount"];
            }
        }

        $total = $income - $expenses;

        $this->header("Summary");
        $this->table([
            [
                "Total Income" => "£{$income}",
                "Total Expenses" => "£{$expenses}",
                "Balance" => "£{$total}"
            ]
        ]);
        $this->br();
    }

    // TODO:: add ability to edit/delete transactions
    // TODO:: add ability to export to CSV

    protected function save()
    {
        file_put_contents($this->dataFile, json_encode($this->transactions, JSON_PRETTY_PRINT));
    }
}