<?php

namespace App\Exports;

use App\Models\Post;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Str;
class BaiDangExport implements FromCollection, WithHeadings
{
public function collection()
{
    return Post::with(['category.parent', 'user'])->get()->map(function ($item) {
        // Gộp danh mục cha/con
        $categoryName = 'Không có';
        if ($item->category) {
            $categoryName = $item->category->name;
            if ($item->category->parent) {
                $categoryName = $item->category->parent->name . ' / ' . $categoryName;
            }
        }

        return [
            'Người đăng'      => $item->user->name ?? 'Không rõ',
            'Tiêu đề'         => $item->title,
            'Mô tả ngắn'      => html_entity_decode($item->short_description), // Giải mã HTML entity
            'Nội dung' => trim(strip_tags(html_entity_decode($item->content))),
            'Danh mục'        => $categoryName,
        ];
    });
}


    public function headings(): array
    {
        return [
            'Người đăng',
            'Tiêu đề',
            'Mô tả ngắn',
            'Nội dung',
            'Danh mục',
        ];
    }
}
