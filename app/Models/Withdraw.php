<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Ramsey\Uuid\Uuid;

class Withdraw
{
    const WITHDRAW_KEY = 'WITHDRAW_KEY';

    private string $uuid;

    public function __construct(
        public int $cache,
        public string $datetime,
    ) {
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public static function save(Withdraw $withdraw): Withdraw
    {
        $withdraw->uuid = Uuid::uuid4();

        Cache::put(self::getAvailableKey(), $withdraw);

        return $withdraw;
    }

    public static function getAll(): array
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

    private static function getAvailableKey(): string
    {
        $availableKey = self::WITHDRAW_KEY;
        $withdraw = self::getFirst();

        if (!isset($withdraw)) {
            return $availableKey;
        }

        do {
            $availableKey = $withdraw->getUuid();
            $withdraw = Cache::get($availableKey);
        } while (isset($withdraw));


        return $availableKey;
    }

    private static function getFirst(): Withdraw|null
    {
        return Cache::get(self::WITHDRAW_KEY);
    }
}
