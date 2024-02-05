<?php

namespace App\Repositories\Interfaces;

use App\Models\Atm;

interface AtmRepositoryInterface
{
    public function save(Atm $atmFill): Atm;
    public function get(): Atm|null;
}
