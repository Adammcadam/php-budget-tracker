<?php

namespace App\Model;

class Transaction
{
    public string $description;
    public int $amount;
    public string $type;
    public string $date;

    public function __construct($description, $amount, $type)
    {
        $this->description = $description;
        $this->amount = $amount;
        $this->type = strtolower($type);
        $this->date = date('Y-m-d H:i:s');
    }

    public function toArray(): array
    {
        return [
            'description' => $this->description,
            'amount' => $this->amount,
            'type' => $this->type,
            'date' => $this->date,
        ];
    }
}
