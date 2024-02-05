<?php

namespace App\DTOs\Entities;

class BillsDTO
{
    /**
     * The Portuguese language was used to adopt the proposed request
     */
    public function __construct(
        public int $notasDez,
        public int $notasVinte,
        public int $notasCinquenta,
        public int $notasCem
    ) {
    }
}
