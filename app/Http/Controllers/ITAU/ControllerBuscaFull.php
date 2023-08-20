<?php

namespace App\Http\Controllers\ITAU;


use App\Http\Controllers\ClassGlobais\ClassGenerica;
use App\Http\Controllers\ClassGlobais\ControllerMaster;
use App\Http\Controllers\Controller;
use App\Models\CobrancaAlteracoes;
use App\Models\CobrancaTitulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ControllerBuscaFull extends Controller
{

    /**
     * Arquivo: ControllerBuscaFull.php
     * Autor: Rubens do Santos
     * Contato: salvadorbba@gmail.com
     * Método para atualizar a data de vencimento de um boleto no sistema ITAU.
     *
     * @param Request $request Os dados da requisição contendo o ID do boleto e a nova data de vencimento.
     * @return \Illuminate\Http\JsonResponse Resposta JSON contendo o resultado da operação de atualização.
     */

    public function index(Request $request)
    {

        try {

            $obj = (object) ControllerMaster::GetCreate($request->id);


            $x_itau_correlationID = ClassGenerica::CreateUuid(1);
            $x_itau_flowID = ClassGenerica::CreateUuid(2);

            $id_beneficiario = $obj->Parametros->id_beneficiario;
            $nosso_numero = $obj->boleto->seunumero;

            $url = 'https://secure.api.cloud.itau.com.br/boletoscash/v2/boletos?id_beneficiario=' . $id_beneficiario . '&codigo_carteira=109&nosso_numero=' .  $nosso_numero . '&view=specific';


            $headers = [
                'x-itau-apikey: ' . $obj->client_id,
                'x-itau-correlationID: ' . $x_itau_correlationID,
                'x-itau-flowID: ' . $x_itau_flowID,
                'Content-Type: application/json',
                'Authorization: Bearer ' . $obj->token,
            ];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSLCERTTYPE => 'P12',
                CURLOPT_SSLCERT => $obj->certificado,
                CURLOPT_SSLCERTPASSWD => $obj->senha,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',

                CURLOPT_HTTPHEADER => $headers,
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $response = json_decode($response);

            $tituloExistente = CobrancaAlteracoes::where("cobranca_titulo_id", $obj->boleto->id)->first();
            $historicos = $response->data[0]->dado_boleto->historico;

            if (!empty($historicos)) {
                $numHistoricos = 0; // Inicializa a variável fora do loop

                foreach ($historicos as $lista) {
                    // Faz a verificação de existência baseada em todos os campos relevantes
                    $tituloExistente = CobrancaAlteracoes::where([
                        "cobranca_titulo_id" => $obj->boleto->id,
                        "data" => $lista->data  ?? null,
                        "operacao" => $lista->operacao  ?? null,
                        "comandado_por" => $lista->comandado_por  ?? null,
                        "conteudo_anterior" => $lista->conteudo_anterior ?? null,
                        "conteudo_atual" => $lista->conteudo_atual  ?? null
                    ])->first();

                    if (!$tituloExistente) {
                        $cobrancaAlteracao = new CobrancaAlteracoes();
                        $cobrancaAlteracao->cobranca_titulo_id = $obj->boleto->id;
                        $cobrancaAlteracao->data = $lista->data ?? null;
                        $cobrancaAlteracao->operacao = $lista->operacao ?? null;
                        $cobrancaAlteracao->comandado_por = $lista->comandado_por ?? null;
                        $cobrancaAlteracao->conteudo_anterior = $lista->conteudo_anterior ?? null;
                        $cobrancaAlteracao->conteudo_atual = $lista->conteudo_atual ?? null;
                        $cobrancaAlteracao->save();
                        $numHistoricos++; // Incrementa o contador
                    }
                }
                return $response;
                return response()->json(
                    [
                        'Metodo' => 'POST',
                        'Arquivo' => 'ControllerBuscaFull',
                        'registros_afetados' => $numHistoricos,
                        'data' => $response
                    ],
                    201
                );
            }
        } catch (\Exception $e) {
            // Registra um erro no log e retorna uma resposta de erro
            Log::error('Erro ao processar ControllerBuscaFull: ' . $e->getMessage());
            return response()->json([
                'Resposta' => [
                    'codigo' => 500,
                    'mensagem' => 'Ocorreu um erro no servidor.',
                ],
            ], 500);
        }
    }
}