<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Remates;

class Lotes extends Model
{
    public $timestamps = false;
    
    protected $table = 'lotes';
    protected $primaryKey = 'codnum';

    protected $fillable = [
        'codnum',
        'codrem',
        'codcli',
        'codrubro',
        'estado',
        'moneda',
        'preciobase',
        'preciofinal',
        'comiscobr',
        'comispag',
        'comprador',
        'ivari',
        'ivarni',
        'codimpadic',
        'impadic',
        'descripcion',
        'descor',
        'observ',
        'usuario',
        'fecalta',
        'secuencia',
        'codintlote',
        'codintnum',
        'codintsublote',
        'dir_secuencia',
        'usuarioultmod',
        'fecultmod'
    ];

    public function codrem()
    {
        return $this->hasOne(Remates::class, 'ncomp', 'codrem');
    }

    
}
