<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'admin_id',
        'customer_id',
        'quotation_no',
        'project_name',
        'description',
        'date',
        'total_manufacturing_cos',
        'profit',
        'overhead',
        'terms_conditions',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function customer()
{
    return $this->belongsTo(Customer::class);
}

   public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

}
