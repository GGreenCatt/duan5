<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy tất cả bài viết và trả về dưới dạng JSON
        // Sử dụng PostResource để định dạng dữ liệu trả về
        return PostResource::collection(Post::all());
    }
}