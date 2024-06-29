<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Model
{
    protected $table = 'usuario';
    protected $primaryKey = 'usuario_id';
    public $timestamps = false;

    protected $fillable = [
        'cargo_id',
        'usuario_nome',
        'usuario_cpf',
        'inactivated'
    ];

    public function cargo(): BelongsTo {
        return $this->belongsTo(Cargo::class, 'cargo_id', 'cargo_id');
    }

    public function pontos(): HasMany {
        return $this->hasMany(Ponto::class, 'usuario_id', 'usuario_id');
    }
}
