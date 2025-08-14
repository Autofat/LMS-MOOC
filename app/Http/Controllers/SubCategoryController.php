<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subCategories = SubCategory::with('category')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.sub-categories.index', compact('subCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.sub-categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean'
        ]);

        SubCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.sub-categories.index')
                        ->with('success', 'Sub kategori berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubCategory $subCategory)
    {
        $subCategory->load(['category', 'materials']);
        return view('admin.sub-categories.show', compact('subCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubCategory $subCategory)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.sub-categories.edit', compact('subCategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubCategory $subCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean'
        ]);

        $subCategory->update([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.sub-categories.index')
                        ->with('success', 'Sub kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete();
        return redirect()->route('admin.sub-categories.index')
                        ->with('success', 'Sub kategori berhasil dihapus.');
    }

    /**
     * Get sub categories by category ID (AJAX)
     */
    public function getByCategory($categoryId)
    {
        $subCategories = SubCategory::where('category_id', $categoryId)
                                   ->where('is_active', true)
                                   ->get(['id', 'name']);
        return response()->json($subCategories);
    }
}
