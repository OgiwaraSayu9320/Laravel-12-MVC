<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Lưu vào storage/app/public/uploads
            $file->storeAs('uploads', $filename, 'public');

            // CKEditor yêu cầu trả về JSON theo format này
            return response()->json([
                'uploaded' => true,
                'url' => asset('storage/uploads/' . $filename)
            ]);
        }

        return response()->json(['uploaded' => false, 'error' => ['message' => 'Lỗi upload']]);
    }
}