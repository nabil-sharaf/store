<?php

namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use HasFactory;
    

    protected $fillable = ['name','description','parent_id'];

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
