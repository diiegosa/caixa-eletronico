<?php

namespace App\Services;

use App\Converters\AtmFillConverter;
use App\Converters\WithdrawConverter;
use App\DTOs\Output\AtmOutputDTO;
use App\Enums\Bills;
use App\Enums\Errors;
use App\Models\Atm;
use App\Models\Withdraw;
use App\Repositories\Interfaces\AtmRepositoryInterface;
use App\Repositories\Interfaces\WithdrawRepositoryInterface;
use App\Util\DateUtil;

class WithdrawService
{
    public function __construct(
        protected WithdrawRepositoryInterface $repository,
        protected AtmRepositoryInterface $atmRepository,
        protected WithdrawConverter $converter,
        protected AtmFillConverter $atmFillconverter,
        protected AtmFillService $atmFillService
    ) {
    }

    public function store(Withdraw $withdraw): AtmOutputDTO
    {
        $atmCached = $this->atmRepository->get();

        $errors = $this->validation($atmCached, $withdraw);

        if (!empty($errors)) {
            return $this->atmFillconverter->atmModelToAtmOutputDTO($atmCached, $errors);
        }

        $atmOutputDTO = $this->atmFillService->updateByWithdraw($atmCached, $withdraw);

        $this->repository->save($withdraw);

        return $atmOutputDTO;
    }

    private function validation(Atm|null $atmCached, Withdraw $withdraw): array
    {
        $errors = [];

        switch (true) {
            case !isset($atmCached):
                array_push($errors, Errors::ATM_NOT_EXISTS);
                break;
            case !$atmCached->available:
                array_push($errors, Errors::ATM_UNAVAILABLE);
                break;
            case $this->atmHasNotCashToWithdraw($atmCached, $withdraw):
                array_push($errors, Errors::CACHE_UNAVAILABLE);
                break;
            case $this->withdrawDuplicated($withdraw):
                array_push($errors, Errors::WITHDRAW_DUPLICATED);
                break;
        }

        return $errors;
    }

    private function atmHasNotCashToWithdraw(Atm $atm, Withdraw $withdraw): bool
    {
        return ($atm->billsOfTen * Bills::TEN->value) +
            ($atm->billsOfTwenty * Bills::TWENTY->value) +
            ($atm->billsOfFifty * Bills::FIFTY->value) +
            ($atm->billsOfHundred * Bills::HUNDRED->value) < $withdraw->cache;
    }

    /**
     * This method get all withdrawals and checks duplicity,
     * starting from the last one element.
     * If the time is greater than or equal to 10 minutes,
     * the for is breaked
     */
    private function withdrawDuplicated(Withdraw $withdrawInput): bool
    {
        $withdrawDuplicated = false;
        $minutesValidateToWithdrawDuplicated = 10;
        $withdrawInputDatetime = DateUtil::convertStringToCarbonDatetime($withdrawInput->datetime);

        $withdrawCachedList = $this->repository->getAll();


        for ($i = count($withdrawCachedList) - 1; $i >= 0; $i--) {
            $withdrawCachedDatetime = DateUtil::convertStringToCarbonDatetime($withdrawCachedList[$i]->datetime);

            if (
                $withdrawInputDatetime->diffInMinutes($withdrawCachedDatetime) < $minutesValidateToWithdrawDuplicated
            ) {
                if ($withdrawCachedList[$i]->cache == $withdrawInput->cache) {
                    $withdrawDuplicated = true;
                }
            } else {
                break;
            }
        }

        return $withdrawDuplicated;
    }
}
