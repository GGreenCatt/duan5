<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Tải tất cả danh mục cha, cùng với danh mục con của chúng
        // và đếm số bài viết cho mỗi danh mục (cả cha và con) một cách hiệu quả.
        $categories = Category::whereNull('parent_id')
                                ->with(['children' => function ($query) {
                                    $query->withCount('posts'); // Đếm bài viết cho từng danh mục con
                                }])
                                ->withCount('posts') // Đếm bài viết cho danh mục cha
                                ->orderBy('name')
                                ->get();

        return view('categories.index', compact('categories'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')->get();
        return view('categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')->with('success', 'Danh mục đã được tạo thành công.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $categories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        return view('categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Danh mục đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Xóa các danh mục con trước (nếu có)
        $category->children()->delete();
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Danh mục và các danh mục con đã được xóa.');
    }
}