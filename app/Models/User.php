<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Front\UserAddress;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name','last_name','phone','email',
        'password','customer_type','vip_start_date',
        'vip_end_date','discount','is_vip','status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'vip_start_date'=>'datetime',
            'vip_end_date'=>'datetime',
        ];
    }

    public function isVip()
    {
        $now = now();
        return $this->is_vip &&
            $now->startOfDay()->greaterThanOrEqualTo($this->vip_start_date->startOfDay()) &&
            $now->endOfDay()->lessThanOrEqualTo($this->vip_end_date->endOfDay());
    }

    public function getVipDiscountAttribute()
    {
        if ($this->isVip()) {
            return $this->discount;
        }
        return 0;
    }

    public function address()
    {
        return $this->hasOne(UserAddress::class); // العلاقة واحد لواحد
    }


}
