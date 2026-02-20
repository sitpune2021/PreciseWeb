<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class MaterialReq extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'material_reqs';

    protected $fillable = [
        'admin_id',
        'customer_id',
        'code',
        'date',
        'description',
        'work_order_no',
        'dia',
        'length',
        'width',
        'height',
        'material',
        'part_no',
        'material_rate',
        'material_gravity',
        'qty',
        'weight',
        'lathe',
        'mg4',
        'mg2',
        'rg2',
        'sg4',
        'sg2',
        'vmc_hrs',
        'vmc_cost',
        'hrc',
        'edm_qty',
        'edm_rate',
        'cl',
        'material_cost',
        'total_cost',
        'sr_no'

    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function materialType()
    {
        return $this->belongsTo(MaterialType::class, 'material', 'id');
    }

    public function materialOrders()
    {
        return $this->hasMany(MaterialOrder::class);
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class, 'part_no');
    }
}
