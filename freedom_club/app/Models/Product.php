<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'prod_name',
        'product_number',
        'prod_qty',
        'prod_type',
        'prod_size',
        'prod_color',
        'prod_status',
        'prod_price',
    ];

    public function transfer()
    {
        return $this->hasMany(StockTransfer::class);
    }
    public function stocks()
    {
        return $this->belongsToMany(Stock::class, 'stock_transfers', 'product_id', 'stock_id');
    }
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
}
