<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $casts = [
        'trial_start' => 'datetime',
        'trial_end' => 'datetime',
        'plan_expiry' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'email_id',
        'login_id',
        'phone_no',
        'gst_no',
        'logo',
        'address',
        'trial_start',
        'trial_end',
        'plan_type',
        'plan_expiry',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'login_id');
    }
    public function plan()
{
    return $this->belongsTo(PaymentPlan::class, 'plan_type');
}


    

}
