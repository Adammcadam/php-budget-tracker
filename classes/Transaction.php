<?php

class Transaction
{
    public $description;
    public $amount;
    public $type;
    public $date;

    public function __construct($description, $amount, $type)
    {
        $this->description = $description;
        $this->amount = $amount;
        $this->type = strtolower($type);
        $this->date = date('Y-m-d H:i:s');
    }

    public function toArray()
    {
        return [
            'description' => $this->description,
            'amount' => $this->amount,
            'type' => $this->type,
            'date' => $this->date,
        ];
    }
}
