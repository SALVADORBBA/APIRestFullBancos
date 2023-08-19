<?php

namespace App\Http\Controllers;

use App\Models\BancosModulo;
use App\Models\Beneficiario;
use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Models\CobrancaTitulo;

class CobrancaTituloController extends Controller
{

    /**
     
     * Arquivo: ControllerCreateCobranca.php
     * Autor: Rubens do Santos
     * Contato: salvadorbba@gmail.com
     * Data: data_de_criacao
     * Descrição: Descrição breve do propósito deste arquivo.
  
     * Obtém uma página de boletos com opção de filtro por status e paginação.
     *
     * @param Request $request Requisição HTTP contendo parâmetros de filtro e paginação.
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetBoletoPage(Request $request)
    {
        // Defina a quantidade de itens por página
        $itensPorPagina = $request->registros;
        if (!isset($request->page)) {
            $page = 1;
        } else {
            $page = $request->page;
        }
        // Verifique se foi fornecido um parâmetro de página na requisição
        $pagina = $request->input('page', $page);

        // Realize a busca com paginação
        $titulos = CobrancaTitulo::where('status', $request->status)
            ->paginate($itensPorPagina, ['*'], 'page', $pagina);

        // Retorne os resultados como JSON
        return response()->json($titulos);
    }


    public function GetBancosPage(Request $request)
    {
        // Defina a quantidade de itens por página
        $itensPorPagina = $request->registros;
        if (!isset($request->page)) {
            $page = 1;
        } else {
            $page = $request->page;
        }
        // Verifique se foi fornecido um parâmetro de página na requisição
        $pagina = $request->input('page', $page);

        // Realize a busca com paginação
        $titulos = BancosModulo::where('status', $request->status)
            ->paginate($itensPorPagina, ['*'], 'page', $pagina);

        // Retorne os resultados como JSON
        return response()->json($titulos);
    }



    public function GetClientePage(Request $request)
    {
        // Defina a quantidade de itens por página
        $itensPorPagina = $request->registros;
        if (!isset($request->page)) {
            $page = 1;
        } else {
            $page = $request->page;
        }
        // Verifique se foi fornecido um parâmetro de página na requisição
        $pagina = $request->input('page', $page);

        // Realize a busca com paginação
        $titulos = Cliente::where('status', $request->status)
            ->paginate($itensPorPagina, ['*'], 'page', $pagina);

        // Retorne os resultados como JSON
        return response()->json($titulos);
    }

    /**
     * Obtém os detalhes de um boleto específico.
     *
     * @param Request $request Requisição HTTP contendo o ID do boleto.
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetBoleto(Request $request)
    {
        $titulos = CobrancaTitulo::find($request->id);
        // Retorne os resultados como JSON
        return response()->json($titulos);
    }

    /**
     * Obtém os detalhes de um cliente específico.
     *
     * @param Request $request Requisição HTTP contendo o ID do cliente.
     * @return \Illuminate\Http\JsonResponse
     */
    public function Getcliente(Request $request)
    {
        $titulos = Cliente::find($request->id);
        // Retorne os resultados como JSON
        return response()->json($titulos);
    }

    /**
     * Obtém os detalhes de um beneficiário específico.
     *
     * @param Request $request Requisição HTTP contendo o ID do beneficiário.
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetBeneficiario(Request $request)
    {
        $titulos = Beneficiario::find($request->id);
        // Retorne os resultados como JSON
        return response()->json($titulos);
    }
}