<?php

namespace App\Http\Controllers\ITAU;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use stdClass;

class ControllerSimulador extends Controller
{
    /**
     * Arquivo: ControllerSimulador.php
     * Autor: Rubens do Santos
     * Contato: salvadorbba@gmail.com
     * Método para atualizar a data de vencimento de um boleto no sistema ITAU.
     *
     * @param Request $request Os dados da requisição contendo o ID do boleto e a nova data de vencimento.
     * @return \Illuminate\Http\JsonResponse Resposta JSON contendo o resultado da operação de atualização.
     */
    public function index(Request $request)
    {
        try {
            $data = $request->all();
            $dados_titulo = new stdClass();
            // Acessando informações do beneficiário
            $dados_titulo->beneficiario = $data['data'][0]['beneficiario'];
            // Acessando informações do pagador
            $dados_titulo->pagador = $data['data'][0]['dado_boleto']['pagador']['pessoa'];
            // Acessando detalhes do boleto
            $dados_titulo->boleto = $data['data'][0]['dado_boleto']['dados_individuais_boleto'][0];
            // Acessando todas as entradas históricas
            $historico = $data['data'][0]['dado_boleto']['historico'];
            $historico_detalhado = []; // Criando um array para armazenar as entradas históricas individuais
            // Agora, é possível iterar pelas entradas históricas
            foreach ($historico as $entry) {
                // Criando um stdClass para cada entrada histórica individual
                $historico_entry = new stdClass();
                // Acessando as propriedades individuais da entrada histórica
                $historico_entry->data_historico = $entry['data'];
                $historico_entry->operacao_historico = $entry['operacao'];
                $historico_entry->comandado_por_historico = $entry['comandado_por'];
                // Adicionando a entrada histórica ao array
                $historico_detalhado[] = $historico_entry;
            }
            // Atribuindo o array de entradas históricas detalhadas ao stdClass principal
            $dados_titulo->historico = $historico_detalhado;
            // Finalmente, retornando os dados coletados
            return $dados_titulo;
        } catch (\Exception $e) {
            // Registrando um erro no log e retornando uma resposta de erro
            Log::error('Erro ao processar ControllerSimulador: ' . $e->getMessage());
            return response()->json([
                'Resposta' => [
                    'codigo' => 500,
                    'mensagem' => 'Ocorreu um erro no servidor.',
                ],
            ], 500);
        }
    }
}