<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Thêm dòng này

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug', // Thêm slug vào fillable
        'description',
        'parent_id',
        'image',
    ];

    /**
     * The "booted" method of the model.
     * Tự động tạo slug khi lưu model.
     */
    protected static function booted(): void
    {
        static::saving(function ($category) {
            // Chỉ tạo slug nếu tên danh mục thay đổi hoặc slug đang trống
            if ($category->isDirty('name') || empty($category->slug)) {
                $slug = Str::slug($category->name);
                $originalSlug = $slug;
                $count = 1;
                // Đảm bảo slug là duy nhất
                while (static::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                    $slug = "{$originalSlug}-" . $count++;
                }
                $category->slug = $slug;
            }
        });
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}