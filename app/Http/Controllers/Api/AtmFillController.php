<?php

namespace App\Http\Controllers\Api;

use App\Adapters\ApiResponseAdapter;
use App\Converters\AtmFillConverter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAtmFillRequest;
use App\Services\AtmFillService;
use Illuminate\Http\JsonResponse;

class AtmFillController extends Controller
{

    public function __construct(
        protected AtmFillService $service,
        protected AtmFillConverter $converter
    ) {
    }

    public function store(CreateAtmFillRequest $request): JsonResponse
    {
        $atm = $this->converter->requestToAtmModel($request);
        return ApiResponseAdapter::getJsonResponse($this->service->store($atm));
    }
}
