<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
 
class Operator extends Model
{
    use SoftDeletes;
    protected $fillable=[
        'operator_name',
        'phone_no',
        'status',
        'is_active',
    ];

     protected $casts = [
        'is_active' => 'boolean',
    ];
}