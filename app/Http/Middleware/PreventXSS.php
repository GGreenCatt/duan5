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

        array_walk_recursive($input, function (&$value) {
            $value = strip_tags($value); // Loại bỏ tất cả thẻ HTML/JS
            $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); // Mã hóa ký tự đặc biệt
        });

        $request->merge($input);

        return $next($request);
    }
}
