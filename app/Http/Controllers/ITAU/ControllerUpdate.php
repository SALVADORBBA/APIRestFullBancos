<?php

namespace App\Http\Controllers\ITAU;

use App\Http\Controllers\ClassGlobais\ClassGenerica;
use App\Http\Controllers\ClassGlobais\ControllerMaster;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ControllerUpdate extends Controller
{

    /**
     * Arquivo: ControllerCreate.php
     * Autor: Rubens do Santos
     * Contato: salvadorbba@gmail.com
     * Método para atualizar a data de vencimento de um boleto no sistema ITAU.
     *
     * @param Request $request Os dados da requisição contendo o ID do boleto e a nova data de vencimento.
     * @return \Illuminate\Http\JsonResponse Resposta JSON contendo o resultado da operação de atualização.
     */

    public function update(Request $request)
    {

        $obj = (object) ControllerMaster::GetCreate($request->id);
        $x_itau_correlationID = ClassGenerica::CreateUuid(1);
        $x_itau_flowID = ClassGenerica::CreateUuid(2);
        $id_boleto =   $obj->Parametros->id_beneficiario . $obj->Parametros->carteira . $obj->boleto->seunumero;


        $url = 'https://api.itau.com.br/cash_management/v2/boletos/' . $id_boleto . '/data_vencimento';
        $requestData = [
            'data_vencimento' =>  $request->data_vencimento,
        ];

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
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => json_encode($requestData),
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response);

        if (isset($response->codigo)) {

            return response()->json(
                [
                    'Metodo' => 'PUT',
                    'Arquivo' => 'ControllerUpdate',
                    'tabela' => 'ControllerMaster',
                    'campo' => 'data_vencimento',
                    'data' => $response->codigo,
                    'Vencimento' =>  $request->data_vencimento,

                ],
                $response->codigo
            );
        } else {

            return response()->json(
                [
                    'Metodo' => 'PUT',
                    'Arquivo' => 'ControllerUpdate',
                    'tabela' => 'ControllerMaster',
                    'campo' => 'data_vencimento',
                    'data' =>  $request->data_vencimento,
                    'mensagem' => 'Vencimento Atualzado',

                ],
                200
            );
        }
    }
}
