<?php

namespace App\Http\Requests;

class CreateAtmFillRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'caixa.caixaDisponivel' => 'required|boolean',
            'caixa.notas.notasDez' => 'required|numeric',
            'caixa.notas.notasVinte' => 'required|numeric',
            'caixa.notas.notasCinquenta' => 'required|numeric',
            'caixa.notas.notasCem' => 'required|numeric'
        ];
    }
}
