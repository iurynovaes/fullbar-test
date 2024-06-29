<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ponto extends Model
{
    protected $table = 'pontos';    
    protected $primaryKey = 'pontos_id';
    public $timestamps = false;

    protected $fillable = [
        'pontos_descricao',
        'pontos_qtd',
        'pontos_status',
        'usuario_id',
        'inactivated'
    ];
}
