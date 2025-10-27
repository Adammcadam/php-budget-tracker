<?php

require_once 'classes/BudgetTracker.php';

$tracker = new BudgetTracker();

while (true) {
    echo "\n------ Budget Tracker ------\n";
    echo "1. Add Transaction\n";
    echo "2. View Transactions\n";
    echo "3. View Summary\n";
    echo "4. Exit\n";

    $choice = readline("Choose an option: ");

    switch ($choice) {
        case 1:
            $tracker->addTransaction();
            break;
        case 2:
            $tracker->viewTransactions();
            break;
        case 3:
            // TODO:: view summary
        case 4:
            exit("bye friend!\n");
        default:
            echo "That's an invalid choice. Try Again\n";
    }
}