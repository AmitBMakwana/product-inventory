<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubcategoryRequest;
use App\Models\Subcategory;
use App\Models\Category;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::with('category')->get();

        return view('subcategories.index', compact('subcategories'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('subcategories.create', compact('categories'));
    }

    public function store(SubcategoryRequest $request)
    {
        $validated = $request->validated();

        if (Subcategory::where('name', $validated['name'])->where('cat_id', $validated['cat_id'])->exists()) {
            return redirect()->back()->withErrors(['name' => 'Subcategory already exists under this category.']);
        }
        
        Subcategory::createSubcategory($request);

        return redirect()->route('subcategories.index')->with('success', 'Subcategory created successfully.');
    }

    public function edit(Subcategory $subcategory)
    {
        $categories = Category::all();

        return view('subcategories.edit', compact('subcategory', 'categories'));
    }

    public function update(SubcategoryRequest $request, Subcategory $subcategory)
    {
        $validated = $request->validated();

        if (Subcategory::where('name', $validated['name'])->where('cat_id', $validated['cat_id'])->where('id', '!=', $subcategory->id)->exists()) {
            return redirect()->back()->withErrors(['name' => 'Subcategory already exists under this category.']);
        }

        Subcategory::updateSubcategory($request, $subcategory);

        return redirect()->route('subcategories.index')->with('success', 'Subcategory updated successfully.');
    }

    public function destroy(Subcategory $subcategory)
    {
        Subcategory::deleteSubcategory($subcategory);

        return redirect()->route('subcategories.index')->with('success', 'Subcategory deleted successfully.');
    }
}