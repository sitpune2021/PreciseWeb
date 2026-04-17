<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProformaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'work_order_id',
        'machine_id',
        'project_id',
        'work_order_id',
        'part_name',
        'hsn_code',
        'material_rate',
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
        'material_cost',   //add
        'total_cost',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }

    public function machineRecord()
    {
        return $this->belongsTo(MachineRecord::class, 'machine_record_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function machine()
    {
        return $this->belongsTo(MachineRecord::class, 'machine_id');
    }
}
