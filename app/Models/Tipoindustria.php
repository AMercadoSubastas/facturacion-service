<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipoindustria extends Model
{
    protected $table = 'tipoindustria';

    protected $fillable = [
        'codnum',
        'nomre',
        'activo',
    ];
}
