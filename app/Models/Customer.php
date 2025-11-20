<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Define which fields are mass assignable
    protected $fillable = [
        'login_id',
        'admin_id',
        'customer_srno',
        'name',
        'code',
        'per_hour_rate',
        'email_id',
        'contact_person',
        'phone_no',
        'gst_no',
        'address',
        'status',
    ];
    public function projects()
    {
        return $this->hasMany(Project::class, 'customer_id');
    }

    public function materialOrders()
    {
        return $this->hasMany(MaterialOrder::class);
    }
    public function materialreq()
    {
        return $this->hasOne(MaterialReq::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
