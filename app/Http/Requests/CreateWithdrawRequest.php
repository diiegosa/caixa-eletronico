<?php

namespace App\Http\Requests;

class CreateWithdrawRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'saque.valor' => 'required|numeric',
            'saque.horario' => "required|date_format:Y-m-d\TH:i:s.\\0\\0\\0\Z",
        ];
    }
}
