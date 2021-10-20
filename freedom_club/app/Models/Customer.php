<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'user_id',
        'cust_firstName',
        'cust_lastName',
        'cust_email',
        'cust_address',
        'cust_region',
        'cust_province',
        'cust_municipality',
        'cust_city',
        'cust_phoneNum',
        'cust_barangay',
        'cust_gender',
        'cust_birthDate',
        'cust_notifyNews',
        'cust_profile_pic',
    ];
}
