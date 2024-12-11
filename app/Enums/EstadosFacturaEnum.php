<?php

namespace App\Enums;

enum EstadosFacturaEnum: string
{
    case PENDIENTE = 'P';
    case APROBADO = 'A';
    case CANCELADA = 'C';
    case SALDADA = 'S';
    case ANULADANC = 'ANC';
    case LEGALES = 'L';
    case VENCIDA = 'V';

    case NRDOCB = "00000-00000";
}

