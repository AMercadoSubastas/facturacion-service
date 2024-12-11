<?php

namespace App\Enums;

interface ValorTipoComprobante
{
    public function valorTipoComprobante(): int;
}

enum TipoComprobanteEnum: int implements ValorTipoComprobante {

    case FACTURA_LOT_SIST_B = 200;
    case FACTURA_CONC_SIST_B = 201;
    case RECIBO_SIST_B = 300;
    case FACTURA_A_LOT = 115;
    case FACTURA_A_CONC = 125;
    case FACTURA_B_LOT = 123;
    case FACTURA_B_CONC = 124;
    case FACTURA_C = 4;

    public function valorTipoComprobante(): int{
        return match ($this) {
            self::FACTURA_A_LOT, self::FACTURA_A_CONC => 1,
            self::FACTURA_B_LOT, self::FACTURA_B_CONC => 6,
            self::FACTURA_C => 0,
            default => 0
        };
    }
}