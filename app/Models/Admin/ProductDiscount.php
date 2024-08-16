<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDiscount extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'discount', 'discount_type', 'start_date', 'end_date'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function isActive()
    {
        $now = now();
        return $this->start_date <= $now && $this->end_date >= $now;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
