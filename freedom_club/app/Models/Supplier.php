<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'supp_name',
        'supp_contactNum',
        'supp_email',
    ];
    public function material()
    {
        return $this->hasMany(Material::class);
    }
}
