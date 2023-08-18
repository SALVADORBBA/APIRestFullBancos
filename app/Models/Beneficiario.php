<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beneficiario extends Model
{
    protected $table = 'beneficiario';

    // Defina as colunas que podem ser preenchidas em massa (mass assignment)
    protected $fillable = [
        'cnpj',
        'endereco',
        'bairro',
        'cidade',
        'uf',
        'cep',
        'fantasia',
        'razao',
        'email',
        'telefone',
        'celular',
        'cuf',
        'ccidade',
        'system_unit_id',
        'numero',
        'lat',
        'lng',
        'token_api'
    ];

    // Defina as colunas que devem ser tratadas como datas (timestamps)
    protected $dates = [
        'data_cadastro',
        'created_at',
        'updated_at',
    ];
}