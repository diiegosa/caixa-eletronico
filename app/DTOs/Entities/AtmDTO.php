<?php

namespace App\DTOs\Entities;

class AtmDTO
{
    /**
     * The Portuguese language was used to adopt the proposed request
     */
    public function __construct(
        public bool $caixaDisponivel,
        public BillsDTO $notas
    ) {
    }
}
