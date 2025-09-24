<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;
     protected $fillable = [
        'admin_id',
        'vendor_name',
        'vendor_code',
        'contact_person',
        'gst_no',
        'status',
        'phone_no',
        'email_id',
        'address'
    ];
}
