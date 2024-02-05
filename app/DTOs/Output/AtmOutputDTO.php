<?php

namespace App\DTOs\Output;

use App\DTOs\Entities\AtmDTO;

class AtmOutputDTO
{
    /**
     * The Portuguese language was used to adopt the proposed request
     */
    public function __construct(
        public AtmDTO|null $caixa,
        public array $erros
    ) {
    }
}
