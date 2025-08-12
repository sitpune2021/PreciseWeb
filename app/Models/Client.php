<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
     protected $fillable = [
        'name',
        'email_id',
        'phone_no',
        'gst_no',
        'logo',
        'address',
        'login_id',
    ];
}
