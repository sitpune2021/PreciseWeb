<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
 
class SetupSheet extends Model
{
    use SoftDeletes;
 
    protected $fillable = [
        'admin_id',
        'customer_id',
        'part_code',
        'work_order_no',
        'date',
        'description',
        'size_in_x',
        'size_in_y',
        'size_in_z',
        'setting',
        'e_time',
        'x_refer',
        'y_refer',
        'z_refer',
        'clamping',    
        'qty',
        'holes',
        'hole_x',
        'hole_y',
        'hole_dia',
        'hole_depth',
    ];

    protected $casts = [
        'holes'      => 'array',
        'hole_x'     => 'array',
        'hole_y'     => 'array',
        'hole_dia'   => 'array',
        'hole_depth' => 'array',
    ];
public function customer() {
    return $this->belongsTo(Customer::class, 'customer_id');
}

public function project() {
    return $this->belongsTo(Project::class, 'project_id');
}
}