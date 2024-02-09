<?php

namespace App\DTOs\Entities;

class BillsDTO
{
    public function __construct(
        public int $notasDez,
        public int $notasVinte,
        public int $notasCinquenta,
        public int $notasCem
    ) {
    }
}
