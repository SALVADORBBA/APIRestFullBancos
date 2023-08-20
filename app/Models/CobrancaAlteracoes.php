<?php

namespace  App\Models;

use Illuminate\Database\Eloquent\Model;

class CobrancaAlteracoes extends Model
{
    protected $table = 'cobranca_alteracoes';
    protected $fillable = [
        'cobranca_titulo_id',
        'data',
        'operacao',
        'comandado_por',
        'conteudo_anterior',
        'conteudo_atual',
    ];
}