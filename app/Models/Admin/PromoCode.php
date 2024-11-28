<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PromoCode extends Model implements Auditable
{
    use HasFactory ;
    use \OwenIt\Auditing\Auditable;


    protected $fillable = ['code', 'discount', 'discount_type','start_date', 'end_date', 'active','min_amount'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function isActive()
    {
        $now = now();
        return $this->active && $this->start_date <= $now && $this->end_date >= $now;
    }
}
