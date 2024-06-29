<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cargo extends Model
{
    protected $table = 'cargo';
    protected $primaryKey = 'cargo_id';
    public $timestamps = false;

    protected $fillable = [
        'cargo_nome',
        'cargo_label',
        'cargo_ponto',
        'inactivated'
    ];

    public function usuarios(): HasMany {
        return $this->hasMany(User::class, 'cargo_id', 'cargo_id');
    }

    public function aplicarBonus(int $pontos): int {

        switch ($this->cargo_label) {
            case 'SUPERVISOR_LOJA':
                return ceil($pontos * 0.35); // Ganha 35% em relação ao total de pontos
            
            case 'GERENTE_REGIONAL':
                return ceil(ceil($pontos * 0.35) * 0.20); // Ganha 20% em relação aos pontos do Supervisor
        }
    }
}
