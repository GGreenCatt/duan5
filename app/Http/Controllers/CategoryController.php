<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories_paginated = Category::with(['children.posts', 'posts'])
                                ->whereNull('parent_id')
                                ->withCount('posts')
                                ->orderBy('name', 'asc')
                                ->paginate(5);
        $all_parent_categories = Category::whereNull('parent_id')->orderBy('name', 'asc')->get();
        
        return view('categories.index', [
            'categories' => $categories_paginated,
            'parent_categories_for_form' => $all_parent_categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255|unique:categories',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);
    
        $category = new Category($validatedData);
    
        if ($request->hasFile('image')) {
            $category->image = $request->file('image')->store('category_images', 'public');
        }
    
        $category->save();
    
        return redirect()->route('categories.index')->with('success', 'Danh mục đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
         $posts = $category->posts()->paginate(10); 
        return view('categories.show', compact('category', 'posts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        // ===== ĐÃ CẬP NHẬT TÊN BIẾN CHO ĐÚNG =====
        // View edit.blade.php cần biến $categories cho dropdown
        $categories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        return view('categories.edit', compact('category', 'categories'));
        // ==========================================
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $category->id,
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $category->fill($validatedData);

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $category->image = $request->file('image')->store('category_images', 'public');
        }

        $category->save();

        return redirect()->route('categories.index')->with('success', 'Danh mục đã được cập nhật thành công.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
    
        $category->delete();
    
        return redirect()->route('categories.index')->with('success', 'Danh mục đã được xóa thành công.');
    }

     public function deleteImage(Category $category)
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
            $category->image = null;
            $category->save();
            return back()->with('success', 'Ảnh danh mục đã được xóa.');
        }
        return back()->with('error', 'Không có ảnh để xóa.');
    }
}