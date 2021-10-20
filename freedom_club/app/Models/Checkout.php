<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checkout extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'status',
        'confirm_token',
        'invoice_number',
        'receipt_number',
        'emailStatus',
        'acc_name',
        'acc_num',
        'payment_method',
        'pending',
        'total',
        'shipping_service',
        'tracking_number',
    ];
    protected $hidden = [
        'acc_number',
    ];
    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_checkout', 'checkout_id', 'cart_id');
    }
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
