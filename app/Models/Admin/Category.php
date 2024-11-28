<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;


class Category extends Model implements Auditable
{
    use HasFactory ;
    use \OwenIt\Auditing\Auditable;


    protected $fillable = ['name','description','parent_id','image'];

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('children');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }


    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function allProducts() {
        return $this->products()->with('category.allProducts');
    }

}
