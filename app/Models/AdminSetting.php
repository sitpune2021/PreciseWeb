<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
    use HasFactory;

    protected $table = 'admin_settings';

    protected $fillable = [
        'admin_id',
        'gst_no',
        'date',
        'udyam_no',
        'bank_details',
        'declaration',
        'note',
        'logo',
        'stamp',
        'footer_note',
    ];
}
