<?php

namespace App\Enums;

enum SerieEnum: int {

    case FACTURA_SERIE_SISTEMA_B = 200;
    case FACTURA_A_SERIE = 52; // FC LOT Y CONC, NC Y ND A
    case FACTURA_B_SERIE = 53; // FC LOT Y CONC, NC Y ND B
    case FACTURA_C_SERIE = 4;
    case FACTURA_A5_SERIE = 5; //MIPYME
}