<?php

namespace App\DTOs\Output;

use App\DTOs\Entities\AtmDTO;

class AtmOutputDTO
{
    public function __construct(
        public AtmDTO|null $caixa,
        public array $erros
    ) {
    }
}
