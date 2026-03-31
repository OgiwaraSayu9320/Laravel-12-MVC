<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasMultilingual
{
    public function getLangs()
    {
        return config('lang.langs');
    }

    public function getDefaultLang()
    {
        return config('lang.default_lang');
    }

    /**
     * 1. Tự động tạo Rules Validate
     * @param array $baseRules: Rules cơ bản (gia, hienthi...)
     * @param int|null $id: ID bản ghi (dùng cho update để check unique)
     */
    public function makeLangRules(array $baseRules = [], $id = null)
    {
        $langs = $this->getLangs();
        $default = $this->getDefaultLang();
        $table = $this->getTable();

        foreach ($langs as $key => $name) {
            $required = ($key == $default) ? 'required' : 'nullable';

            $baseRules['ten' . $key] = "$required|max:255";

            // Xử lý unique: Nếu có $id thì bỏ qua check trùng với chính nó
            $uniqueRule = "unique:{$table},tenkhongdau$key";
            if ($id) {
                $uniqueRule .= ",$id";
            }

            $baseRules['tenkhongdau' . $key] = "nullable|max:255|$uniqueRule";

            // Các trường khác
            $baseRules['mota' . $key] = 'nullable';
            $baseRules['noidung' . $key] = 'nullable';

            // SEO
            $baseRules['title' . $key] = 'nullable';
            $baseRules['keywords' . $key] = 'nullable';
            $baseRules['description' . $key] = 'nullable';
        }

        return $baseRules;
    }

    /**
     * 2. Tự động tạo Custom Attributes (Để báo lỗi tiếng Việt đẹp)
     */
    public function makeLangAttributes(array $baseAttributes = [])
    {
        $langs = $this->getLangs();

        foreach ($langs as $key => $name) {
            $baseAttributes['ten' . $key] = "Tên sản phẩm ($name)";
            $baseAttributes['tenkhongdau' . $key] = "Đường dẫn / Slug ($name)";
            $baseAttributes['mota' . $key] = "Mô tả ($name)";
            $baseAttributes['noidung' . $key] = "Nội dung ($name)";
        }

        return $baseAttributes;
    }

    /**
     * 3. Xử lý Slug và Auto Fill dữ liệu
     */
    public function processLangData(array $data)
    {
        $langs = $this->getLangs();

        // Tạo slug nếu trống
        foreach ($langs as $key => $name) {
            $ten = 'ten' . $key;
            $slug = 'tenkhongdau' . $key;

            if (!empty($data[$ten]) && empty($data[$slug])) {
                $data[$slug] = Str::slug($data[$ten]);
            }
        }

        // Auto fill Tiếng Anh (nếu cần)
        if (empty($data['tenen']) && !empty($data['tenvi'])) {
            $data['tenen'] = $data['tenvi'];
            $data['tenkhongdauen'] = Str::slug($data['tenen']);
        }

        // $default = $this->getDefaultLang();

        return $data;
    }
}