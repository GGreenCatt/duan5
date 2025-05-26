<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // Các trường có thể mass assignable
    protected $fillable = [
        'user_id',
        'category_id',           // Liên kết danh mục
        'title',
        'short_description',
        'content',
        'banner_image',
        'gallery_images',
    ];

    // Chuyển đổi kiểu dữ liệu cho các trường nhất định
    protected $casts = [
        'gallery_images' => 'array', // Tự động cast JSON thành mảng
    ];

    /**
     * Mỗi bài viết thuộc về một người dùng (tác giả)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mỗi bài viết thuộc về một danh mục
     */
    public function category()
    {
            return $this->belongsTo(Category::class);;
    }
}
