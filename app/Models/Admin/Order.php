<?php

namespace App\Models\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function orderDetails(){
        return $this->hasMany(OrderDetail::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function status(){
        return $this->belongsTo(Status::class);
    }
    public function promocode()
    {
        return $this->belongsTo(PromoCode::class);
    }

    public function userAddress()
    {
        return $this->belongsTo(UserAddress::class);
    }
    public function guestAddress()
    {
        return $this->belongsTo(GuestAddress::class);
    }

}
