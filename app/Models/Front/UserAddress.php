<?php

namespace App\Models\Front;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;
    protected $table = 'user_addresses';
    protected $fillable = [
        'full_name',
        'phone',
        'address',
        'city',
        'state',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class); // العلاقة من الجهة الأخرى
    }
}
