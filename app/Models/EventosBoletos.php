<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventosBoletos extends Model
{
    protected $table = 'eventos_boletos';

    protected $fillable = [
        'linhaDigitavel',
        'codigoBarras',
        'pdf_binario',
        'caminho_pdf',
        'mill_parametros_bancos_id',
        'system_unit_id',
        'documento_id',
        'mensagem',
        'codigo',
    ];

    // Relação com a tabela MillParametrosBancos
    public function parametrosBancos()
    {
        return $this->belongsTo(MillParametrosBancos::class, 'mill_parametros_bancos_id');
    }

    // Relação com a tabela SystemUnit
    public function systemUnit()
    {
        return $this->belongsTo(SystemUnit::class, 'system_unit_id');
    }
}
