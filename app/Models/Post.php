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
        'title',
        'short_description',
        'content',
        'banner_image',
        'gallery_images',
    ];

    // Các trường sẽ được chuyển đổi sang kiểu dữ liệu khác, ví dụ như mảng cho trường gallery_images
    protected $casts = [
        'gallery_images' => 'array', // Định dạng gallery_images là mảng
    ];

    /**
     * Quan hệ với User (Mỗi bài viết thuộc về một người dùng)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
