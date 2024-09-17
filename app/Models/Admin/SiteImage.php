<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteImage extends Model
{
    use HasFactory;
    protected $table = 'site_images';
    protected $fillable = ['logo','offer_one','offer_two','slider_image','footer_image'];

}
