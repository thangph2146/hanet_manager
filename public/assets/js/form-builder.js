/**
 * FormBuilder JS - Quản lý khởi tạo các trường form đặc biệt
 */
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo các trường thời gian
    function initTimepickers() {
        if (typeof flatpickr === 'undefined') {
            console.warn('Flatpickr không được tải. Các trường thời gian sẽ sử dụng controls mặc định của trình duyệt.');
            return;
        }
        
        // Khởi tạo các trường timepicker
        if (document.querySelectorAll('.timepicker').length > 0) {
            flatpickr(".timepicker", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true
            });
        }
        
        // Khởi tạo các trường datepicker
        if (document.querySelectorAll('.datepicker').length > 0) {
            flatpickr(".datepicker", {
                enableTime: false,
                dateFormat: "Y-m-d"
            });
        }
        
        // Khởi tạo các trường datetimepicker
        if (document.querySelectorAll('.datetimepicker').length > 0) {
            flatpickr(".datetimepicker", {
                enableTime: true,
                dateFormat: "Y-m-d H:i"
            });
        }
    }

    // Khởi tạo tất cả các controls
    function initFormControls() {
        initTimepickers();

        // Khởi tạo Select2 nếu có
        if (typeof $.fn.select2 !== 'undefined' && document.querySelectorAll('.select2').length > 0) {
            $('.select2').select2();
        }
    }

    // Chạy khởi tạo
    initFormControls();

    // Định nghĩa một phương thức global để khởi tạo lại khi cần (ví dụ: sau khi tải form bằng AJAX)
    window.reinitFormBuilder = function() {
        initFormControls();
    };
}); 