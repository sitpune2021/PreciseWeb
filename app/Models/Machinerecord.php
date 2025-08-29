<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MachineRecord extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'part_no',
        'code',
        'work_order',
        'first_set',
        'qty',
        'machine',
        'operator',
        'setting_no',
        'est_time',
        'start_time',
        'end_time',
        'actual_hrs',
        'invoice_no',
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
