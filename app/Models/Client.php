<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
     use SoftDeletes;
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
