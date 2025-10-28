<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'customer_id',
        'client_id',
        'invoice_no',
        'invoice_date',
        'sub_total',
        'total_tax',
        'adjustment',
        'round_off',
        'grand_total',
        'total_hrs',
        'total_vmc',
        'declaration',
        'note',
        'bank_details',
        'amount_in_words',
        'status',
    ];

    protected $casts = [
        'invoice_date' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }
}
