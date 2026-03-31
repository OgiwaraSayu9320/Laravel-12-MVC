<!DOCTYPE html>
<html>
<head>
    <title>Liên hệ mới</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; padding: 20px; border: 1px solid #eee; border-radius: 5px; }
        .header { background: #f8f9fa; padding: 10px; border-bottom: 2px solid #007bff; margin-bottom: 20px; }
        .info-row { margin-bottom: 10px; }
        .label { font-weight: bold; width: 120px; display: inline-block; }
        .content { background: #fcfcfc; padding: 15px; border: 1px inset #eee; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Bạn có liên hệ mới từ Website</h2>
        </div>
        <div class="info-row">
            <span class="label">Họ tên:</span> {{ $contact->ho_ten }}
        </div>
        <div class="info-row">
            <span class="label">Email:</span> {{ $contact->email }}
        </div>
        <div class="info-row">
            <span class="label">Điện thoại:</span> {{ $contact->phone ?? 'Không có' }}
        </div>
        <div class="info-row">
            <span class="label">Loại:</span> {{ $contact->type }}
        </div>
        <div class="info-row">
            <span class="label">Thời gian:</span> {{ $contact->created_at->format('d/m/Y H:i') }}
        </div>
        <hr>
        <div class="info-row">
            <span class="label">Nội dung:</span>
        </div>
        <div class="content">
            {!! nl2br(e($contact->noi_dung)) !!}
        </div>
    </div>
</body>
</html>
