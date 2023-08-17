<?php

namespace App\Http\Controllers\ClassGlobais;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use stdClass;

class ControllerMaster extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = DB::table('view_for_boleto')->where('id', '=', $request->cobranca_id)->first();


        $Parametros = DB::table('mill_parametros_bancos')->where('id', '=', $result->mill_parametros_bancos_id)->first([
            'modelo_id',
            'client_secret',
            'client_id',
            'certificado',
            'senha', 'client_id_bolecode', 'client_secret_bolecode', 'certificados_pix',
            'certificados_extra', 'senha_certificado_pix', 'senha_certificado_extra',
            'numerocontrato as id_beneficiario', 'carteira', 'id as mill_parametros_bancos_id',
            'system_unit_id', 'certificado_base64', 'certificado_pix_base64'
        ]);


        $obj = new stdClass();

        if ($Parametros->modelo_id == 2) {
            $obj->client_id = $Parametros->client_id;
            $obj->client_secret = $Parametros->client_secret;
            $obj->id_beneficiario = $Parametros->id_beneficiario;
            $obj->certificado = $Parametros->certificado;
            $obj->senha = $Parametros->senha;
            $obj->carteira = $Parametros->carteira;
            $obj->seunumero = $result->seunumero;
            $obj->mill_parametros_bancos_id = $Parametros->mill_parametros_bancos_id;
            $obj->system_unit_id = $Parametros->system_unit_id;
            $obj->certificado_base64 = $Parametros->certificado_base64;
            $obj->data_vencimento = $result->data_vencimento;
            $obj->modelo_id = $Parametros->modelo_id;
            // $obj->indentificacao_global = $result->indentificacao_global;
            //  $obj->digito_verificador_global = $result->digito_verificador_global;
        } else {
            $obj->client_id = $Parametros->client_id_bolecode;
            $obj->client_secret = $Parametros->client_secret_bolecode;
            $obj->id_beneficiario = $Parametros->id_beneficiario;
            $obj->certificado = $Parametros->certificados_pix;
            $obj->senha = $Parametros->senha_certificado_pix;
            $obj->carteira = $Parametros->carteira;
            $obj->seunumero = $result->seunumero;
            $obj->mill_parametros_bancos_id = $Parametros->mill_parametros_bancos_id;
            $obj->system_unit_id = $Parametros->system_unit_id;
            $obj->certificado_base64 = $Parametros->certificado_pix_base64;
            $obj->data_vencimento = $result->data_vencimento;
            $obj->modelo_id = $Parametros->modelo_id;
            //   $obj->indentificacao_global = $result->indentificacao_global; //indetificação do boleto id_boleto itau
            //$obj->digito_verificador_global = $result->digito_verificador_global;

        }




        // id_beneficiario/codigo_carteira/numero_nosso_numero/dac_titulo
        $x_itau_correlationID = MillFunctionsClass::CreateUuid(1);
        $x_itau_flowID = MillFunctionsClass::CreateUuid(2);
        $pasta = 'certificado/pfx/' . $result->cnpj . '/' . $result->mill_beneficiario_id . '/' . $result->mill_parametros_bancos_id . '/modelo_' . $Parametros->modelo_id;
        $certificado_real = $pasta . '/certificado.pfx';

        if (is_dir($pasta)) {
        } else {
            mkdir($pasta, 0777, true);
        }

        $decodedCert = base64_decode($obj->certificado_base64);
        file_put_contents($certificado_real, $decodedCert);
    }
}
