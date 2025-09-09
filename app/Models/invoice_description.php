<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class invoice_description extends Model
{
    use SoftDeletes;

    protected $table = 'invoice_descriptions';

    protected $fillable = [
        'inc_description',
        'status',
    ];
}
