<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CobrancaTitulo extends Model
{
    protected $table = 'cobranca_titulo';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'beneficiario_id',
        'system_unit_id',
        'parametros_bancos_id',
        'cliente_id',
        'valor',
        'data_vencimento',
        'cobranca_id',
        'xml_nfe',
        'emissao_tipo',
        'mill_bancos_modulos_id',
        'status',
    ];
}