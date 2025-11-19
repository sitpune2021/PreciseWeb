<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'admin_id',
        'customer_id',
        'date',
        'work_order_no',
        'work_order_desc',
        'f_diameter',
        'f_length',
        'f_width',
        'f_height',
        'r_diameter',
        'r_length',
        'r_width',
        'r_height',
        'quantity',
        'material',
        'qty',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function materialreq()
    {
        return $this->belongsTo(MaterialReq::class, 'MaterialReq');
    }
}
