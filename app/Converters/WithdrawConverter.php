<?php

namespace App\Converters;

use App\Http\Requests\CreateWithdrawRequest;
use App\Models\Withdraw;

class WithdrawConverter
{
    /**
     * The Portuguese language was used to adopt the proposed response
     */
    public function requestToWithdrawModel(CreateWithdrawRequest $request): Withdraw
    {
        return new Withdraw(
            $request->saque['valor'],
            $request->saque['horario']
        );
    }
}
