<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        'name','email','password','customer_type','vip_start_dae','vip_end_date','discount',
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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'vip_start_date'=>'datetime',
            'vip_end_date'=>'datetime',
        ];
    }

    public function isVip()
    {
        return $this->is_vip && now()->between($this->vip_start_date, $this->vip_end_date);
    }

    public function getVipDiscount()
    {
        if ($this->isVip()) {
            return $this->vip_discount;
        }
        return 0;
    }

    

}
