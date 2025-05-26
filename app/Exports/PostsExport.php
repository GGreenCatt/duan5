<?php

namespace App\Exports;

use App\Models\Post;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles; // Add this line
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet; // Add this line

class PostsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles // Add WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Lấy tất cả bài viết, eager load category và user
        // Bạn có thể thêm các điều kiện lọc ở đây nếu cần
        return Post::with(['category.parent', 'user'])->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Tiêu đề',
            'Mô tả ngắn',
            'Nội dung', // Sẽ là nội dung đầy đủ
            'Danh mục',
            'Người đăng',
            'Ngày tạo',
            'Ngày cập nhật',
        ];
    }

    /**
     * @param mixed $post
     * @return array
     */
    public function map($post): array
    {
        $categoryName = '';
        if ($post->category) {
            $categoryName = $post->category->parent ? $post->category->parent->name . ' → ' : '';
            $categoryName .= $post->category->name;
        }

        return [
            $post->id,
            $post->title,
            html_entity_decode(strip_tags($post->short_description), ENT_QUOTES, 'UTF-8'), // Giải mã entities sau khi strip tags
            html_entity_decode(strip_tags($post->content), ENT_QUOTES, 'UTF-8'),          // Giải mã entities sau khi strip tags
            $categoryName,
            $post->user->name ?? 'N/A',
            $post->created_at->format('d/m/Y H:i:s'),
            $post->updated_at->format('d/m/Y H:i:s'),
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // Apply bold styling to the header row (row 1)
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        // You can add more styling here if needed
    }
}
