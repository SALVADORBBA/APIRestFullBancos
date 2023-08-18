<?php


namespace App\Http\Controllers;

use App\Models\Beneficiario;
use App\Models\Cliente;
use App\Models\CobrancaTitulo;

use App\Models\ParametrosBancos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ControllerCreateCobranca extends Controller
{


    /**
     
     * Arquivo: ControllerCreateCobranca.php
     * Autor: Rubens do Santos
     * Contato: salvadorbba@gmail.com
     * Data: data_de_criacao
     * Descrição: Descrição breve do propósito deste arquivo.
  
     * Método para criar um novo recurso de armazenamento.
     *
     * @param int $cobranca_id
     * @return \Illuminate\Http\Response
     */

    public function create(Request $request)
    {
        try {
            $parametrosBancos = ParametrosBancos::find($request->parametros_bancos_id);
            $beneficiario = Beneficiario::find($parametrosBancos->beneficiario_id);
            $cliente = Cliente::find($request->cliente_id);

            $validator = Validator::make($request->all(), [
                'cliente_id' => 'required|int',
                'parametros_bancos_id' => 'required|int',
                'valor' => 'required|numeric',
                'data_vencimento' => 'required|date',
                'identificacaoboletoempresa' => 'required|int',
                'cobranca_id' => 'required|int',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return response()->json([
                    'Metodo' => 'POST',
                    'Arquivo' => 'ControllerCreateCobranca',
                    'tabela' => 'cobranca_titulo',
                    'ListagemErros' => $errors,
                ], 400);
            } else {
                $bruteForce = filter_var($request->brute_force, FILTER_VALIDATE_BOOLEAN);

                if ($bruteForce === false) {
                    $existingCobranca = CobrancaTitulo::where([
                        'beneficiario_id' => $beneficiario->id,
                        'parametros_bancos_id' => $parametrosBancos->id,
                        'cliente_id' => $cliente->id,
                        'valor' => $request->valor,
                        'data_vencimento' => $request->data_vencimento,
                        'cobranca_id' => $request->cobranca_id,
                        'identificacaoboletoempresa' => $request->identificacaoboletoempresa,
                    ])->first();

                    if ($existingCobranca !== null) {

                        Log::info('Já existe uma cobrança com esses dados: ' . $request->all());
                        return response()->json([
                            'Resposta' => [
                                'codigo' => 400,
                                'mensagem' => 'Já existe uma cobrança com esses dados.',
                            ],
                        ], 400);
                    }
                }

                $cobrancaTitulo = new CobrancaTitulo();
                $cobrancaTitulo->beneficiario_id = $beneficiario->id;
                $cobrancaTitulo->parametros_bancos_id = $parametrosBancos->id;
                $cobrancaTitulo->cliente_id = $cliente->id;
                $cobrancaTitulo->valor = $request->valor;
                $cobrancaTitulo->DataDoProces = now();
                $cobrancaTitulo->data_vencimento = $request->data_vencimento;
                $cobrancaTitulo->emissao_tipo = 1;
                $cobrancaTitulo->bancos_modulos_id = $parametrosBancos->bancos_modulos_id;
                $cobrancaTitulo->status = 'new';
                $cobrancaTitulo->tipo = 1;
                $cobrancaTitulo->cobranca_id = $request->cobranca_id;
                $cobrancaTitulo->identificacaoboletoempresa = $request->identificacaoboletoempresa;
                $cobrancaTitulo->save();


                Log::info($cobrancaTitulo);
                return response()->json([
                    'Cobranca' => [
                        'data' => $cobrancaTitulo,
                    ],
                ], 201);
            }
        } catch (\Exception $e) {
            Log::info('Erro ao processar GetCreate: ' . $e->getMessage());
            return response()->json([
                'Resposta' => [
                    'codigo' => 500,
                    'mensagem' => 'Ocorreu um erro no servidor verifique o log. rota api/logs',
                ],
            ], 500);
        }
    }
}