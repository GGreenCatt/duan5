<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Thêm dòng này

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'gallery_images' => 'array',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // Cập nhật: Thêm 'slug' vào $fillable để cho phép mass assignment
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'content',
        'category_id',
        'user_id',
        'banner_image',
        'gallery_images',
    ];

    /**
     * Cập nhật: Tự động tạo slug khi lưu model.
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::saving(function ($post) {
            // Chỉ tạo slug nếu tiêu đề thay đổi hoặc slug đang trống
            if ($post->isDirty('title') || empty($post->slug)) {
                $slug = Str::slug($post->title);
                $originalSlug = $slug;
                $count = 1;
                // Đảm bảo slug là duy nhất, nếu trùng thì thêm hậu tố -1, -2, ...
                while (static::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                    $slug = "{$originalSlug}-" . $count++;
                }
                $post->slug = $slug;
            }
        });
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function interactions()
    {
        return $this->hasMany(PostInteraction::class);
    }

    public function likes()
    {
        return $this->hasMany(PostInteraction::class)->where('type', 'like');
    }

    public function dislikes()
    {
        return $this->hasMany(PostInteraction::class)->where('type', 'dislike');
    }
}