<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

class Atm
{
    const ATM_KEY = 'ATM_KEY';

    public function __construct(
        public bool $available,
        public int $billsOfTen,
        public int $billsOfTwenty,
        public int $billsOfFifty,
        public int $billsOfHundred
    ) {
    }

    public static function get(): Atm|null
    {
        return Cache::get(self::ATM_KEY);
    }

    public static function save(Atm $atmFill): Atm
    {
        Cache::put(self::ATM_KEY, $atmFill);

        return $atmFill;
    }
}
