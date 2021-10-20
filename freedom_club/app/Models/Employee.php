<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    use HasFactory;
    protected $fillable = [
        'user_id',
        'emp_firstName',
        'emp_lastName',
        'emp_email',
        'emp_gender',
        'emp_birthDate',
    ];
}
