/**
 * Các tiện ích JavaScript cho bảng dữ liệu
 */

document.addEventListener('DOMContentLoaded', function() {
    // Xử lý checkbox "Chọn tất cả"
    const selectAllCheckbox = document.getElementById('select-all');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name]');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });

        // Cập nhật trạng thái "Chọn tất cả" khi các checkbox riêng lẻ thay đổi
        document.addEventListener('change', function(e) {
            if (e.target && e.target.type === 'checkbox' && e.target.name && e.target.name.includes('[]')) {
                const checkboxes = document.querySelectorAll('input[type="checkbox"][name="' + e.target.name + '"]');
                const checkedCount = document.querySelectorAll('input[type="checkbox"][name="' + e.target.name + '"]:checked').length;
                selectAllCheckbox.checked = checkedCount === checkboxes.length;
            }
        });
    }

    // Xử lý form xóa nhiều mục
    const deleteForm = document.getElementById('delete-form');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            const checkboxes = deleteForm.querySelectorAll('input[type="checkbox"][name]:checked');
            
            if (checkboxes.length === 0) {
                e.preventDefault();
                alert('Vui lòng chọn ít nhất một mục để xóa.');
                return false;
            }
            
            if (!confirm('Bạn có chắc chắn muốn xóa các mục đã chọn?')) {
                e.preventDefault();
                return false;
            }
            
            // Cập nhật input hidden với danh sách ID
            const selectedIds = Array.from(checkboxes).map(cb => cb.value);
            const hiddenInput = deleteForm.querySelector('input[name="selected_ids"]');
            if (hiddenInput) {
                hiddenInput.value = selectedIds.join(',');
            }
        });
    }

    // Xử lý form khôi phục nhiều mục
    const restoreForm = document.getElementById('restore-form');
    if (restoreForm) {
        restoreForm.addEventListener('submit', function(e) {
            const checkboxes = restoreForm.querySelectorAll('input[type="checkbox"][name]:checked');
            
            if (checkboxes.length === 0) {
                e.preventDefault();
                alert('Vui lòng chọn ít nhất một mục để khôi phục.');
                return false;
            }
            
            if (!confirm('Bạn có chắc chắn muốn khôi phục các mục đã chọn?')) {
                e.preventDefault();
                return false;
            }
            
            // Cập nhật input hidden với danh sách ID
            const selectedIds = Array.from(checkboxes).map(cb => cb.value);
            const hiddenInput = restoreForm.querySelector('input[name="selected_ids"]');
            if (hiddenInput) {
                hiddenInput.value = selectedIds.join(',');
            }
        });

        // Xử lý nút xóa vĩnh viễn nhiều mục
        const forceDeleteBtn = document.getElementById('force-delete-btn');
        if (forceDeleteBtn) {
            forceDeleteBtn.addEventListener('click', function(e) {
                const checkboxes = restoreForm.querySelectorAll('input[type="checkbox"][name]:checked');
                
                if (checkboxes.length === 0) {
                    alert('Vui lòng chọn ít nhất một mục để xóa vĩnh viễn.');
                    return false;
                }
                
                if (confirm('Bạn có chắc chắn muốn xóa vĩnh viễn các mục đã chọn? Hành động này không thể hoàn tác!')) {
                    // Cập nhật input hidden với danh sách ID
                    const selectedIds = Array.from(checkboxes).map(cb => cb.value);
                    const hiddenInput = restoreForm.querySelector('input[name="selected_ids"]');
                    if (hiddenInput) {
                        hiddenInput.value = selectedIds.join(',');
                    }
                    
                    // Thay đổi action của form và submit
                    restoreForm.action = restoreForm.getAttribute('data-force-delete-url') || '';
                    restoreForm.submit();
                }
            });
        }
    }

    // Xử lý xác nhận xóa một mục
    document.addEventListener('click', function(e) {
        if (e.target && e.target.closest('.delete-item')) {
            if (!confirm('Bạn có chắc chắn muốn xóa mục này?')) {
                e.preventDefault();
                return false;
            }
        }
    });

    // Xử lý xác nhận khôi phục một mục
    document.addEventListener('click', function(e) {
        if (e.target && e.target.closest('.restore-item')) {
            if (!confirm('Bạn có chắc chắn muốn khôi phục mục này?')) {
                e.preventDefault();
                return false;
            }
        }
    });

    // Xử lý xác nhận xóa vĩnh viễn một mục
    document.addEventListener('click', function(e) {
        if (e.target && e.target.closest('.delete-permanent')) {
            if (!confirm('Bạn có chắc chắn muốn xóa vĩnh viễn mục này? Hành động này không thể hoàn tác!')) {
                e.preventDefault();
                return false;
            }
        }
    });
}); 