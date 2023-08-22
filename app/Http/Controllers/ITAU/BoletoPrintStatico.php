<?php

namespace App\Http\Controllers\ITAU;

use App\Http\Controllers\ClassGlobais\ClassGenerica;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ServicosDelicados\MillFunctionsClass;
use App\Models\Beneficiario;
use App\Models\Cliente;
use App\Models\LayoutBanco;
use App\Models\CobrancaTitulo;
use App\Models\ParametrosBancos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Picqer\Barcode\BarcodeGeneratorPNG;

use stdClass;
// BoletoITAU::indexx($cobranca_id)
class BoletoPrintStatico extends Controller
{
    /**
     * Display a listing of the resource.
     *composer require mpdf/mpdf
     *composer require picqer/php-barcode-generator   
     * 
     * @return \Illuminate\Http\Response
     */
    public static function pdf($id)
    {

        $ObjetoImprimir = CobrancaTitulo::find($id);
        $LayoutITAU =  LayoutBanco::find(4);
        $ParametrosBancos = ParametrosBancos::find($ObjetoImprimir->parametros_bancos_id);
        $Bendeficiario = Beneficiario::find($ObjetoImprimir->beneficiario_id);
        $Cliente = Cliente::find($ObjetoImprimir->cliente_id);


        $dados = new stdClass();
        $dados->cobranca = new stdClass();
        $dados->cobranca->numero = $ObjetoImprimir->seunumero;
        $dados->cobranca->linhadigitavel = $ObjetoImprimir->linhadigitavel;
        $dados->cobranca->codigoBarraNumerico = $ObjetoImprimir->codigobarras;
        $dados->cobranca->Vencimento = date('d/m/Y', strtotime($ObjetoImprimir->data_vencimento));
        $data_vencimento = $dados->cobranca->Vencimento;
        $dados->beneficiario = new stdClass();
        $dados->beneficiario->cnpj = $Bendeficiario->cnpj;
        $dados->beneficiario->nome = $Bendeficiario->razao;
        $DataDoDoc = date('d/m/Y H:i:s', strtotime($ObjetoImprimir->created_at));
        $dados->pagador = new stdClass();
        $nome = $dados->pagador->nome = $Cliente->nome;
        $documento =  $dados->pagador->documento =  $Cliente->documento;
        $dados->pagador->documento = $Cliente->cpf_cliente;
        $dados->pagador->endereco = $Cliente->endereco;
        $dados->pagador->numero = $Cliente->numero;
        $dados->pagador->bairro = $Cliente->bairro;
        $dados->pagador->cidade = $Cliente->cidade;
        $dados->pagador->uf = $Cliente->uf;
        $dados->pagador->cep = $Cliente->cep;

        $ValorDocumento = number_format($ObjetoImprimir->valor, 2, ",", ".");

        $AgenciCodDoCedente = $ParametrosBancos->agencia;
        $RuaNumeroBairro = $dados->pagador->endereco . ', ' . $dados->pagador->numero . ' ' . $dados->pagador->bairro;

        $DataDoProces = date('d/m/Y  H:i:s', strtotime($ObjetoImprimir->data_cadastro));
        $NumeroDodoc =   $ObjetoImprimir->seunumero;
        $CidadeUf = 'Cidade: ' . $dados->pagador->cidade . '-' . 'UF: ' . $dados->pagador->uf;

        $dadosbanco = $ParametrosBancos->agencia . '-' . $ParametrosBancos->digito_agencia . '  /  ' . $ParametrosBancos->numerocontacorrente . '-' . $ParametrosBancos->digito_conta;
        $NossoNumero = $ObjetoImprimir->seunumero;

        $CEP = 'CEP: ' . $dados->pagador->cep;

        $especie = "DM";

        $info =
            $ParametrosBancos->mens1 . '<br>'
            . $ParametrosBancos->mens2 . '<br>'
            . $ParametrosBancos->mens3 . '<br>'

            . $ParametrosBancos->info5 . ' ' . $ObjetoImprimir->cobranca_id;

        $dados->beneficiario->digito_agencia = $ObjetoImprimir->digito_agencia;
        $dados->beneficiario->numerocontacorrente = $ObjetoImprimir->numerocontacorrente;
        $dados->beneficiario->digito_conta = $ObjetoImprimir->digito_conta;
        function formatarCNPJ($cnpj)
        {
            $cnpj = preg_replace('/[^0-9]/', '', $cnpj); // Remove caracteres não numéricos
            $cnpj = str_pad($cnpj, 14, '0', STR_PAD_LEFT); // Completa com zeros à esquerda, se necessário
            $cnpjFormatado = substr($cnpj, 0, 2) . '.' . substr($cnpj, 2, 3) . '.' . substr($cnpj, 5, 3) . '/' . substr($cnpj, 8, 4) . '-' . substr($cnpj, 12);
            return $cnpjFormatado;
        }

        $cnpj = formatarCNPJ($dados->beneficiario->cnpj);
        $Cedente = ($dados->beneficiario->nome);

        if (strlen($documento  === 11)) {
            $CpfDoSacado = 'CPF: ' . $documento;
        } else {
            $CpfDoSacado = 'CNPJ: ' . $documento;
        }



        $urlBancoLayout = env('URL_BANCO_LAYOUT');
        $creditos = env('CREDITOS');
        $instrucoes = env('INSTRUCOES');
        $urlApi = env('URL_API');
        $resultado = env('NUMERO_BANCO');


        $linhadigitavel = $ObjetoImprimir->linhadigitavel;
        $numero = $linhadigitavel;
        $nomeArquivo = $numero . '.pdf';
        $generatorPNG = new BarcodeGeneratorPNG();
        $bar_code = $generatorPNG->getBarcode($ObjetoImprimir->codigobarras, $generatorPNG::TYPE_CODE_128, 2, 50, [0, 0, 0]);
        $img_base64 = base64_encode($bar_code);
        $barra = '<img src="data:image/png;base64,' . $img_base64 . '" width="520">';



        $logo_banco =  $urlBancoLayout .  $LayoutITAU->logomarca;
        $digito_verificador = '109' . $NumeroDodoc;

        $mpdf = new Mpdf();

        $html = " <!DOCTYPE HTML>
        <html>

        <head>
            <link rel='stylesheet' href='css/style_bb.css'>

        </head>

        <body>


            <TABLE cellSpacing=0 cellPadding=0 border=0 class=Boleto>

                <TR>
                    <TD style='width: 0.9cm'></TD>
                    <TD style='width: 1cm'></TD>
                    <TD style='width: 1.9cm'></TD>

                    <TD style='width: 0.5cm'></TD>
                    <TD style='width: 1.3cm'></TD>
                    <TD style='width: 0.8cm'></TD>
                    <TD style='width: 1cm'></TD>

                    <TD style='width: 1.9cm'></TD>
                    <TD style='width: 1.9cm'></TD>

                    <TD style='width: 3.8cm'></TD>

                    <TD style='width: 3.8cm'> </TD>

                <tr>
                    <td colspan=11>
                        <table border='0' cellspacing='0' style='border-collapse:collapse; width:100%'>
                            <tbody>
                                <tr>
                                    <td style='width:965px'>
                                        <ul>
                                            <li><span style='font-family:Verdana,Geneva,sans-serif'><span
                                                        style='font-size:11px'>Imprima em papel A4 ou Carta</span></span></li>
                                            <li><span style='font-family:Verdana,Geneva,sans-serif'><span
                                                        style='font-size:11px'>Utilize margens m&iacute;nimas a direita e a
                                                        esquerda</span></span></li>
                                            <li><span style='font-family:Verdana,Geneva,sans-serif'><span
                                                        style='font-size:11px'>Recorte na linha pontilhada</span></span></li>
                                            <li><span style='font-family:Verdana,Geneva,sans-serif'><span
                                                        style='font-size:11px'>N&atilde;o rasure o c&oacute;digo de
                                                        barras</span></span>


                                        </ul>
                                        </li>
                                        </ul>
                                    </td>
                                    <td style='text-align:right; width:137px'> </td>
                                    <td style='text-align:right; width:86px'>  </td>
                                </tr>
                            </tbody>
                        </table>

                        <table border='0' cellpadding='1' cellspacing='1' style='width:100%'>
                            <tbody>
                                <tr>
                                    <td class=Pontilhado></td>
                                </tr>
                            </tbody>
                        </table>

                    </td>
                </tr>
                </TR>
                <tr>
                    <td colspan=11 class=BoletoPontilhado>&nbsp;</td>
                </tr>
                <TR>
                    <TD colspan=4 class=BoletoLogo><img src='$logo_banco' width='150'>
                    </TD>
                    <TD colspan=2 class=BoletoCodigoBanco> $resultado </TD>
                    <TD colspan=6 class=Boletolinhadigitavel>$numero</TD>
                </TR>
                <TR>
                    <TD colspan=10 class=BoletoTituloEsquerdo>Local de Pagamento</TD>
                    <TD class=BoletoTituloDireito>Vencimento</TD>
                </TR>
                <TR>
                    <TD colspan=10 class=BoletoValorEsquerdo style='text-align: left; padding-left : 0.1cm'> ATÉ O VENC.
                        PREFERENCIALMENTE NO BANCO DO ITAU
                    </TD>
                    <TD class=BoletoValorDireito>$data_vencimento</TD>
                </TR>
                <TR>
                    <TD colspan=10 class=BoletoTituloEsquerdo> Nome do Beneficários</TD>
                    <TD class=BoletoTituloDireito>Agência/Código do Beneficário</TD>
                </TR>
                <TR>
                    <TD colspan=10 class=BoletoValorEsquerdo style='text-align: left; padding-left : 0.1cm'>$Cedente CNPJ: $cnpj
                    </TD>
                    <TD class=BoletoValorDireito>$dadosbanco</TD>
                </TR>
                <TR>
                    <TD colspan=3 class=BoletoTituloEsquerdo>Data do Documento</TD>
                    <TD colspan=4 class=BoletoTituloEsquerdo>Número do Documento</TD>
                    <TD class=BoletoTituloEsquerdo>Espécie</TD>
                    <TD class=BoletoTituloEsquerdo>Aceite</TD>
                    <TD class=BoletoTituloEsquerdo>Data do Processamento</TD>
                    <TD class=BoletoTituloDireito>Nosso Número</TD>
                </TR>
                <TR>
                    <TD colspan=3 class=BoletoValorEsquerdo>$DataDoDoc</TD>
                    <TD colspan=4 class=BoletoValorEsquerdo>$NumeroDodoc</TD>
                    <TD class=BoletoValorEsquerdo>$especie</TD>
                    <TD class=BoletoValorEsquerdo>N</TD>
                    <TD class=BoletoValorEsquerdo>$DataDoProces</TD>
                    <TD class=BoletoValorDireito>$digito_verificador </TD>
                </TR>
                <TR>
                    <TD colspan=3 class=BoletoTituloEsquerdo>Uso do Banco</TD>
                    <TD colspan=2 class=BoletoTituloEsquerdo>Carteira</TD>
                    <TD colspan=2 class=BoletoTituloEsquerdo>Moeda</TD>
                    <TD colspan=2 class=BoletoTituloEsquerdo>Quantidade</TD>
                    <TD class=BoletoTituloEsquerdo>(x) Valor</TD>
                    <TD class=BoletoTituloDireito>(=) Valor do Documento</TD>
                </TR>
                <TR>
                    <TD colspan=3 class=BoletoValorEsquerdo>&nbsp;</TD>
                    <TD colspan=2 class=BoletoValorEsquerdo> 109 </TD>
                    <TD colspan=2 class=BoletoValorEsquerdo>R$</TD>
                    <TD colspan=2 class=BoletoValorEsquerdo>&nbsp;</TD>
                    <TD class=BoletoValorEsquerdo>&nbsp;</TD>
                    <TD class=BoletoValorDireito>$ValorDocumento</TD>
                </TR>
                <TR>
                    <TD colspan=10 class=BoletoTituloEsquerdo>Instruções</TD>
                    <TD class=BoletoTituloDireito>(-) Desconto</TD>
                </TR>
                <TR>
                    <TD colspan=10 rowspan=9 class=BoletoValorEsquerdo
                        style='text-align: left; vertical-align:top; padding-left : 0.1cm'> $info</TD>
                    <TD class=BoletoValorDireito>&nbsp;</TD>
                </TR>
                <TR>
                    <TD class=BoletoTituloDireito>(-) Outras Deduções/Abatimento</TD>
                </TR>
                <TR>
                    <TD class=BoletoValorDireito>&nbsp;</TD>
                </TR>
                <TR>
                    <TD class=BoletoTituloDireito>(+) Mora/Multa/Juros</TD>
                </TR>
                <TR>
                    <TD class=BoletoValorDireito>&nbsp;</TD>
                </TR>
                <TR>
                    <TD class=BoletoTituloDireito>(+) Outros Acréscimos</TD>
                </TR>
                <TR>
                    <TD class=BoletoValorDireito>&nbsp;</TD>
                </TR>
                <TR>
                    <TD class=BoletoTituloDireito>(=) Valor Cobrado</TD>
                </TR>
                <TR>
                    <TD class=BoletoValorDireito>&nbsp;</TD>
                </TR>
                <TR>
                    <TD rowspan=3 Class=BoletoTituloSacado>Pagador: </TD>
                    <TD colspan=8 Class=BoletoValorSacado>$nome</TD>
                    <TD colspan=2 Class=BoletoValorSacado>$CpfDoSacado</TD>
                </TR>
                <TR>
                    <TD colspan=10 Class=BoletoValorSacado>$RuaNumeroBairro</TD>
                </TR>
                <TR>
                    <TD colspan=10 Class=BoletoValorSacado>$CidadeUf&nbsp;&nbsp;&nbsp;$CEP</TD>
                </TR>
                <TR>
                    <TD colspan=2 Class=BoletoTituloSacador>Pagador / Avalista:</TD>
                    <TD colspan=9 Class=BoletoValorSacador>...</TD>
                </TR>
                <TR>
                    <TD colspan=11 class=BoletoTituloDireito style='text-align: right; padding-right: 0.1cm'>Recibo do Pagador -
                        Autenticação Mecânica</TD>
                </TR>
                <TR>
                    <TD class=ambiente colspan=11 valign=top>$instrucoes</TD>
                </TR>
                <tr>

                    <td colspan=11 class=BoletoPontilhado>&nbsp;</td>
                </tr>
                <TR>
                    <TD colspan=4 class=BoletoLogo><img src='$logo_banco' width=' 150'>
                    </TD>
                    <TD colspan=2 class=BoletoCodigoBanco> $resultado</TD>
                    <TD colspan=6 class=Boletolinhadigitavel>$numero</TD>
                </TR>
                <TR>
                    <TD colspan=10 class=BoletoTituloEsquerdo>Local de Pagamento</TD>
                    <TD class=BoletoTituloDireito>Vencimento</TD>
                </TR>
                <TR>
                    <TD colspan=10 class=BoletoValorEsquerdo style='text-align: left; padding-left : 0.1cm'> ATÉ O VENC.
                        PREFERENCIALMENTE NO BANCO DO ITAU
                    </TD>
                    <TD class=BoletoValorDireito>$data_vencimento</TD>
                </TR>
                <TR>
                    <TD colspan=10 class=BoletoTituloEsquerdo>Nome do Beneficário</TD>
                    <TD class=BoletoTituloDireito>Agência/Código do Beneficário</TD>
                </TR>
                <TR>
                    <TD colspan=10 class=BoletoValorEsquerdo style='text-align: left; padding-left : 0.1cm'>$Cedente CNPJ: $cnpj
                    </TD>
                    <TD class=BoletoValorDireito>$dadosbanco</TD>
                </TR>
                <TR>
                    <TD colspan=3 class=BoletoTituloEsquerdo>Data do Documento</TD>
                    <TD colspan=4 class=BoletoTituloEsquerdo>Número do Documento</TD>
                    <TD class=BoletoTituloEsquerdo>Espécie</TD>
                    <TD class=BoletoTituloEsquerdo>Aceite</TD>
                    <TD class=BoletoTituloEsquerdo>Data do Processamento</TD>
                    <TD class=BoletoTituloDireito>Nosso Número</TD>
                </TR>
                <TR>
                    <TD colspan=3 class=BoletoValorEsquerdo>$DataDoDoc</TD>
                    <TD colspan=4 class=BoletoValorEsquerdo>$NumeroDodoc</TD>
                    <TD class=BoletoValorEsquerdo>$especie</TD>
                    <TD class=BoletoValorEsquerdo>N</TD>
                    <TD class=BoletoValorEsquerdo>$DataDoProces</TD>
                    <TD class=BoletoValorDireito>$digito_verificador </TD>
                </TR>
                <TR>
                    <TD colspan=3 class=BoletoTituloEsquerdo>Uso do Banco</TD>
                    <TD colspan=2 class=BoletoTituloEsquerdo>Carteira</TD>
                    <TD colspan=2 class=BoletoTituloEsquerdo>Moeda</TD>
                    <TD colspan=2 class=BoletoTituloEsquerdo>Quantidade</TD>
                    <TD class=BoletoTituloEsquerdo>(x) Valor</TD>
                    <TD class=BoletoTituloDireito>(=) Valor do Documento</TD>
                </TR>
                <TR>
                    <TD colspan=3 class=BoletoValorEsquerdo>&nbsp;</TD>
                    <TD colspan=2 class=BoletoValorEsquerdo> 109 </TD>
                    <TD colspan=2 class=BoletoValorEsquerdo>R$</TD>
                    <TD colspan=2 class=BoletoValorEsquerdo>&nbsp;</TD>
                    <TD class=BoletoValorEsquerdo>&nbsp;</TD>
                    <TD class=BoletoValorDireito>$ValorDocumento</TD>
                </TR>
                <TR>
                    <TD colspan=10 class=BoletoTituloEsquerdo>Instruções</TD>
                    <TD class=BoletoTituloDireito>(-) Desconto</TD>
                </TR>
                <TR>
                    <TD colspan=10 rowspan=9 class=BoletoValorEsquerdo
                        style='text-align: left; vertical-align:top; padding-left : 0.1cm'> $info</TD>
                    <TD class=BoletoValorDireito>&nbsp;</TD>
                </TR>
                <TR>
                    <TD class=BoletoTituloDireito>(-) Outras Deduções/Abatimento</TD>
                </TR>
                <TR>
                    <TD class=BoletoValorDireito>&nbsp;</TD>
                </TR>
                <TR>
                    <TD class=BoletoTituloDireito>(+) Mora/Multa/Juros</TD>
                </TR>
                <TR>
                    <TD class=BoletoValorDireito>&nbsp;</TD>
                </TR>
                <TR>
                    <TD class=BoletoTituloDireito>(+) Outros Acréscimos</TD>
                </TR>
                <TR>
                    <TD class=BoletoValorDireito>&nbsp;</TD>
                </TR>
                <TR>
                    <TD class=BoletoTituloDireito>(=) Valor Cobrado</TD>
                </TR>
                <TR>
                    <TD class=BoletoValorDireito>&nbsp;</TD>
                </TR>
                <TR>
                    <TD rowspan=3 Class=BoletoTituloSacado>Pagador: </TD>
                    <TD colspan=8 Class=BoletoValorSacado>$nome</TD>
                    <TD colspan=2 Class=BoletoValorSacado>$CpfDoSacado</TD>
                </TR>
                <TR>
                    <TD colspan=10 Class=BoletoValorSacado>$RuaNumeroBairro</TD>
                </TR>
                <TR>
                    <TD colspan=10 Class=BoletoValorSacado>$CidadeUf&nbsp;&nbsp;&nbsp;$CEP</TD>
                </TR>
                <TR>
                    <TD colspan=2 Class=BoletoTituloSacador>Pagador / Avalista:</TD>
                    <TD colspan=9 Class=BoletoValorSacador>...</TD>
                </TR>
                <TR>
                    <TD colspan=11 class=BoletoTituloDireito style='text-align: right; padding-right: 0.1cm'>Ficha de
                        Compensação - Autenticação Mecânica</TD>
                </TR>
                <TR>
                    <TD colspan=11 height=60 valign=top>" . $barra . "</TD>
                </TR>
                <tr>
                    <td colspan=11 class=Pontilhado> </td>

                </tr>

            </TABLE>


        </body>

        </html>";

        $nomeDiretorio = 'documentos/pdf/itau/boletos/' . $ObjetoImprimir->beneficiario_id . '/' . $ObjetoImprimir->parametros_bancos_id;
        $nomeArquivo = $nomeDiretorio . '/' . $numero . '.pdf';

        if (is_dir($nomeDiretorio)) {
        } else {
            mkdir($nomeDiretorio, 0777, true);
        }
        $mpdf->SetHeader($LayoutITAU->nome . ' ' . $LayoutITAU->codigo_layout);

        if ($ObjetoImprimir->etapa_processo_boleto == "validacao") {
            $mpdf->SetWatermarkText($ObjetoImprimir->etapa_processo_boleto);
        }

        $mpdf->showWatermarkText = true;
        $mpdf->WriteHTML($html);
        $senha = 2;


        $mpdf->SetProtection(array(), 'UserPassword', 123); //habilitar senha

        $mpdf->SetFooter($creditos);

        $caminhoArquivo = $nomeArquivo;
        $mpdf->Output($caminhoArquivo, Destination::FILE);
        $mpdf->Output($nomeArquivo, Destination::INLINE);

        $Cobranca = CobrancaTitulo::find($id);
        if ($Cobranca) {
            $Cobranca->caminho_boleto = $nomeArquivo;
            $Cobranca->save();
        }
    }
}
