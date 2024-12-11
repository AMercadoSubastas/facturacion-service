<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Awobaz\Compoships\Compoships;

use App\Models\Lotes;

class Detfac extends Model
{
    protected $table = 'detfac';
    use Compoships;

    protected $fillable = [
        'codnum',
        'tcomp',
        'serie',
        'ncomp',
        'nreng',
        'codrem',
        'codlote',
        'descrip',
        'neto',
        'bruto',
        'iva',
        'imp',
        'comcob',
        'compag',
        'fechahora',
        'usuario',
        'porciva',
        'tieneresol',
        'concafac',
        'tcomsal',
        'seriesal',
        'ncompsal',
    ];

    public function codlote()
    {
        return $this->hasOne(Lotes::class, 'codlote', 'codnum');
    }
}
