<!DOCTYPE html>
<html lang="en">

{{-- Nhúng Head --}}
@include('admin.layouts.head')

<body>

    <div class="container">
        <div class="row">
            {{-- Nhúng Sidebar --}}
            @include('admin.layouts.sidebar')

            <div class="col-md-10 offset-md-2 main-content">
                {{-- Nhúng Header --}}
                @include('admin.layouts.header')

                <div class="d-flex flex-column pt-3" style="min-height: calc(100vh - 61px);">
                    <main class="flex-fill">
                        {{-- Phần nội dung thay đổi (Main Content) --}}
                        @yield('content')
                    </main>
                    {{-- Nhúng Footer --}}
                    @include('admin.layouts.footer')
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>

    {{-- Script cấu hình Editor dùng chung --}}
    <script>
        // 1. CẤU HÌNH EDITOR RÚT GỌN (Cho mô tả ngắn)
        function initShortEditors() {
            const shortEditors = document.querySelectorAll('.editor-short');
            shortEditors.forEach((element) => {
                ClassicEditor
                    .create(element, {
                        toolbar: ['bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'undo',
                            'redo'
                        ],
                        height: 300 // Chỉnh chiều cao nếu cần
                    })
                    .catch(error => console.error(error));
            });
        }

        // 2. CẤU HÌNH EDITOR FULL (Cho nội dung chi tiết)
        function initFullEditors() {
            const fullEditors = document.querySelectorAll('.editor-full');
            fullEditors.forEach((element) => {
                ClassicEditor
                    .create(element, {
                        // Cấu hình Toolbar đầy đủ
                        toolbar: [
                            'heading', '|',
                            'bold', 'italic', 'underline', 'strikethrough', '|',
                            'link', 'uploadImage', 'blockQuote', 'insertTable', 'mediaEmbed', '|',
                            'bulletedList', 'numberedList', 'outdent', 'indent', '|',
                            'undo', 'redo'
                        ],
                        // Cấu hình Upload ảnh
                        ckfinder: {
                            uploadUrl: "{{ route('admin.upload.media') }}?_token={{ csrf_token() }}"
                        },
                        language: 'vi' // Nếu muốn tiếng Việt
                    })
                    .then(editor => {
                        // Fix lỗi chiều cao (mặc định CKEditor khá thấp)
                        editor.ui.view.editable.element.style.minHeight = '300px';
                    })
                    .catch(error => console.error(error));
            });
        }

        // Tự động chạy khi load trang
        document.addEventListener('DOMContentLoaded', function() {
            initShortEditors();
            initFullEditors();
        });
    </script>

    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {

            // 1. Kiểm tra xem trang này có phải được load từ nút BACK/FORWARD không?
            var isBackNavigation = false;

            // Cách kiểm tra cho trình duyệt đời mới
            if (window.performance && window.performance.getEntriesByType) {
                var navEntries = window.performance.getEntriesByType('navigation');
                if (navEntries.length > 0 && navEntries[0].type === 'back_forward') {
                    isBackNavigation = true;
                }
            }
            // Fallback cho trình duyệt cũ
            else if (window.performance && window.performance.navigation) {
                if (window.performance.navigation.type === 2) {
                    isBackNavigation = true;
                }
            }

            // 2. Chỉ hiển thị thông báo nếu KHÔNG PHẢI là nút Back
            if (!isBackNavigation) {

                // Dùng pull() để lấy và xóa session ngay lập tức (như đã bàn ở trên)
                @if (session()->has('success'))
                    toastr.success("{{ session()->pull('success') }}");
                @endif

                @if (session()->has('error'))
                    toastr.error("{{ session()->pull('error') }}");
                @endif

                @if (session()->has('info'))
                    toastr.info("{{ session()->pull('info') }}");
                @endif

                @if (session()->has('warning'))
                    toastr.warning("{{ session()->pull('warning') }}");
                @endif
            }

            // Phần lỗi validate form ($errors) thì cứ để hiện, 
            // vì nếu Back lại form bị lỗi thì người dùng cần biết để sửa.
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            @endif
        });
    </script>

    @stack('scripts')
</body>

</html>
