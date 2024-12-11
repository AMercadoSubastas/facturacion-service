<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipoivas extends Model
{
    protected $table = 'tipoiva';

    protected $fillable = [
        'codnum',
        'descor',
        'descrip',
        'discrimina',
    ];
}
