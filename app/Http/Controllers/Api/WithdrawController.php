<?php

namespace App\Http\Controllers\Api;

use App\Adapters\ApiResponseAdapter;
use App\Converters\WithdrawConverter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateWithdrawRequest;
use App\Services\WithdrawService;

class WithdrawController extends Controller
{
    public function __construct(
        protected WithdrawService $service,
        protected WithdrawConverter $converter,
    ) {
    }

    public function store(CreateWithdrawRequest $request)
    {
        $withdraw = $this->converter->requestToWithdrawModel($request);
        return ApiResponseAdapter::getJsonResponse($this->service->store($withdraw));
    }
}
