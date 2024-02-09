<?php

namespace App\Converters;

use App\DTOs\Entities\AtmDTO;
use App\DTOs\Entities\BillsDTO;
use App\DTOs\Output\AtmOutputDTO;
use App\Http\Requests\CreateAtmFillRequest;
use App\Models\Atm;

class AtmFillConverter
{
    public function requestToAtmModel(CreateAtmFillRequest $request): Atm
    {
        return new Atm(
            $request->caixa['caixaDisponivel'],
            $request->caixa['notas']['notasDez'],
            $request->caixa['notas']['notasVinte'],
            $request->caixa['notas']['notasCinquenta'],
            $request->caixa['notas']['notasCem']
        );
    }

    public function atmModelToAtmOutputDTO(Atm|null $atmModel, array $errors = []): AtmOutputDTO
    {
        $atmDTO = null;

        if (isset($atmModel)) {
            $atmDTO = new AtmDTO(
                $atmModel->available,
                new BillsDTO(
                    $atmModel->billsOfTen,
                    $atmModel->billsOfTwenty,
                    $atmModel->billsOfFifty,
                    $atmModel->billsOfHundred,
                )
            );
        }

        return new AtmOutputDTO($atmDTO, $errors);
    }
}
