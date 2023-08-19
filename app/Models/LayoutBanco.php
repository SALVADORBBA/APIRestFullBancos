<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LayoutBanco extends Model
{
    protected $table = 'layout_bancos'; // Nome da tabela no banco de dados
    protected $primaryKey = 'id'; // Chave primária da tabela
    public $timestamps = false; // Defina como false se a tabela não possui colunas 'created_at' e 'updated_at'

    // Defina quais colunas você pode preencher usando atribuição em massa (mass assignment)
    protected $fillable = [
        'nome',
        'bancos_modulos_id',
        'logomarca',
        'codigo_layout',
        'tipo_layout',
        'nome_arquivo_php',
        'nome_arquivo_css',
        'status',
        'imagem_layout',
        'modelo_id',
        'bancos',
    ];
}
