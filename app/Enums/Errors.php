<?php

namespace App\Enums;

enum Errors: string
{
    case ATM_AVAILABLE = "caixa-em-uso";
    case ATM_NOT_EXISTS = "caixa-inexistente";
    case ATM_UNAVAILABLE = "caixa-indisponivel";
    case CACHE_UNAVAILABLE = "valor-indisponivel";
    case WITHDRAW_DUPLICATED = "saque-duplicado";
}
