<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLevel extends Model
{
    use HasFactory;

    protected $table = 'userlevels';

    protected $primaryKey = 'userlevelid';

    protected $fillable = [
        'userlevelname',
    ];

    /**
     * Relación con User: un nivel tiene muchos usuarios.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class, 'nivel', 'userlevelid');
    }
}
