// Đây là file bootstrap.js của Laravel (dùng để cài đặt Axios gọi API)
import './bootstrap';
// Đây là thư viện Bootstrap 5 (để chạy Dropdown, Modal...)
import 'bootstrap';

import jQuery from 'jquery';
window.$ = window.jQuery = jQuery;

// Import Toastr 
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';
window.toastr = toastr;

toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": "5000"
};


$(document).ready(function () {
    $('[data-widget="pushmenu"]').on('click', function (e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-collapse');
    });
});

// $(document).ready(function () {

//     $('.nav-link.has-treeview').on('click', function (e) {
//         e.preventDefault();

//         let $parent = $(this).parent();
//         let $treeview = $parent.find('.nav-treeview');

//         $parent.toggleClass('menu-open');
//     });

// });

$(document).ready(function () {
    // Chỉ xử lý sự kiện CLICK bằng tay
    $('.has-treeview > a').on('click', function (e) {
        e.preventDefault(); // Ngăn chặn nhảy trang khi click thẻ <a> cha

        let $parentLi = $(this).parent('.has-treeview');

        // Bật/tắt class menu-open để xoay mũi tên
        $parentLi.toggleClass('menu-open');

        // Trượt đóng/mở menu con
        $parentLi.children('.nav-treeview').stop().slideToggle(300);
    });
});