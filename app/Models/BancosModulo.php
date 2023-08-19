<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BancosModulo extends Model
{
    use HasFactory;

    protected $table = 'bancos_modulos';
    protected $primaryKey = 'id';
    public $timestamps = false;

    // Colunas que podem ser preenchidas em massa
    protected $fillable = [
        'numero',
        'descricao',
        'status',
        'logo',
        'ambiente',
        'apelido',
        'system_unit_id',
    ];

    // Relações ou outros métodos do model podem ser definidos aqui
}
