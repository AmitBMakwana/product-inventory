<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();

        if (Category::where('name', $validated['name'])->exists()) {
            return redirect()->back()->withErrors(['name' => 'Category already exists.']);
        }
        
        Category::createCategory($request);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $validated = $request->validated();

        if (Category::where('name', $validated['name'])->where('id', '!=', $category->id)->exists()) {
            return redirect()->back()->withErrors(['name' => 'Category already exists.']);
        }

        Category::updateCategory($request, $category);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        Category::deleteCategory($category);

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}