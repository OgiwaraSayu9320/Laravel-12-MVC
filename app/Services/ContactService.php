<?php

namespace App\Services;

use App\Models\Contact;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactService
{
    /**
     * Lưu liên hệ và gửi mail thông báo.
     */
    public function submit(array $data): Contact
    {
        $contact = Contact::create([
            'type'     => $data['type'] ?? 'lien-he',
            'ho_ten'   => $data['ho_ten'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'noi_dung' => $data['noi_dung'],
        ]);

        // Gửi email cho admin
        try {
            Mail::to(config('mail.admin_email'))->send(new ContactMail($contact));
        } catch (\Exception $e) {
            // Log lỗi nếu gửi mail thất bại nhưng vẫn lưu DB thành công
            \Log::error('Send Contact Mail Error: ' . $e->getMessage());
        }

        return $contact;
    }

    /**
     * Danh sách liên hệ cho Admin.
     */
    public function getListing(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Contact::latest();

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['da_doc']) && $filters['da_doc'] !== '') {
            $query->where('da_doc', $filters['da_doc'] == '1');
        }

        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('ho_ten', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('phone', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Đánh dấu đã đọc.
     */
    public function markAsRead(Contact $contact): void
    {
        $contact->update(['da_doc' => true]);
    }
}
