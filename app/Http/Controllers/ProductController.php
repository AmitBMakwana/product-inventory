<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DataTables;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    // public function getData()
    // {
    //     $data = Product::select(['id', 'name', 'cat_id', 'sub_cat_id', 'price', 'qty', 'status', 'created_at', 'updated_at', 'deleted_at'])
    //         ->with('category', 'subcategory')
    //         ->get();

    //     return DataTables::of($data)->make(true);
    // }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::select(['id', 'name', 'cat_id', 'sub_cat_id', 'price', 'qty', 'status', 'created_at', 'updated_at', 'deleted_at'])
            ->with('category', 'subcategory')
            ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function search(Request $request)
    {
        dd("dd");
        $searchTerm = $request->input('search');
        $products = Product::whereHas('category', function ($query) use ($searchTerm) {
            $query->where('name', 'LIKE', "%$searchTerm%");
        })->get();
        return view('products.index', compact('products'));
    }


    public function create()
    {
        $categories = Category::all();
        $subCategories = Subcategory::all();
        return view('products.create', compact('categories','subCategories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:5|max:20',
            'cat_id' => 'required',
            'sub_cat_id' => 'required',
            'price' => 'required|numeric|min:0',
            'qty' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);
        
        $category = Category::find($validatedData['cat_id']);
        if (!$category) {
            return redirect()->back()->withErrors(['cat_id' => 'Invalid category selected.'])->withInput();
        }

        $product = new Product();
        $product->name = $validatedData['name'];
        $product->cat_id = $validatedData['cat_id'];
        $product->sub_cat_id = $validatedData['sub_cat_id'];
        $product->price = $validatedData['price'];
        $product->qty = $validatedData['qty'];
        $product->status = $request->input('status', true);
        $product->save();

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $subCategories = Subcategory::all();
        return view('products.edit', compact('product', 'categories','subCategories'));
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:5|max:20',
            'cat_id' => 'required',
            'sub_cat_id' => 'required',
            'price' => 'required|numeric|min:0',
            'qty' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $category = Category::find($validatedData['cat_id']);
        if (!$category) {
            return redirect()->back()->withErrors(['cat_id' => 'Invalid category selected.'])->withInput();
        }

        $product->name = $validatedData['name'];
        $product->cat_id = $validatedData['cat_id'];
        $product->sub_cat_id = $validatedData['sub_cat_id'];
        $product->price = $validatedData['price'];
        $product->qty = $validatedData['qty'];
        $product->status = $request->input('status', true);
        $product->save();

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
