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

        $rows = [];
        foreach ($this->transactions as $i => $t) {
            $rows[] = [
                '#' => $i + 1,
                'Description' => $t['description'],
                'Amount (£)' => ($t['type'] === 'expense' ? '-' : '+') . number_format($t['amount'], 2),
                'Type' => ucfirst($t['type']),
                'ID' => $t['id'],
            ];
        }

        $this->table($rows);
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

    public function editTransaction()
    {
        $index = $this->findTransaction();
        if ($index === null) {
            return; // nothing to edit
        }

        $transaction = $this->transactions[$index];
        $this->info("Leave a field blank to keep the current value.");

        $newDescription = $this->ask("Description [{$transaction['description']}]:");
        $newAmount = $this->ask("Amount [{$transaction['amount']}]:");
        $newType = $this->ask("Type (income/expense) [{$transaction['type']}]:");

        if (!empty(trim($newDescription))) {
            $transaction['description'] = $newDescription;
        }
        if (!empty(trim($newAmount)) && is_numeric($newAmount)) {
            $transaction['amount'] = (float) $newAmount;
        }
        if (!empty(trim($newType)) && in_array(strtolower($newType), ['income', 'expense'])) {
            $transaction['type'] = strtolower($newType);
        }

        $this->transactions[$index] = $transaction;
        $this->save();

        $this->success("Transaction updated successfully!");
    }

    public function deleteTransaction()
    {
        $index = $this->findTransaction('Delete');
        if ($index === null) {
            return; // nothing to delete
        }

        $confirm = strtolower($this->ask("Are you sure you want to delete this transaction? (y/n):"));
        if ($confirm !== 'y') {
            $this->info("Deletion cancelled.");
            return;
        }

        unset($this->transactions[$index]);
        $this->transactions = array_values(array_filter($this->transactions)); // clean + reindex
        $this->save();

        $this->success("Transaction deleted successfully!");
    }

    // TODO:: add ability to export to CSV

    private function findTransaction(string $mode = 'Edit'): ?int
    {
        $this->header("{$mode} a Transaction");

        if (empty($this->transactions)) {
            $this->info("No transactions available to {$mode}.");
            return null;
        }

        // Build a table-friendly array of transactions
        $rows = [];
        foreach ($this->transactions as $i => $t) {
            $rows[] = [
                '#' => $i + 1,
                'Description' => $t['description'],
                'Amount (£)' => ($t['type'] === 'expense' ? '-' : '+') . number_format($t['amount'], 2),
                'Type' => ucfirst($t['type']),
                'ID' => $t['id'],
            ];
        }

        $this->table($rows);

        $input = trim($this->ask("Enter the number or transaction ID to {$mode}:"));

        // If user entered a number, treat it as a row selection
        if (is_numeric($input)) {
            $index = (int) $input - 1;
            if (!isset($this->transactions[$index])) {
                $this->error("Invalid selection number.");
                return null;
            }
            return $index;
        }

        // Otherwise, treat input as an ID
        $index = array_search($input, array_column($this->transactions, 'id'), true);

        if ($index === false || !isset($this->transactions[$index])) {
            $this->error("Transaction not found.");
            return null;
        }

        return $index;
    }


    protected function save()
    {
        file_put_contents($this->dataFile, json_encode($this->transactions, JSON_PRETTY_PRINT));
    }
}