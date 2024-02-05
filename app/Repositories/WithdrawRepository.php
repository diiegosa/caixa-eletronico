<?php

namespace App\Repositories;

use App\Models\Withdraw;
use App\Repositories\Interfaces\WithdrawRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class WithdrawRepository implements WithdrawRepositoryInterface
{
    const WITHDRAW_KEY = 'WITHDRAW_KEY';

    public function save(Withdraw $withdraw): Withdraw
    {
        $withdraw->generateUUid();
        Cache::put($this->getAvailableKey(), $withdraw);

        return $withdraw;
    }

    public function getAll(): array
    {
        $withdrawList = [];
        $withdraw = Cache::get(self::WITHDRAW_KEY);

        if (!isset($withdraw)) {
            return $withdrawList;
        }

        do {
            array_push($withdrawList, $withdraw);
            $withdraw = Cache::get($withdraw->getUuid());
        } while (isset($withdraw));

        return $withdrawList;
    }

    /**
     * Linked list: The available key is the uuid of the last withdrawal
     */
    private function getAvailableKey(): string
    {
        $availableKey = self::WITHDRAW_KEY;
        $withdraw = $this->getFirst();

        if (!isset($withdraw)) {
            return $availableKey;
        }

        do {
            $availableKey = $withdraw->getUuid();
            $withdraw = Cache::get($availableKey);
        } while (isset($withdraw));


        return $availableKey;
    }

    private function getFirst(): Withdraw|null
    {
        return Cache::get(self::WITHDRAW_KEY);
    }
}
