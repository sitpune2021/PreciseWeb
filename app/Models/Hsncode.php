<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hsncode extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hsn_code',
        'sgst',
        'cgst',
        'igst',
        'invoice_desc',
        'status',
    ];
}
