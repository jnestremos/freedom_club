<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use HasFactory;

    // $table->id();
    //         $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
    //         $table->foreignId('product_id')->constrained('products')->onUpdate('cascade')->onDelete('cascade');
    //         $table->foreignId('checkout_id')->nullable()->constrained('checkouts')->onUpdate('cascade')->onDelete('cascade');
    //         $table->boolean('pending')->default(false);
    //         $table->integer('quantity');
    //         $table->double('subtotal');
    //         $table->timestamps();
    protected $fillable = [
        'user_id',
        'product_id',
        'checkout_id',
        'quantity',
        'pending',
        'subtotal',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function checkouts()
    {
        return $this->belongsToMany(Checkout::class, 'cart_checkout', 'cart_id', 'checkout_id');
    }
    public function checkout()
    {
        return $this->hasMany(Checkout::class);
    }
}
