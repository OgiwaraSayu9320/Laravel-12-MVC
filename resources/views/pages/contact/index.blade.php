@extends('layouts.main')

@php
    $lang = app()->getLocale();
@endphp

@section('title', $seo ? ($lang == 'vi' ? $seo->titlevi : $seo->titleen) : 'Contact')

@section('seo')
@if($seo)
    <meta name="description" content="{{ $lang == 'vi' ? $seo->descriptionvi : $seo->descriptionen }}">
    <meta name="keywords" content="{{ $lang == 'vi' ? $seo->keywordsvi : $seo->keywordsen }}">
@endif
@endsection

@section('content')
    <section style="padding: 80px 0; background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1523966211575-eb4a01e7dd51?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80'); background-size: cover; background-position: center; color: white;">
        <div class="container" style="text-align: center;">
            <h1 style="font-size: 3.5rem; font-weight: 800; margin-bottom: 20px;">
                {{ $lang == 'vi' ? 'Kết Nối Với Chúng Tôi' : 'Connect With Us' }}
            </h1>
            <p style="font-size: 1.25rem; opacity: 0.9; max-width: 600px; margin: 0 auto;">
                {{ $lang == 'vi' ? 'Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn.' : 'We are always ready to listen and support you.' }}
            </p>
        </div>
    </section>

    <section style="padding: 80px 0; background: white;">
        <div class="container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 60px;">
            <!-- Contact Info -->
            <div>
                <h2 style="font-size: 2rem; font-weight: 800; margin-bottom: 30px; color: var(--text-color);">
                    {{ $lang == 'vi' ? 'Thông tin liên hệ' : 'Contact Information' }}
                </h2>
                <p style="color: #64748b; line-height: 1.8; margin-bottom: 40px;">
                    {{ $lang == 'vi' ? 'Hãy liên hệ với chúng tôi qua bất kỳ kênh nào dưới đây hoặc điền vào biểu mẫu bên cạnh.' : 'Contact us through any of the channels below or fill out the form beside.' }}
                </p>

                <div style="display: grid; gap: 30px;">
                    <div style="display: flex; gap: 20px; align-items: flex-start;">
                        <div style="width: 50px; height: 50px; background: #eff6ff; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; color: var(--primary-color); font-size: 1.5rem; flex-shrink: 0;">📍</div>
                        <div>
                            <h4 style="font-weight: 700; margin-bottom: 5px;">{{ $lang == 'vi' ? 'Địa chỉ' : 'Address' }}</h4>
                            <p style="color: #64748b;">District 1, Ho Chi Minh City, Vietnam</p>
                        </div>
                    </div>
                    <div style="display: flex; gap: 20px; align-items: flex-start;">
                        <div style="width: 50px; height: 50px; background: #ecfdf5; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; color: #10b981; font-size: 1.5rem; flex-shrink: 0;">📞</div>
                        <div>
                            <h4 style="font-weight: 700; margin-bottom: 5px;">{{ $lang == 'vi' ? 'Điện thoại' : 'Phone' }}</h4>
                            <p style="color: #64748b;">+84 123 456 789</p>
                        </div>
                    </div>
                    <div style="display: flex; gap: 20px; align-items: flex-start;">
                        <div style="width: 50px; height: 50px; background: #fef2f2; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; color: #ef4444; font-size: 1.5rem; flex-shrink: 0;">✉️</div>
                        <div>
                            <h4 style="font-weight: 700; margin-bottom: 5px;">Email</h4>
                            <p style="color: #64748b;">support@antigravity.site</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div style="background: white; border: 1px solid var(--border-color); border-radius: 1.5rem; padding: 40px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
                <h3 style="font-size: 1.5rem; font-weight: 800; margin-bottom: 30px;">{{ $lang == 'vi' ? 'Gửi tin nhắn' : 'Send us a message' }}</h3>
                
                @if(session('success'))
                    <div style="padding: 15px; background: #ecfdf5; color: #065f46; border-radius: 0.5rem; margin-bottom: 20px; font-weight: 600;">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('contact.store', ['lang' => $lang]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="lien-he">
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 8px;">{{ $lang == 'vi' ? 'Họ và tên' : 'Full Name' }} *</label>
                        <input type="text" name="ho_ten" value="{{ old('ho_ten') }}" style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('ho_ten') ? '#ef4444' : 'var(--border-color)' }}; border-radius: 0.5rem;" required>
                        @error('ho_ten') <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span> @enderror
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 8px;">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('email') ? '#ef4444' : 'var(--border-color)' }}; border-radius: 0.5rem;" required>
                        @error('email') <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span> @enderror
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 8px;">{{ $lang == 'vi' ? 'Số điện thoại' : 'Phone Number' }}</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('phone') ? '#ef4444' : 'var(--border-color)' }}; border-radius: 0.5rem;">
                        @error('phone') <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span> @enderror
                    </div>
                    <div style="margin-bottom: 30px;">
                        <label style="display: block; font-weight: 600; margin-bottom: 8px;">{{ $lang == 'vi' ? 'Nội dung' : 'Message' }} *</label>
                        <textarea name="noi_dung" style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('noi_dung') ? '#ef4444' : 'var(--border-color)' }}; border-radius: 0.5rem; height: 120px;" required>{{ old('noi_dung') }}</textarea>
                        @error('noi_dung') <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem; font-size: 1rem;">
                        {{ $lang == 'vi' ? 'Gửi ngay' : 'Send Message' }}
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section style="height: 400px; background: #eee;">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15677.291771971714!2d106.69176375!3d10.78657685!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f38f9ed0e51%3A0x62955f3032543324!2sDistrict%201%2C%20Ho%20Chi%20Minh%20City%2C%20Vietnam!5e0!3m2!1sen!2s!4v1617180000000!5m2!1sen!2s"
                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </section>
@endsection
