<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;

class Withdraw
{
    private string $uuid;

    public function __construct(
        public int $cache,
        public string $datetime,
    ) {
    }

    public function generateUUid()
    {
        $this->uuid = Uuid::uuid4();
    }

    public function getUuid()
    {
        return $this->uuid;
    }
}
