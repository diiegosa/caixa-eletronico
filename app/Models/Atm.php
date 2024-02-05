<?php

namespace App\Models;

class Atm
{
    public function __construct(
        public bool $available,
        public int $billsOfTen,
        public int $billsOfTwenty,
        public int $billsOfFifty,
        public int $billsOfHundred
    ) {
    }
}
