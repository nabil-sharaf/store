<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;
    protected function casts(): array
    {
        return [
            'start_date'=>'datetime',
            'end_date'=>'datetime',
        ];
    }
    protected $fillable = ['offer_name', 'offer_quantity', 'free_quantity','start_date','end_date', 'customer_type', 'product_id'];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
