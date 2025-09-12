<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class MaterialOrder extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'sr_no',
        'date',
        'work_order_desc',
        'f_diameter',
        'f_length',
        'f_width',
        'f_height',
        'r_diameter',
        'r_length',
        'r_width',
        'r_height',
        'material',
        'quantity',
    ];
}

