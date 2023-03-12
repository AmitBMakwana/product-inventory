<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'status'
    ];

    protected $dates = ['deleted_at'];

    public function products()
    {
        return $this->hasMany(Product::class, 'cat_id');
    }

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public static function createCategory($request)
    {
        return Category::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);
    }

    public static function updateCategory($request, $category)
    {
        $category->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return $category;
    }

    public static function deleteCategory($category)
    {
        return $category->delete();
    }

    public static function getCategories()
    {
        return Category::all();
    }

    public static function getCategoryById($id)
    {
        return Category::findOrFail($id);
    }
}