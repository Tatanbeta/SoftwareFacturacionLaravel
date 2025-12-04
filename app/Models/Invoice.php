<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';
    public $timestamps = false;

    const CREATED_AT = 'creado';
    const UPDATED_AT = 'modificado';

    protected $fillable = [
        'persona_id',
        'issue_date',
        'due_date',
        'invoice_type',
        'subtotal',
        'tax_total',
        'total',
        'user_id',
    ];

    // Factura pertenece a Persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'id');
    }

    // Factura pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'id');
    }

    // Factura tiene muchos productos
    public function detalles()
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id', 'id');
    }
}
