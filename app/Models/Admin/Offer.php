<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Offer extends Model implements Auditable
{
    use HasFactory ;
    use \OwenIt\Auditing\Auditable;

    protected function casts(): array
    {
        return [
            'start_date'=>'datetime',
            'end_date'=>'datetime',
        ];
    }
    protected $fillable = ['offer_name', 'offer_quantity', 'free_quantity','start_date','end_date', 'customer_type', 'product_id','variant_id'];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }
}
