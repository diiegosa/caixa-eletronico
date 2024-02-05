<?php

namespace App\Repositories\Interfaces;

use App\Models\Withdraw;

interface WithdrawRepositoryInterface
{
    public function save(Withdraw $withdraw): Withdraw;
    public function getAll(): array;
}
