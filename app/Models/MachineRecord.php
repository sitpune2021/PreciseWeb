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
        'part_no',
        'code',
        'work_order',
        'first_set',
        'qty',
        'machine',
        'operator',
        'setting_no',
        'material',
        'est_time',
        'start_time',
        'end_time',
        'adjustment',
        // 'minute',
        'hrs',
        // 'time_taken',
        // 'actual_hrs',
        'invoice_no'

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
}
