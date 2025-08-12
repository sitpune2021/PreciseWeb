<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;


    // Define which fields are mass assignable
    protected $fillable = [
        'login_id',
        'name',
        'code',
        'email_id',
        'contact_person',
        'phone_no',
        'gst_no',
        'address',
    ];
    public function projects()
{
    return $this->hasMany(Project::class,'customer_id');
}
}
