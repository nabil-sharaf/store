<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class OptionValue extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['option_id', 'value'];

    public $translatable = ['value'];

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }
    public function variants()
    {
        return $this->belongsToMany(Variant::class, 'opion_value_variant', 'option_value_id', 'variant_id');
    }
}
