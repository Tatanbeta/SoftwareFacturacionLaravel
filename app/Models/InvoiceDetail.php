<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    protected $table = 'invoice_details';
    public $timestamps = false;

    const CREATED_AT = 'creado';
    const UPDATED_AT = 'modificado';

    protected $fillable = [
        'invoice_id',
        'item_code',
        'item_name',
        'unit_price',
        'quantity',
        'applies_tax',
        'tax_amount',
        'subtotal',
        'total',
    ];

    // Producto pertenece a una factura
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }
}
