<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MillCliente extends Model
{
    protected $table = 'mill_cliente';
    protected $fillable = [
        'documento',
        'nome',
        'endereco',
        'bairro',
        'cidade',
        'cep',
        'uf',
        'telefone',
        'email',
        'codigo_ibge',
        'numero',
        'complemento',
        'system_unit_id',
        'status_id'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}