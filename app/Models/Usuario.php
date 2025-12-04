<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    public $timestamps = false;

    const CREATED_AT = 'creado';
    const UPDATED_AT = 'modificado';

    protected $fillable = [
        'persona',
        'nick',
        'pass',
        'token',
        'ultimo_acceso',
        'estado',
    ];

    // Usuario pertenece a Persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona', 'id');
    }

    // Usuario registra facturas
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'user_id', 'id');
    }
}
