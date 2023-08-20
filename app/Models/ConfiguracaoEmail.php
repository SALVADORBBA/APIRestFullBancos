<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracaoEmail extends Model
{
    use HasFactory;

    protected $table = 'configuracoes_emails';
    protected $fillable = [
        'parametros_bancos_id',
        'descricao',
        'titulo',
        'assunto',
        'Host',
        'SMTPAuth',
        'Username',
        'Password',
        'SMTPSecure',
        'Port',
        'setFrom',
        'setFrom_name',
        'status',
        'logo',
        'mensagem_final'
    ];

    public function parametroBanco()
    {
        return $this->belongsTo(ParametroBanco::class, 'parametros_bancos_id');
    }
}
