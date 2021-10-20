<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuppTransaction extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'receipt_num',
        'totalCost',
        'datePaid'
    ];

    public function supplierTransactionMaterial()
    {
        return $this->hasMany(SupplierTransaction::class);
    }
    public function materials()
    {
        return $this->belongsToMany(Material::class, 'material_transaction', 'supp_transactions_id', 'material_id');
    }
    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }
}
