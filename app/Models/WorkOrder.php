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
        'project_no',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class, 'work_order');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'id');
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class, 'work_order_id', 'id');
    }


    public function machineRecords()
    {
        return $this->hasMany(MachineRecord::class, 'work_order', 'id');
    }

    public function hsn()
    {
        return $this->belongsTo(Hsncode::class, 'material', 'material_type');
    }
    
    public function materialType()
    {
        return $this->belongsTo(MaterialType::class, 'material');
    }
}
