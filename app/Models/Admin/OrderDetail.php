<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function variant(){
        return $this->belongsTo(Variant::class);
    }
}
