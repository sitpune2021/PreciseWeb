<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentPlan extends Model
{
    protected $table = 'payment_plan';

    protected $fillable = [
        'title',
        'price',
        'short_text',
        'description',
        'days',
        'gst',
        'plan_status'
    ];
    public function clients()
{
    return $this->hasMany(Client::class, 'plan_type');
}
}
