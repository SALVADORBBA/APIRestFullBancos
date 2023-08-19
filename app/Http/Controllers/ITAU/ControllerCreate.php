<?php

namespace App\Http\Controllers\ITAU;

use App\Http\Controllers\ClassGlobais\ClassGenerica;
use App\Http\Controllers\ClassGlobais\ControllerMaster;
use App\Http\Controllers\Controller;
use App\Models\CobrancaTitulo;
use App\Models\ControleMeuNumero;
use App\Models\EventosBoletos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

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

        $obj = (object) ControllerMaster::GetCreate($request->id);


        $data = new stdClass();

        $data->data = new stdClass();
        $data->data->etapa_processo_boleto = $obj->boleto->etapa_processo_boleto;
        $data->data->codigo_canal_operacao = "API";

        $data->data->beneficiario = new stdClass();
        $data->data->beneficiario->id_beneficiario = $obj->Parametros->id_beneficiario;

        $data->data->dado_boleto = new stdClass();
        $data->data->dado_boleto->descricao_instrumento_cobranca = "boleto";
        $data->data->dado_boleto->tipo_boleto = "a vista";
        $data->data->dado_boleto->codigo_carteira = 109; /// carteira
        $data->data->dado_boleto->valor_total_titulo = ClassGenerica::formatarValorItau($obj->boleto->valor);




        $data->data->dado_boleto->codigo_especie = "01";
        $data->data->dado_boleto->valor_abatimento = "000";
        $data->data->dado_boleto->data_emissao = date('Y-m-d');
        $data->data->dado_boleto->indicador_pagamento_parcial = true;
        $data->data->dado_boleto->quantidade_maximo_parcial = 0;

        $data->data->dado_boleto->pagador = new stdClass();
        $data->data->dado_boleto->pagador->pessoa = new stdClass();
        $data->data->dado_boleto->pagador->pessoa->nome_pessoa = $obj->cliente->nome;
        $data->data->dado_boleto->pagador->pessoa->tipo_pessoa = new stdClass();
        if (strlen($obj->cliente->documento) === 14) {
            $data->data->dado_boleto->pagador->pessoa->tipo_pessoa->codigo_tipo_pessoa = "J";
            $data->data->dado_boleto->pagador->pessoa->tipo_pessoa->numero_cadastro_nacional_pessoa_juridica =  $obj->cliente->documento;
        } else {
            $data->data->dado_boleto->pagador->pessoa->tipo_pessoa->codigo_tipo_pessoa = "F";
            $data->data->dado_boleto->pagador->pessoa->tipo_pessoa->numero_cadastro_pessoa_fisica = $obj->cliente->documento;
        }


        $endereco = ClassGenerica::limitarTexto($obj->cliente->endereco, 40) . ClassGenerica::limitarTexto($obj->cliente->numero_cliente, 5);

        $data->data->dado_boleto->pagador->endereco = new stdClass();
        $data->data->dado_boleto->pagador->endereco->nome_logradouro = $endereco;
        $data->data->dado_boleto->pagador->endereco->nome_bairro = $obj->cliente->bairro;
        $data->data->dado_boleto->pagador->endereco->nome_cidade = $obj->cliente->cidade;
        $data->data->dado_boleto->pagador->endereco->sigla_UF = $obj->cliente->uf;
        $data->data->dado_boleto->pagador->endereco->numero_CEP = $obj->cliente->cep;


        $data->data->dado_boleto->dados_individuais_boleto = array();
        $dados_individuais_boleto = new stdClass();
        $dados_individuais_boleto->numero_nosso_numero = $obj->numero_nosso_numero;
        $dados_individuais_boleto->data_vencimento = $obj->boleto->data_vencimento;
        $dados_individuais_boleto->valor_titulo = ClassGenerica::formatarValorItau($obj->boleto->valor);
        $dados_individuais_boleto->texto_uso_beneficiario = "2";
        $dados_individuais_boleto->texto_seu_numero = "2";
        $data->data->dado_boleto->dados_individuais_boleto[] = $dados_individuais_boleto;


        $data->data->dado_boleto->instrucao_cobranca = array();
        $instrucao_cobranca = new stdClass();
        $instrucao_cobranca->codigo_instrucao_cobranca = "4";

        $data->data->dado_boleto->desconto_expresso = false;


        $x_itau_correlationID = ClassGenerica::CreateUuid(1);
        $x_itau_flowID = ClassGenerica::CreateUuid(2);


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.itau.com.br/cash_management/v2/boletos',
            CURLOPT_SSLCERTTYPE => 'P12',
            CURLOPT_SSLCERT =>  $obj->certificado,
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
                'Authorization: Bearer ' . $obj->token,
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response);

        if (isset($response->data->dado_boleto->dados_individuais_boleto[0]->numero_linha_digitavel)) {
            $resultado = $response->data->dado_boleto->dados_individuais_boleto[0];
            $registro = ControleMeuNumero::where('parametros_bancos_id', $obj->boleto->parametros_bancos_id)
                ->where('ultimo_numero', $obj->numero_nosso_numero)
                ->first();

            if ($registro) {
                $registro->status = 'uso';
                $registro->save();
            }


            ################Salvar o evento no banco de dados####################
            $evento = new EventosBoletos();
            // Preencher os campos do evento
            $evento->linhaDigitavel = $resultado->numero_linha_digitavel;
            $evento->codigoBarras = $resultado->codigo_barras;
            $evento->parametros_bancos_id = $obj->boleto->parametros_bancos_id;
            $evento->cobranca_titulo_id = $obj->boleto->id;
            $evento->seunumero = $obj->numero_nosso_numero;
            $evento->numerocarteira = 109;
            $evento->numerocontratocobranca = $data->data->beneficiario->id_beneficiario;
            $evento->mensagem = 'Boleto Gerado com sucesso enviado para Banco do Itau';
            $evento->codigo = 200;

            $evento->save();

            $mensagemPadrao = $evento->mensagem;
            $Cobranca = CobrancaTitulo::find($obj->boleto->id);
            if ($Cobranca) {
                $Cobranca->status = 'Em aberto';
                $Cobranca->seunumero = $obj->numero_nosso_numero;
                $Cobranca->linhadigitavel = $evento->linhaDigitavel;
                $Cobranca->codigobarras = $evento->codigoBarras;
                $Cobranca->modelo = 1;
                $Cobranca->numero_generico_1 = $x_itau_correlationID;
                $Cobranca->numero_generico_2 =  $x_itau_flowID;
                $Cobranca->ambiente_emissao =    $data->data->etapa_processo_boleto;
                $Cobranca->save();
            }



            BoletoPrintStatico::pdf($obj->boleto->id);


            return response()->json([
                'Boleto' => [
                    'codigo' => 200,
                    // 'mensagem' => $mensagemPadrao,
                    // 'data' => $response,
                    // 'id' => $evento->id,
                    //  'PDF'=>$GravaFilePDF
                ],
            ], 200);
        } else {

            return response()->json([
                'Boleto' => [
                    'codigo' => 204,

                ],
            ], 204);
        }
    }
}
