<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'personas';
    public $timestamps = false;

    const CREATED_AT = 'creado';
    const UPDATED_AT = 'modificado';

    protected $fillable = [
        'cedula',
        'nombre',
        'email',
    ];

    // Relación con usuarios (una persona puede tener un usuario)
    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'persona', 'id');
    }

    // Relación con facturas
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'persona_id', 'id');
    }
}
