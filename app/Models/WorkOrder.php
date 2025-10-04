<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkOrder extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'admin_id',
        'customer_id',
        'part',
        //  'part_code',
        'project_id',
        'date',         
        'part_description',
        'dimeter',
        'length',
        'width',
        'height',
        'exp_time',
        'quantity',
        'material',
    ];

    public function customer()
{
    return $this->belongsTo(Customer::class, 'customer_id', 'id');
}

public function project()
{
    return $this->belongsTo(Project::class, 'project_id');
}


    
}
