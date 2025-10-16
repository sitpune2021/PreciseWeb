<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'work_order_id',
        'part_name',
        'hsn_code',
        'qty',
        'rate',
        'amount',
        'hrs',
        'vmc',
        'adj',
        'sgst',
        'cgst',
        'igst',
        'vo_no',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
