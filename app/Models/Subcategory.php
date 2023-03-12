<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cat_id',
        'name',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'cat_id');
    }

    public static function createSubcategory($request)
    {
        return self::create([
            'cat_id' => $request->cat_id,
            'name' => $request->name,
            'status' => $request->status,
        ]);
    }

    public static function updateSubcategory($request, $subcategory)
    {
        return $subcategory->update([
            'cat_id' => $request->cat_id,
            'name' => $request->name,
            'status' => $request->status,
        ]);
    }

    public static function deleteSubcategory($subcategory)
    {
        return $subcategory->delete();
    }
}