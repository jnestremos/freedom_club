<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'material_number',
        'material_type',
        'material_size',
        'material_color',
        'material_price',
    ];
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function supplierTransactions()
    {
        return $this->belongsToMany(SuppTransaction::class, 'material_transaction', 'material_id', 'supp_transactions_id');
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
}
