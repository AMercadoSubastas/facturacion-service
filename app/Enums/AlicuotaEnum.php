<?php

namespace App\Enums;

interface ValorAlicuota
{
    public function valorAlicuota(): float;
}

enum AlicuotaEnum: int implements ValorAlicuota {

    case IVA0 = 0;
    case IVA21 = 21;
    case IVA105 = 105;

    case IVA_0 = 3;
    case IVA_21 = 5;
    case IVA_105 = 4;

    public function valorAlicuota(): float{
        return match ($this) {
            self::IVA_21 => 0.21,
            self::IVA_105 => 0.105,
            self::IVA_0 => 0,

            self::IVA21 => 21,
            self::IVA105 => 10.5,
            self::IVA0 => 0,
            default => 0
        };
    }
}