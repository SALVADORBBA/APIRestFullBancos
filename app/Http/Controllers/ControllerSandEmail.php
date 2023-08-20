<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ControllerSandEmail extends Controller
{

    public function store(Request $request)
    {

        $Response_Titulo = CobrancaTitulo::find($cobranca_id);
        $Bendeficiario = Beneficiario::find($Response_Titulo->beneficiario_id);
        $Cliente = Cliente::find($Response_Titulo->cliente_id);
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
            'certificado_pix_base64'
        ])
            ->where('id', '=', $Response_Titulo->parametros_bancos_id)
            ->first();




        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $Parametros->Host; // Altere para o host do seu provedor de e-mail
            $mail->SMTPAuth = $Parametros->SMTPAuth;
            $mail->Username = $Parametros->Username; // Altere para o seu e-mail
            $mail->Password = $Parametros->Password; // Altere para a sua senha
            $mail->SMTPSecure = $Parametros->SMTPSecure;
            $mail->Port = $Parametros->Port;
            $mail->Subject = utf8_decode($TemaEmail->titulo);

            $mail->setFrom($Boleto->setFrom, utf8_decode($Boleto->setFrom_name));
            $destinatario_email = $Boleto->email;
            // Define o corpo do e-mail como o conteúdo renderizado da view
            $mail->isHTML(true);
            $mail->Body = $objeto->descricao;
            //  Adiciona o anexo

            $mail->addAttachment($Boleto->caminho_boleto);

            // Define o destinatário
            $mail->addAddress($destinatario_email);

            // Envia o e-mail
            $mail->send();

            $parametros_bancos_id = $Boleto->mill_parametros_bancos_id;
            $system_unit_id = $Boleto->system_unit_id;
            $cobranca_id = $Boleto->cobranca_id;
            $mensagem = 'E-mail de Cobrannça';

            $banco = $Boleto->descricao;

            $titulo = 'Envio Manual';
            $codigo = 500;
            $return = CreateEventos::create($parametros_bancos_id, $system_unit_id, $cobranca_id, $mensagem, $codigo, $titulo, $banco);
            return $return;
        } catch (Exception $e) {
            return 'Ocorreu um erro ao enviar o e-mail: ' . $mail->ErrorInfo;
        }
    }
}