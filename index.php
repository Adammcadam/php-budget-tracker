<?php

use App\Console\BudgetTracker;
use League\CLImate\CLImate;

require_once 'App/Console/BudgetTracker.php';
require_once('vendor/autoload.php');

$tracker = new BudgetTracker();
$cli = new CLImate();

while (true) {
    // TODO:: maybe only show edit when transactions exist. Make the list dynamic
    $cli->underline("Budget Tracker")->br();
    $cli->bold("1. Add Transaction");
    $cli->bold("2. Edit Transaction");
    $cli->bold("3. Delete Transaction");
    $cli->bold("4. View Transactions");
    $cli->bold("5. View Summary");
    $cli->bold("6. Exit")->br();

    $choice = $cli->input("choose an option: ")->prompt();

    switch ($choice) {
        case 1:
            $tracker->addTransaction();
            break;
        case 2:
            $tracker->editTransaction();
            break;
        case 3:
            $tracker->deleteTransaction();
            break;
        case 4:
            $tracker->viewTransactions();
            break;
        case 5:
            $tracker->viewSummary();
            break;
        case 6:
            $cli->info("bye friend!");
            exit();
        default:
            $cli->error("That's an invalid choice. Try Again")->br();
    }
}