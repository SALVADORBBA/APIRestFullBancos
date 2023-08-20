<?php

namespace App\Http\Controllers;

use App\Http\Controllers\ClassGlobais\ClassGenerica;
use App\Models\Beneficiario;
use App\Models\Cliente;
use App\Models\CobrancaTitulo;
use App\Models\ConfiguracaoEmail;
use App\Models\EventosBoletos;
use App\Models\ParametrosBancos;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use stdClass;

class ControllerSandEmail extends Controller
{

    public function index(Request $request)
    {




        $Response_Titulo = CobrancaTitulo::find($request->id);
        $Bendeficiario = Beneficiario::find($Response_Titulo->beneficiario_id);
        $Cliente = Cliente::find($Response_Titulo->cliente_id);
        $Configuracao = ConfiguracaoEmail::find($Response_Titulo->parametros_bancos_id);


        $Configuracao = ConfiguracaoEmail::where('parametros_bancos_id', $Response_Titulo->parametros_bancos_id)->first();

        $Parametros = ParametrosBancos::select([
            'modelo_id',
            'client_secret',
            'client_id',
            'certificado',
            'senha',
            'client_id_bolecode',
            'client_secret_bolecode',
            'certificados_pix',
            'certificados_extra',
            'senha_certificado_pix',
            'senha_certificado_extra',
            'numerocontrato as id_beneficiario',
            'carteira',
            'id as parametros_bancos_id',
            'system_unit_id',
            'certificado_base64',
            'certificado_pix_base64', 'identificacaoboletoempresa'
        ])
            ->where('id', '=', $Response_Titulo->parametros_bancos_id)
            ->first();


        $retorno = new stdClass();

        $retorno->nome_cliente = $Cliente->nome;
        $retorno->email_cliente = $Cliente->email;
        $retorno->documento_cliente = $Cliente->documento;
        $retorno->celular_cliente = $Cliente->telefone;
        $retorno->endereco_cliente = $Cliente;
        $retorno->valor = ClassGenerica::modeda_string($Response_Titulo->valor);
        $retorno->localizador = $Parametros->identificacaoboletoempresa;
        $retorno->vencimento = ClassGenerica::data_BR($Response_Titulo->data_vencimento);
        $retorno->status_cobranca = $Response_Titulo->status;
        $retorno->razao = $Bendeficiario->razao;
        $retorno->cnpj = $Bendeficiario->cnpj;
        //  $retorno->endereco_beneficiario = $ndereco_bendeficiario;
        $retorno->data_atual = date('d/m/Y H:i:s');


        $retorno->config = new stdClass();
        $retorno->config->Host = $Configuracao->Host;
        $retorno->config->SMTPAuth  = $Configuracao->SMTPAuth;
        $retorno->config->Username  = $Configuracao->Username;
        $retorno->config->Password = $Configuracao->Password;
        $retorno->config->SMTPSecure = $Configuracao->SMTPSecure;
        $retorno->config->Port  = $Configuracao->Port;
        $retorno->config->Port  = $Configuracao->Port;
        $retorno->config->Subject = $Configuracao->Subject;
        $retorno->config->setFrom_name = $Configuracao->setFrom_name;
        $retorno->config->setFrom = $Configuracao->setFrom;
        $retorno->config->body = $Configuracao->body;

        $retorno->boleto = new stdClass();

        $retorno->boleto->seunumero = $Response_Titulo->seunumero;
        $retorno->boleto->caminho_boleto = $Response_Titulo->caminho_boleto;
        $retorno->boleto->DataDoProces = $Response_Titulo->DataDoProces;
        $retorno->boleto->linhadigitavel = $Response_Titulo->linhadigitavel;
        $retorno->boleto->codigobarras = $Response_Titulo->codigobarras;

        try {
            $mail = new PHPMailer(true);

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = $retorno->config->Host; // Altere para o host do seu provedor de e-mail
            $mail->SMTPAuth = $retorno->config->SMTPAuth;
            $mail->Username = $retorno->config->Username; // Altere para o seu e-mail
            $mail->Password = $retorno->config->Password; // Altere para a sua senha
            $mail->SMTPSecure = $retorno->config->SMTPSecure;
            $mail->Port = $retorno->config->Port;
            $mail->Subject =     utf8_decode($retorno->config->Subject);

            $mail->setFrom($retorno->config->setFrom,  utf8_decode($retorno->config->setFrom_name));
            $destinatario_email = $retorno->email_cliente;
            // Define o corpo do e-mail como o conteúdo renderizado da view
            $mail->isHTML(true);
            $mail->Body =  utf8_decode($retorno->config->body);
            //  Adiciona o anexo

            $mail->addAttachment($retorno->boleto->caminho_boleto);

            // Define o destinatário
            $mail->addAddress($destinatario_email);

            // Envia o e-mail
            $mail->send();

            $parametros_bancos_id = $Response_Titulo->parametros_bancos_id;

            $cobranca_id =  $Response_Titulo->id;
            $mensagem = 'E-mail de Cobrança';

            $banco = $mensagem;

            $titulo = 'Envio Manual';
            $codigo = 200;

            $evento = new EventosBoletos();
            // Preencher os campos do evento

            $evento->parametros_bancos_id = $Response_Titulo->parametros_bancos_id;
            $evento->cobranca_titulo_id =  $Response_Titulo->id;
            $evento->seunumero = $retorno->boleto->seunumero;
            $evento->linhadigitavel =   $retorno->boleto->linhadigitavel;


            $evento->mensagem = 'E-mail de Cobrança';
            $evento->codigo = 200;

            $evento->save();

            return response()->json([
                'Resposta' => [
                    'mensagem' => 'email enviado com sucesso',
                    'data' => [
                        'cliente' => [
                            'nome' => $retorno->nome_cliente,
                            'email' => $retorno->email_cliente,
                            'documento' => $retorno->documento_cliente,
                            'celular' => $retorno->celular_cliente,
                            'endereco' => $retorno->endereco_cliente,
                        ],
                        'valor' => $retorno->valor,
                        'boleto' => $retorno->boleto,
                    ],
                ],
            ], $evento->codigo);
        } catch (Exception $e) {
            // Registrando um erro no log e retornando uma resposta de erro
            Log::error('Erro ao processar ControllerSandEmail: ' . $e->getMessage());
            return response()->json([
                'Resposta' => [

                    'mensagem' => 'Erro alenviar email',
                ],
            ], 404);
        }
    }
}
