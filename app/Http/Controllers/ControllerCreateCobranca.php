<?php

/**
 * Arquivo: ControllerCreateCobranca.php
 * Autor: Rubens do Santos
 * Contato: salvadorbba@gmail.com
 * Data: data_de_criacao
 * Descrição: Descrição breve do propósito deste arquivo.
 */

namespace App\Http\Controllers;

use App\Models\CobrancaTitulo;
use App\Models\MillCobrancaTitulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ControllerCreateCobranca extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {



        $ParametrosBancos = DB::table('parametros_bancos')->where('id', '=', $request->parametros_bancos_id)->first([
            'modelo_id',
            'client_secret',
            'client_id',
            'certificado',
            'senha', 'client_id_bolecode', 'client_secret_bolecode', 'certificados_pix',
            'certificados_extra', 'senha_certificado_pix', 'senha_certificado_extra',
            'numerocontrato as id_beneficiario', 'carteira', 'id as parametros_bancos_id',
            'system_unit_id', 'certificado_base64', 'certificado_pix_base64', 'beneficiario_id', 'bancos_modulos_id', 'id'
        ]);

        $Beneficiario = DB::table('beneficiario')->where('id', '=', $ParametrosBancos->beneficiario_id)->first();
        $Cliente = DB::table('beneficiario')->where('id', '=', $ParametrosBancos->beneficiario_id)->first();
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
            return response()->json(
                [
                    'Metodo' => 'POST',
                    'Arquivo' => 'ControllerCreateCobranca',
                    'tabela' => 'cobranca_titulo',
                    'ListagemErros' => $errors,
                ],
                400
            );
        } else {





            $cobrancaTitulo = new CobrancaTitulo();
            // Preencha os atributos do modelo com base nos dados recebidos do formulário
            $cobrancaTitulo->beneficiario_id = $Beneficiario->id;

            $cobrancaTitulo->parametros_bancos_id = $ParametrosBancos->id;

            $cobrancaTitulo->cliente_id =   $Cliente->id;

            $cobrancaTitulo->valor = $request->valor;
            $cobrancaTitulo->DataDoProces = date('Y-m-d H:i:s');

            $cobrancaTitulo->data_vencimento = $request->data_vencimento;
            $cobrancaTitulo->emissao_tipo = 1; /// tipo 1 = boletos simples 2 carne
            $cobrancaTitulo->bancos_modulos_id = $ParametrosBancos->bancos_modulos_id;
            $cobrancaTitulo->status = 'new';
            $cobrancaTitulo->tipo = 1;
            $cobrancaTitulo->cobranca_id = $request->cobranca_id;
            $cobrancaTitulo->identificacaoboletoempresa = $request->identificacaoboletoempresa;
            //   $cobrancaTitulo->seunumero = $ultimoNumero;
            // Salve o novo registro no banco de dados
            $cobrancaTitulo->save();











            return response()->json([
                'EventoBoleto' => [
                    'codigo' => 200,
                    'data' =>  $cobrancaTitulo,

                ],
            ], 200);
        }
    }
}
