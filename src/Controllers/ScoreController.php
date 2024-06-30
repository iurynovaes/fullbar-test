<?php

namespace App\Controllers;

use App\Models\Ponto;
use App\Models\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ScoreController
{
    public function aplicarBonus(Request $request, Response $response, $args)
    {
        $cargosBonus = CARGOS_BONUS;

        $pontos = Ponto::selectRaw('SUM(pontos.pontos_qtd) as tot_pontos')
            ->join('usuario', 'usuario.usuario_id', '=', 'pontos.usuario_id')
            ->join('cargo', 'cargo.cargo_id', '=', 'usuario.cargo_id')
            ->whereNotIn('cargo.cargo_label', $cargosBonus)
            ->first();
        
        $usuarios = User::join('cargo', 'cargo.cargo_id', '=', 'usuario.cargo_id')
            ->whereIn('cargo.cargo_label', $cargosBonus)
            ->get();

        foreach ($usuarios as $u) {

            $bonus = $u->cargo->aplicarBonus((int) $pontos->tot_pontos);

            Ponto::create([
                'usuario_id' => $u->usuario_id,
                'pontos_descricao' => 'BONUS',
                'pontos_qtd' => $bonus,
                'pontos_status' => 'APROVADA',
            ]);
        }

        $response->getBody()->write(json_encode([
            'success' => true,
            'message' => 'Aplicação de bônus realizada com sucesso!'
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);        
    }
}
