<?php

namespace App\Http\Requests;

class CreateAtmFillRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
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
