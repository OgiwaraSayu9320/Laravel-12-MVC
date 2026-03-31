<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasImageUpload
{
    /**
     * Tải hình ảnh lên và trả về đường dẫn lưu trữ.
     *
     * @param UploadedFile $file File hình ảnh tải lên.
     * @param string $folder Thư mục mục tiêu (ví dụ: 'products', 'news').
     * @return string Đường dẫn tương đối của file đã lưu.
     */
    protected function uploadImage(UploadedFile $file, string $folder): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs($folder, $filename, 'public');
        return $folder . '/' . $filename;
    }

    /**
     * Xóa hình ảnh khỏi lưu trữ nếu tồn tại.
     *
     * @param string|null $path Đường dẫn hình ảnh cần xóa.
     * @return void
     */
    protected function deleteImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
