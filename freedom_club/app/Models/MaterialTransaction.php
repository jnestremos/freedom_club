<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'supplier_transactions_id',
        'material_id',
        'material_type',
        'material_size',
        'material_color',
        'material_qty',
        'material_price',
    ];
}
