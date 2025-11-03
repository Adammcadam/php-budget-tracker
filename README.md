# PHP Budget Tracker CLI

[![PHP Version](https://img.shields.io/badge/PHP-8.3%2B-777BB4?logo=php\&logoColor=white)](https://www.php.net/)
[![Made With](https://img.shields.io/badge/Made%20with-CLImate-blue)](https://github.com/thephpleague/climate)
[![Build Status](https://img.shields.io/badge/Status-Active-success)]()

> A lightweight **command-line budget tracker** built with **PHP 8.3**, featuring a clean, styled interface powered by [League CLImate](https://github.com/thephpleague/climate).

---

## Overview

This CLI app lets you easily track your **income and expenses** directly from the terminal.
It’s simple, extendable, and completely dependency-light — ideal for small finance tracking or as a base for larger CLI tools.

---

## Features

* **Add Transactions** — log income and expenses
* **Edit Transactions** — update income and expenses
* **Delete Transactions** — delete erroneous transaction
* **Persistent Data** — stored locally as JSON
* **Colourised Output** — powered by CLImate
* **Modular Architecture** — easily extendable with new commands
* **Interactive Prompts** — replaces `readline()` with CLImate’s input system

---

## Requirements

* PHP **8.3+**
* [Composer](https://getcomposer.org/)
* A terminal (CLI access)

---

## Installation

```bash
# 1. Clone this repository
git clone https://github.com/yourusername/php-budget-tracker-cli.git
cd php-budget-tracker-cli

# 2. Install dependencies
composer install

# 3. Run the application
php index.php
```

---

## Usage Example

Here’s what it looks like in action:

```
Add a New Transaction
────────────────────────────
Enter a description: Coffee
Enter an amount: 3.50
Enter type (income/expense): expense
Transaction added successfully!
```

Transactions are automatically saved to a local JSON file (`transactions.json`).

---

## Powered by CLImate

[League CLImate](https://github.com/thephpleague/climate) makes your CLI output colorful and interactive:

```php
$this->climate->green('Transaction added successfully!');
$amount = $this->climate->input('Enter amount:')->prompt();
```

With easy access to:

* `->green()`, `->error()`, `->bold()`, etc. for color & style
* `->input()->prompt()` for interactive input
* `->border()` and `->table()` for structured formatting

---

© 2025 — Developed by *Adam Wragg*
