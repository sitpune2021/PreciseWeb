<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationItem extends Model
{
    protected $fillable = [
        'quotation_id',
        'description',
        'dia',
        'length',
        'width',
        'height',
        'qty',
        'qty_in_kg',
        'material_rate',
        'material',
        'material_type_id',
        'material_cost',
        'lathe',
        'mg',
        'rg',
        'cg',
        'sg',
        'vmc_soft',
        'vmc_hard',
        'edm_hole',
        'ht',
        'wirecut',
        'machining_cost',
        'material_gravity',
        
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    
}
