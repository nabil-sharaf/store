<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestAddress extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'guest_addresses';
}
