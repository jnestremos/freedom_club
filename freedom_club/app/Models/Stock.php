<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'material_id',
        'material_number',
        'stock_type',
        'stock_number',
        'stock_size',
        'stock_color',
        'stock_qty',
        'stock_price',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
    public function transfer()
    {
        return $this->hasMany(StockTransfer::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'stock_transfers', 'stock_id', 'product_id');
    }
}
