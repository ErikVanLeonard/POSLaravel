<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category; // Added Category model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Added Storage facade for image handling

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10); // Eager load category and paginate
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all(); // Get all categories
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'barcode' => 'required|string|unique:products,barcode|max:255', // Barcode validation
            'precio_menudeo' => 'required|numeric|min:0',
            'precio_mayoreo' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id', // Category validation
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Image validation
        ]);

        $data = $request->except('image_path');

        if ($request->hasFile('image_path')) {
            $imageName = time().'.'.$request->image_path->extension();
            // Store in public/images/products, ensure 'products' folder exists or is created
            $request->image_path->storeAs('public/images/products', $imageName);
            $data['image_path'] = 'images/products/' . $imageName; // Store relative path for public access
        }

        Product::create($data);
        return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category'); // Eager load category for single product view
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all(); // Get all categories
        $product->load('category');
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'barcode' => 'required|string|max:255|unique:products,barcode,' . $product->id, // Barcode validation, ignore current product
            'precio_menudeo' => 'required|numeric|min:0',
            'precio_mayoreo' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id', // Category validation
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Image validation
        ]);

        $data = $request->except('image_path');

        if ($request->hasFile('image_path')) {
            // Delete old image if exists
            if ($product->image_path && Storage::exists('public/' . $product->image_path)) {
                Storage::delete('public/' . $product->image_path);
            }
            $imageName = time().'.'.$request->image_path->extension();
            $request->image_path->storeAs('public/images/products', $imageName);
            $data['image_path'] = 'images/products/' . $imageName;
        }

        $product->update($data);
        return redirect()->route('products.index')->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete image if exists
        if ($product->image_path && Storage::exists('public/' . $product->image_path)) {
            Storage::delete('public/' . $product->image_path);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Producto eliminado exitosamente.');
    }
}
