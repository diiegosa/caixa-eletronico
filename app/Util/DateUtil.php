<?php

namespace App\Util;

use Carbon\Carbon;

class DateUtil
{
    public static function convertStringToCarbonDatetime(string $datetime): Carbon
    {
        return Carbon::createFromFormat('Y-m-d\TH:i:s.u\Z', $datetime);
    }
}
