<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MachineRecord extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'admin_id',
        'work_order_id',
        'project_id',
        'part_no',
        'code',
        'work_order',
        'first_set',
        'qty',
        'customer_id',
        // 'machine',
        // 'operator',
        // 'setting_no',
        // 'material',
        'machine_id',
        'operator_id',
        'setting_id',
        'material_id',
        'est_time',
        'start_time',
        'end_time',
        'adjustment',
        // 'minute',
        'hrs',
        'idl_time',
        // 'time_taken',
        // 'actual_hrs',
        'invoice_no',
        'status',

    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function workOrder()
    {
        // foreign key in machine_records = work_order
        return $this->belongsTo(WorkOrder::class, 'work_order', 'id');
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class, 'machine_record_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function machineData()
    {
        return $this->belongsTo(Machine::class, 'machine_id');
    }

    public function operatorData()
    {
        return $this->belongsTo(Operator::class, 'operator_id');
    }

    public function setting()
    {
        return $this->belongsTo(Setting::class, 'setting_id');
    }

    public function materialData()
    {
        return $this->belongsTo(MaterialType::class, 'material_id');
    }
    
}
