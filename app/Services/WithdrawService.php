<?php

namespace App\Services;

use App\Converters\AtmFillConverter;
use App\Converters\WithdrawConverter;
use App\DTOs\Output\AtmOutputDTO;
use App\Enums\Bills;
use App\Enums\Errors;
use App\Models\Atm;
use App\Models\Withdraw;
use App\Util\DateUtil;

class WithdrawService
{
    public function __construct(
        protected WithdrawConverter $converter,
        protected AtmFillConverter $atmFillconverter,
        protected AtmFillService $atmFillService
    ) {
    }

    public function store(Withdraw $withdraw): AtmOutputDTO
    {
        $atmCached = Atm::get();

        $errors = $this->validation($atmCached, $withdraw);

        if (!empty($errors)) {
            return $this->atmFillconverter->atmModelToAtmOutputDTO($atmCached, $errors);
        }

        $atmOutputDTO = $this->atmFillService->updateByWithdraw($atmCached, $withdraw);

        Withdraw::save($withdraw);

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

    private function withdrawDuplicated(Withdraw $withdrawInput): bool
    {
        $minutesValidateToWithdrawDuplicated = 10;
        $withdrawInputDatetime = DateUtil::convertStringToCarbonDatetime($withdrawInput->datetime);

        $withdrawCachedList = Withdraw::getAll();


        for ($i = count($withdrawCachedList) - 1; $i >= 0; $i--) {
            $withdrawCachedDatetime = DateUtil::convertStringToCarbonDatetime($withdrawCachedList[$i]->datetime);

            if (
                $withdrawInputDatetime->diffInMinutes($withdrawCachedDatetime) < $minutesValidateToWithdrawDuplicated
                && $withdrawCachedList[$i]->cache == $withdrawInput->cache
            ) {
                return true;
            }
        }

        return false;
    }
}
