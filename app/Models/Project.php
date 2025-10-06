<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
 
 
class Project extends Model
{
    use HasFactory;
    use SoftDeletes;
 
    // Define which fields are mass assignable
    protected $fillable = [  
        'admin_id',    
        'customer_id',
        'project_name',
        // 'customer_name',
        'customer_code',
        'quantity',
        'date',
        'user_id',
        'project_no'
    ];
 public function customer()
{
    return $this->belongsTo(Customer::class,'customer_id');
}
   
}