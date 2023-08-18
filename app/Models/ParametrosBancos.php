<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosBancos extends Model
{
    use HasFactory;
    protected $table = 'parametros_bancos';

    protected $hidden = ['certiticado_base64', 'senha'];
}

// INSERT INTO mill_parametros_bancos_modelo
// SELECT * FROM mill_parametros_bancos;