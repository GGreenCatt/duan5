<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventXSS
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$value, $key) {
            // Chỉ xử lý nếu key không phải là 'content' (hoặc các trường khác bạn muốn loại trừ)
            // và giá trị là một chuỗi
            if ($key !== 'content' && is_string($value)) {
                $value = strip_tags($value); // Loại bỏ tất cả thẻ HTML/JS
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); // Mã hóa ký tự đặc biệt
            }
        });

        $request->merge($input);

        return $next($request);
    }
}
