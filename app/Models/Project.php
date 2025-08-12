<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
 
class Project extends Model
{
    use HasFactory;
   
 
    // Define which fields are mass assignable
    protected $fillable = [
        'name',
        'code',
        'description',
        'customer_id',
        'work_order_no',
        'qty',
        'StartDate',
        'EndDate',
    ];
 public function customer()
{
    return $this->belongsTo(Customer::class,'customer_id');
}
   
}