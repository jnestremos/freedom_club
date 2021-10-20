<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'supp_transactions_id',
        'receipt_number',
        'shipping_fee',
        'dateReceived',
    ];

    public function transaction()
    {
        return $this->belongsTo(SuppTransaction::class);
    }
}
