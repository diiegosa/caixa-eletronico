<?php

namespace App\Adapters;

use App\DTOs\Output\AtmOutputDTO;
use Illuminate\Http\JsonResponse;

class ApiResponseAdapter
{
    public static function getJsonResponse(AtmOutputDTO $atmOutputDTO): JsonResponse
    {
        return response()->json(
            $atmOutputDTO,
            empty($atmOutputDTO->erros) ? 200 : 400
        );
    }
}
