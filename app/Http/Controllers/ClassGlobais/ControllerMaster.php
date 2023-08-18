<?php

namespace App\Http\Controllers\ClassGlobais;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ITAU\TokenItau;
use App\Http\Controllers\ServicosDelicados\ControleMeuNumeroService;
use Illuminate\Http\Request;
use stdClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ControllerMaster extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public  static function GetCreate($cobranca_id)
    {


        try {

            $Response_Titulo = DB::table('cobranca_titulo')->where('id', '=', $cobranca_id)->first();
            $Bendeficiario = DB::table('beneficiario')->where('id', '=', $Response_Titulo->beneficiario_id)->first();
            $Parametros = DB::table('parametros_bancos')->where('id', '=', $Response_Titulo->parametros_bancos_id)->first([
                'modelo_id',
                'client_secret',
                'client_id',
                'certificado',
                'senha', 'client_id_bolecode', 'client_secret_bolecode', 'certificados_pix',
                'certificados_extra', 'senha_certificado_pix', 'senha_certificado_extra',
                'numerocontrato as id_beneficiario', 'carteira', 'id as parametros_bancos_id',
                'system_unit_id', 'certificado_base64', 'certificado_pix_base64'
            ]);



            $obj = new stdClass();
            $obj->client_id = $Parametros->client_id;
            $obj->client_secret = $Parametros->client_secret;
            $obj->id_beneficiario = $Parametros->id_beneficiario;
            $obj->certificado = $Parametros->certificado;
            $obj->senha = $Parametros->senha;
            $obj->carteira = $Parametros->carteira;
            $obj->seunumero = $Response_Titulo->seunumero;
            $obj->parametros_bancos_id = $Parametros->parametros_bancos_id;
            $obj->system_unit_id = $Parametros->system_unit_id;
            $obj->certificado_base64 = $Parametros->certificado_base64;
            $obj->data_vencimento = $Response_Titulo->data_vencimento;
            $obj->modelo_id = $Parametros->modelo_id;
            $x_itau_correlationID = ClassGenerica::CreateUuid(1);
            $x_itau_flowID = ClassGenerica::CreateUuid(2);
            $pasta = 'certificado/pfx/' .  $Bendeficiario->cnpj . '/' . $Response_Titulo->beneficiario_id . '/' . $Response_Titulo->parametros_bancos_id . '/modelo_' . $Parametros->modelo_id;
            $certificado_real = $pasta . '/certificado.pfx';

            if (is_dir($pasta)) {
            } else {
                mkdir($pasta, 0777, true);
            }

            $decodedCert = base64_decode($obj->certificado_base64);
            file_put_contents($certificado_real, $decodedCert);

            $token =  TokenItau::itau(
                $obj->client_id,
                $obj->client_secret,
                $certificado_real,
                $obj->senha
            );

            $ControleMeuNumeroService = new ControleMeuNumeroServices();
            $ultimoNumero = $ControleMeuNumeroService->verificarEAtualizarNumero_itau($Response_Titulo->parametros_bancos_id);
            return  $numero_agregado = str_pad($ultimoNumero, 8, '0', STR_PAD_LEFT);



            return response()->json([
                'Resposta' => [
                    'codigo' => 200,
                    'nosso_numero' => $numero_agregado,
                    'certificado' =>  $certificado_real,
                    'token' =>  $token,
                    //  'PDF'=>$GravaFilePDF
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao processar GetCreate: ' . $e->getMessage());
            return response()->json([
                'Resposta' => [
                    'codigo' => 500,
                    'mensagem' => 'Ocorreu um erro no servidor.',
                ],
            ], 500);
        }
    }
}
