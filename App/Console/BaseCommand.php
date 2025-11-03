<?php

namespace App\Console;

use League\CLImate\CLImate;

abstract class BaseCommand
{
    protected CLImate $cli;

    public function __construct()
    {
        $this->cli = new CLImate();
    }

    /**
     * Simple input helper with optional validation.
     */
    protected function ask(string $question, ?callable $validator = null): string
    {
        while (true) {
            $input = $this->cli->input($question)->prompt();

            if ($validator === null || $validator($input)) {
                return $input;
            }

            $this->error('Invalid input, please try again.');
        }
    }

    /**
     * Output helper methods for consistent styling.
     */
    protected function info(string $message): void
    {
        $this->cli->info($message);
    }

    protected function success(string $message): void
    {
        $this->cli->green($message);
    }

    protected function warning(string $message): void
    {
        $this->cli->yellow($message);
    }

    protected function error(string $message): void
    {
        $this->cli->error($message);
    }

    protected function header(string $message): void
    {
        $this->cli->bold()->out("\n" . $message);
        $this->cli->border();
    }

    protected function table(array $data): void
    {
        if (! empty($data)) {
            $this->cli->table($data);
        }
    }

    protected function br(int $amount = 1): void
    {
        $this->cli->br($amount);
    }
}
