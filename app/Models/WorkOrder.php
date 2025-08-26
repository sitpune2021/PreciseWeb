<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrder extends Model
{
    use SoftDeletes;
    protected $fillable = [
    
        'customer_id',
        'part',
        //  'part_code',
        'date',         
        'part_description',
        'dimeter',
        'length',
        'width',
        'height',
        'exp_time',
        'quantity',
    ];

    public function customer()
{
    return $this->belongsTo(Customer::class, 'customer_id', 'id');
}

    
}
