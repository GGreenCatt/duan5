<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class CategoryController extends Controller
{
    // Trang danh sách danh mục
    public function index()
    {
        $categories = Category::with('parent')->get();
        $parentCategories = Category::whereNull('parent_id')->get();

        return view('categories.index', compact('categories', 'parentCategories'));
    }

    // Trang tạo danh mục
    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('categories.create', compact('parentCategories'));
    }

    // Lưu danh mục mới
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'parent_id' => 'nullable|exists:categories,id',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $category = new Category();
    $category->name = $validated['name'];
    $category->parent_id = $request->input('parent_id') ?: null;
    $category->description = $validated['description'] ?? null;
    $category->author = Auth::user()->name;

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('categories/banners', 'public');
        $category->image = $path;
    }

    $category->save();

    return redirect()->route('categories.index')->with('success', 'Tạo danh mục thành công!');
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'parent_id' => 'nullable|exists:categories,id|not_in:' . $id,
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $category = Category::findOrFail($id);
    $category->name = $request->name;
    $category->parent_id = $request->parent_id ?: null;
    $category->description = $request->description;

    if ($request->hasFile('image')) {
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }
        $path = $request->file('image')->store('categories/banners', 'public');
        $category->image = $path;
    }

    $category->save();

    return redirect()->route('categories.index')->with('success', 'Cập nhật danh mục thành công!');
}

public function deleteImage(Category $category)
{
    if ($category->image) {
        Storage::disk('public')->delete($category->image);
        $category->image = null;
        $category->save();
    }
    return redirect()->back()->with('success', 'Ảnh banner đã được xoá.');
}

public function destroy($id)
{
    $category = Category::findOrFail($id);

    if ($category->image && Storage::disk('public')->exists($category->image)) {
        Storage::disk('public')->delete($category->image);
    }

    $category->delete();

    return redirect()->route('categories.index')->with('success', 'Xoá danh mục thành công!');
}

    // Hiển thị bài viết thuộc danh mục (và các danh mục con)
    public function show($id)
    {
        $category = Category::with('children')->findOrFail($id);
        $categoryIds = $this->getAllCategoryIds($category);

        $posts = Post::whereIn('category_id', $categoryIds)->paginate(10);

        return view('categories.show', compact('category', 'posts'));
    }

    // Đệ quy lấy ID của danh mục và các con
    private function getAllCategoryIds($category)
    {
        $ids = [$category->id];
        $category->load('children');

        foreach ($category->children as $child) {
            $ids = array_merge($ids, $this->getAllCategoryIds($child));
        }

        return $ids;
    }
    public function edit(Category $category)
{
    // Lấy danh sách danh mục cha để chọn trong select, tránh chọn chính nó
    $parentCategories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();

    return view('categories.edit', compact('category', 'parentCategories'));
}
}
