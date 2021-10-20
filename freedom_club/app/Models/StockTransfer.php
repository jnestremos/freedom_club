<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTransfer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'stock_id',
        'product_id',
        'quantity',
        'confirmed'
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
