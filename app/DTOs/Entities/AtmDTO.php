<?php

namespace App\DTOs\Entities;

class AtmDTO
{
    public function __construct(
        public bool $caixaDisponivel,
        public BillsDTO $notas
    ) {
    }
}
