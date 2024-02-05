<?php

namespace App\Repositories;

use App\Models\Atm;
use App\Repositories\Interfaces\AtmRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class AtmRepository implements AtmRepositoryInterface
{
    const ATM_KEY = 'ATM_KEY';

    public function get(): Atm|null
    {
        return Cache::get(self::ATM_KEY);
    }

    public function save(Atm $atmFill): Atm
    {
        Cache::put(self::ATM_KEY, $atmFill);

        return $atmFill;
    }
}
