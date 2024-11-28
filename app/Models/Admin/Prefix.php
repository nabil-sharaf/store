<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Prefix extends Model
{
    use HasFactory,HasTranslations;
    public $translatable = ['name'];
    protected $fillable = ['name','prefix_code'];
}
