<?php

namespace App\Services;

use App\Converters\AtmFillConverter;
use App\DTOs\Output\AtmOutputDTO;
use App\Enums\Bills;
use App\Enums\Errors;
use App\Models\Atm;
use App\Models\Withdraw;
use App\Repositories\Interfaces\AtmRepositoryInterface;

class AtmFillService
{
    public function __construct(
        protected AtmRepositoryInterface $repository,
        protected AtmFillConverter $converter
    ) {
    }

    public function store(Atm $atmRequest): AtmOutputDTO
    {
        $errors = [];
        $atmReturn = null;

        $atmCached = $this->repository->get();

        if (isset($atmCached) && $atmCached->available) {
            array_push($errors, Errors::ATM_AVAILABLE);
            $atmReturn = $atmCached;
        } else {
            $atmReturn = $this->repository->save($atmRequest);
        }

        return $this->converter->atmModelToAtmOutputDTO($atmReturn, $errors);
    }

    /**
     * Calculates how many notes will be withdrawn
     */
    public function updateByWithdraw(Atm $atmCached, Withdraw $withdraw): AtmOutputDTO
    {
        $totalBillsOfHundred = 0;
        $totalBillsOfFifty = 0;
        $totalBillsOfTwenty = 0;
        $totalBillsOfTen = 0;
        $withdrawCache = $withdraw->cache;

        $totalBillsOfHundred = $this->calculateBills($withdrawCache, $atmCached->billsOfHundred, Bills::HUNDRED->value);
        $withdrawCache = $withdrawCache - $totalBillsOfHundred * Bills::HUNDRED->value;

        $totalBillsOfFifty = $this->calculateBills($withdrawCache, $atmCached->billsOfFifty, Bills::FIFTY->value);
        $withdrawCache = $withdrawCache - $totalBillsOfFifty * Bills::FIFTY->value;

        $totalBillsOfTwenty = $this->calculateBills($withdrawCache, $atmCached->billsOfTwenty, Bills::TWENTY->value);
        $withdrawCache = $withdrawCache - $totalBillsOfTwenty * Bills::TWENTY->value;

        $totalBillsOfTen = $this->calculateBills($withdrawCache, $atmCached->billsOfTen, Bills::TEN->value);
        $withdrawCache = $withdrawCache - $totalBillsOfTen * Bills::TEN->value;

        $atmCached->billsOfHundred = $atmCached->billsOfHundred - $totalBillsOfHundred;
        $atmCached->billsOfFifty = $atmCached->billsOfFifty - $totalBillsOfFifty;
        $atmCached->billsOfTwenty = $atmCached->billsOfTwenty - $totalBillsOfTwenty;
        $atmCached->billsOfTen = $atmCached->billsOfTen - $totalBillsOfTen;

        return $this->converter->atmModelToAtmOutputDTO($this->repository->save($atmCached));
    }

    private function calculateBills(int $withdrawCache, int $atmCachedBills, int $billValue): int
    {
        $totalBills = intval($withdrawCache / $billValue);
        if ($totalBills > $atmCachedBills) {
            $totalBills = $atmCachedBills;
        }

        return $totalBills;
    }
}
