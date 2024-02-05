<?php

namespace App\Http\Requests;

class CreateWithdrawRequest extends BaseRequest
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
            'saque.valor' => 'required|numeric',
            'saque.horario' => "required|date_format:Y-m-d\TH:i:s.\\0\\0\\0\Z",
        ];
    }
}
