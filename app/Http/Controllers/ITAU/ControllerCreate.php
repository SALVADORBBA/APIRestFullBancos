<?php

namespace App\Http\Controllers\ITAU;

use App\Http\Controllers\ClassGlobais\ClassGenerica;
use App\Http\Controllers\ClassGlobais\ControllerMaster;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ControllerCreate extends Controller
{

    /**
     * Arquivo: ControllerCreate.php
     * Autor: Rubens do Santos
     * Contato: salvadorbba@gmail.com
     * Data: data_de_criacao
     * Descrição: Descrição breve do propósito deste arquivo.
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)

    {


        return      $retorno = ControllerMaster::GetCreate($request->cobranca_id);


        $data = new stdClass();

        $data->data = new stdClass();
        $data->data->etapa_processo_boleto = $request->etapa_processo_boleto;
        $data->data->codigo_canal_operacao = "API";

        $data->data->beneficiario = new stdClass();
        $data->data->beneficiario->id_beneficiario = $obj->id_beneficiario;

        $data->data->dado_boleto = new stdClass();
        $data->data->dado_boleto->descricao_instrumento_cobranca = "boleto";
        $data->data->dado_boleto->tipo_boleto = "a vista";
        $data->data->dado_boleto->codigo_carteira = $result->global_2; /// carteira
        $data->data->dado_boleto->valor_total_titulo = MillFunctionsClass::formatarValorItau($result->valor);

        $data->data->dado_boleto->codigo_especie = "01";
        $data->data->dado_boleto->valor_abatimento = "000";
        $data->data->dado_boleto->data_emissao = date('Y-m-d');
        $data->data->dado_boleto->indicador_pagamento_parcial = true;
        $data->data->dado_boleto->quantidade_maximo_parcial = 0;

        $data->data->dado_boleto->pagador = new stdClass();
        $data->data->dado_boleto->pagador->pessoa = new stdClass();
        $data->data->dado_boleto->pagador->pessoa->nome_pessoa = $result->nome;
        $data->data->dado_boleto->pagador->pessoa->tipo_pessoa = new stdClass();
        if (strlen($result->documento) === 14) {
            $data->data->dado_boleto->pagador->pessoa->tipo_pessoa->codigo_tipo_pessoa = "J";
            $data->data->dado_boleto->pagador->pessoa->tipo_pessoa->numero_cadastro_nacional_pessoa_juridica = $result->documento;
        } else {
            $data->data->dado_boleto->pagador->pessoa->tipo_pessoa->codigo_tipo_pessoa = "F";
            $data->data->dado_boleto->pagador->pessoa->tipo_pessoa->numero_cadastro_pessoa_fisica = $result->documento;
        }

        $endereco = MillFunctionsClass::limitarTexto($result->endereco, 40) . MillFunctionsClass::limitarTexto($result->numero_cliente, 5);

        $data->data->dado_boleto->pagador->endereco = new stdClass();
        $data->data->dado_boleto->pagador->endereco->nome_logradouro = $endereco;
        $data->data->dado_boleto->pagador->endereco->nome_bairro = $result->bairro;
        $data->data->dado_boleto->pagador->endereco->nome_cidade = $result->cidade;
        $data->data->dado_boleto->pagador->endereco->sigla_UF = $result->uf;
        $data->data->dado_boleto->pagador->endereco->numero_CEP = $result->cep;

        $data->data->dado_boleto->dados_individuais_boleto = array();
        $dados_individuais_boleto = new stdClass();
        $dados_individuais_boleto->numero_nosso_numero = $numero_agregado;
        $dados_individuais_boleto->data_vencimento = $obj->data_vencimento;
        $dados_individuais_boleto->valor_titulo = MillFunctionsClass::formatarValorItau($result->valor);
        $dados_individuais_boleto->texto_uso_beneficiario = "2";
        $dados_individuais_boleto->texto_seu_numero = "2";
        $data->data->dado_boleto->dados_individuais_boleto[] = $dados_individuais_boleto;


        $data->data->dado_boleto->instrucao_cobranca = array();
        $instrucao_cobranca = new stdClass();
        $instrucao_cobranca->codigo_instrucao_cobranca = "4";

        $data->data->dado_boleto->desconto_expresso = false;


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.itau.com.br/cash_management/v2/boletos',
            CURLOPT_SSLCERTTYPE => 'P12',
            CURLOPT_SSLCERT =>  $certificado_real,
            CURLOPT_SSLCERTPASSWD => $obj->senha,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'x-itau-apikey: ' . $obj->client_id,
                'x-itau-correlationID: ' .   $x_itau_correlationID,
                'x-itau-flowID: ' . $x_itau_flowID,
                'Content-Type: application/json',
                'Authorization: Bearer ' . $TokenItau,
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response);

        if (isset($response->data->dado_boleto->dados_individuais_boleto[0]->numero_linha_digitavel)) {
            $resultado = $response->data->dado_boleto->dados_individuais_boleto[0];
            $registro = MillControleMeuNumero::where('mill_parametros_bancos_id', $result->mill_parametros_bancos_id)
                ->where('ultimo_numero', $ultimoNumero)
                ->where('system_unit_id', $result->system_unit_id)
                ->first();

            if ($registro) {
                $registro->status = 'uso';
                $registro->save();
            }

            // $resultado->numero_nosso_numero": "20000002",
            // $resultado->data_vencimento": "2023-07-30",
            // $resultado->valor_titulo": "00000000000119900",
            // $resultado->texto_seu_numero": "2",
            // $resultado->codigo_barras": "34199942700001199001092000000225729085083000",
            // $resultado->numero_linha_digitavel": "34191092060000022572290850830002994270000119900",
            // $resultado->texto_uso_beneficiario": "2"
            ################Salvar o evento no banco de dados####################
            $evento = new MillEventosBoletos();
            // Preencher os campos do evento
            $evento->linhaDigitavel = $resultado->numero_linha_digitavel;
            $evento->codigoBarras = $resultado->codigo_barras;

            $evento->mill_parametros_bancos_id = $result->mill_parametros_bancos_id;
            $evento->system_unit_id = $result->system_unit_id;
            $evento->mill_cobranca_titulo_id = $result->id;

            $evento->seunumero = $numero_agregado;
            $evento->numerocarteira = $result->global_2;

            $evento->numerocontratocobranca = $data->data->beneficiario->id_beneficiario;

            $evento->mensagem = 'Boleto Gerado com sucesso enviado para Banco do Itau';
            $evento->codigo = 200;

            $evento->save();

            $mensagemPadrao = $evento->mensagem;
            $Cobranca = MillCobrancaTitulo::find($result->id);
            if ($Cobranca) {
                $Cobranca->status = 'Em aberto';
                $Cobranca->seunumero = $numero_agregado;
                $Cobranca->linhadigitavel = $evento->linhaDigitavel;
                $Cobranca->codigobarras = $evento->codigoBarras;
                $Cobranca->modelo = 1;
                $Cobranca->numero_generico_1 = $x_itau_correlationID;
                $Cobranca->numero_generico_2 =  $x_itau_flowID;
                $Cobranca->ambiente_emissao =    $data->data->etapa_processo_boleto;
                $Cobranca->save();
            }

            BoletoCodePIX::store($result->id);

            sleep(1);

            return response()->json([
                'EventoBoleto' => [
                    'codigo' => 200,
                    'mensagem' => $mensagemPadrao,
                    'data' => $response,
                    'id' => $evento->id,
                    //  'PDF'=>$GravaFilePDF
                ],
            ], 200);
        } else {

            return response()->json([
                'EventoBoleto' => [
                    'codigo' => 404,

                ],
            ], 404);
        }
    }
}
