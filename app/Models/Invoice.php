<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

   protected $fillable = [
    'client_id',
    'invoice_no',
    'invoice_date',
    'our_ch_no',
    'our_ch_no_date',
    'y_ch_no',
    'y_ch_no_date',
    'p_o_no',
    'p_o_no_date',
    'description_fast',
    'gst_no',
    'msme_no',

    // Buyer
    'buyer_name',
    'buyer_address',

    // Consignee
    'consignee_name',
    'consignee_address',

    // Extra Contact
    'ki_attn_name',
    '_ki_contact_no',
    'ki_gst',
    
    'kind_attn_name',
    'contact_no',
    'kind_gst',

    // Item
    'description',
    'hsn_code',
    'qty',
    'rate',
    'amount',
    'hrs_per_job',
    'cost',

    // Totals
    'sub_total',
    'sgst',
    'cgst',
    'igst',
    'total_tax_payable',
    'grand_total',

    // Others
    'declaration',
    'note',
    'bank_details',
    'amount_in_words',
];




    // Relations
   

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
