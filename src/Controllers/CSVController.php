<?php

namespace App\Controllers;

use App\Models\Ponto;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use League\Csv\Reader;
use League\Csv\SwapDelimiter;
use Throwable;

class CSVController
{
    public function import(Request $request, Response $response, $args)
    {
        $uploadedFiles = $request->getUploadedFiles();

        if (isset($uploadedFiles['csv_file'])) {

            $csvFile = $uploadedFiles['csv_file'];

            if ($csvFile->getError() === UPLOAD_ERR_OK) {

                $stream = $csvFile->getStream()->getMetadata('uri');

                $csv = Reader::createFromPath($stream, 'r');
                $csv->setHeaderOffset(0);
                SwapDelimiter::addTo($csv, ';');

                $records = $csv->getRecords();
                
                foreach ($records as $record) {

                    // Aceitando somente registros com status APROVADA

                    if (mb_strtoupper(trim($record['STATUS'])) !== 'APROVADA') continue;

                    $usuario = User::where('usuario_cpf', trim($record['CPF']))->first();
            
                    if (!empty($usuario)) {

                        $qtdPontos = $usuario->cargo->cargo_ponto;

                        Ponto::create([
                            'usuario_id' => $usuario->usuario_id,
                            'pontos_descricao' => trim($record['DESCRICAO']),
                            'pontos_qtd' => $qtdPontos,
                            'pontos_status' => trim($record['STATUS']),
                        ]);
                    }
                }

                $response->getBody()->write(json_encode([
                    'success' => true,
                    'message' => 'CSV importado com sucesso!'
                ]));

            } else {
                $response->getBody()->write(json_encode([
                    'success' => false,
                    'message' => 'Erro no upload do arquivo.'
                ]));
            }
        } else {
            $response->getBody()->write(json_encode([
                'success' => false,
                'message' => 'Nenhum arquivo enviado.'
            ]));
        }

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
