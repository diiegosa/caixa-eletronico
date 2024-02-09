<?php

namespace App\Converters;

use App\Http\Requests\CreateWithdrawRequest;
use App\Models\Withdraw;

class WithdrawConverter
{
    public function requestToWithdrawModel(CreateWithdrawRequest $request): Withdraw
    {
        return new Withdraw(
            $request->saque['valor'],
            $request->saque['horario']
        );
    }
}
