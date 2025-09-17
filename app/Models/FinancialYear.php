<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class FinancialYear extends Model
{
     use SoftDeletes;
    protected $fillable = ['year', 'status'];
}
