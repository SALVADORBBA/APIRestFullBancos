<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MillEmitente extends Model
{
    use HasFactory;

    protected $table = 'beneficiario';

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
        'numero',
        'lat',
        'lng'
    ];
}
