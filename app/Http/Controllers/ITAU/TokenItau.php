<?php

namespace App\Http\Controllers\ITAU;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TokenItau extends Controller
{
    /**
     * Obtém um token de acesso do serviço de autenticação OAuth 2.0 do Itaú usando o fluxo de credenciais do cliente.
     *
     * @param string $client_id O ID do cliente fornecido pelo Itaú para autenticação.
     * @param string $client_secret O segredo do cliente fornecido pelo Itaú para autenticação.
     * @param string $certificado_a1 O certificado decodificado no formato P12 para autenticação TLS mútua.
     * @param string $senha A senha para o certificado P12.
     *
     * @return string|false O token de acesso se a autenticação for bem-sucedida, caso contrário, uma mensagem de erro ou false em caso de falha.
     */
    public static function itau(
        $client_id,
        $client_secret,
        $certificado_a1,
        $senha
    ) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sts.itau.com.br/api/oauth/token',
            CURLOPT_SSLCERTTYPE => 'P12',
            CURLOPT_SSLCERT => $certificado_a1,
            CURLOPT_SSLCERTPASSWD => $senha,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials&client_id=' . $client_id . '&client_secret=' . $clientSecret,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'x-itau-flowID: 1',
                'x-itau-correlationID: 2',
            ),
        ));

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $response = json_decode($response);
        if ($error) {
            curl_close($curl);
            return $error; // Retorna a mensagem de erro em caso de erro cURL.
        } else {
            curl_close($curl);
            return $response->access_token; // Retorna o token de acesso obtido.
        }
    }
}
