<?php

use App\Console\BudgetTracker;
use League\CLImate\CLImate;

require_once 'App/Console/BudgetTracker.php';
require_once('vendor/autoload.php');

$tracker = new BudgetTracker();
$cli = new CLImate();

while (true) {
    $cli->underline("Budget Tracker")->br();
    $cli->bold("1. Add Transaction");
    $cli->bold("2. View Transactions");
    $cli->bold("3. View Summary");
    $cli->bold("4. Exit")->br();

    $choice = $cli->input("choose an option: ")->prompt();

    switch ($choice) {
        case 1:
            $tracker->addTransaction();
            break;
        case 2:
            $tracker->viewTransactions();
            break;
        case 3:
            $tracker->viewSummary();
            break;
        case 4:
            $cli->info("bye friend!");
            exit();
        default:
            $cli->error("That's an invalid choice. Try Again")->br();
    }
}