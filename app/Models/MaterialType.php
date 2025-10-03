<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
 
 
class MaterialType extends Model
{
    use SoftDeletes;
    use HasFactory;
 
   protected $fillable = [
     
        'material_type',
        'material_rate',
        'material_gravity',
        'status',
        'is_active',
        'admin_id', 
];
}
 