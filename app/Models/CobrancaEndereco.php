<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CobrancaEndereco extends Model
{
    protected $table = 'cobranca_enderecos';

    protected $fillable = [
        'endereco',
        'bairro',
        'cidade',
        'cep',
        'uf',
        'numero',
        'complemento',
        'cliente_id',
    ];

    public function cliente()
    {
        return $this->belongsTo(MillCliente::class, 'cliente_id');
    }
}