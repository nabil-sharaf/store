<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['setting_key', 'setting_value','setting_type','description','social_type'];

    public static function getValue($key)
    {
        $setting = self::where('setting_key', $key)->first();
        return $setting ? $setting->setting_value : null;
    }
}
