<?php

namespace App\Models;

use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Model;
use Razorpay\Api\Plan;

class Order extends Model
{

    protected $fillable = [
        'user_id',
        'plan_id',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'amount',
        'payment_status',
        'plan_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function plan()
    {
        return $this->belongsTo(PaymentPlan::class, 'plan_id');  // ✔ Correct
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'user_id', 'login_id'); // ✔ So you can access plan_type
    }
}
