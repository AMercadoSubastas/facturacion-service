<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detrecibo extends Model
{
    protected $table = 'detrecibo';

    protected $fillable = [
        'codnum',
        'tcomp',
        'serie',
        'ncomp',
        'nreng',
        'tcomprel',
        'serierel',
        'ncomprel',
        'netocbterel',
        'usuario',
        'fechahora'
    ];
}
