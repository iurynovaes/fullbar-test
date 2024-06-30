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
                return ceil($pontos * SUPERVISOR_LOJA_BONUS); // Ganha 35% em relação ao total de pontos
            
            case 'GERENTE_REGIONAL':
                return ceil(ceil($pontos * SUPERVISOR_LOJA_BONUS) * GERENTE_REGIONAL_BONUS); // Ganha 20% em relação aos pontos do Supervisor
        }
    }
}
