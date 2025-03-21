<?php

/**
 * Language file for LoaiNguoiDung (User Type) module in Vietnamese
 */

return [
    // Module information
    'moduleTitle'        => 'Loại Người Dùng',
    'manageTitle'        => 'Quản Lý Loại Người Dùng',
    'createNew'          => 'Thêm Loại Người Dùng',
    'editTitle'          => 'Chỉnh Sửa Loại Người Dùng',
    'viewTrash'          => 'Thùng Rác',
    'backToList'         => 'Quay Lại Danh Sách',
    'trashTitle'         => 'Loại Người Dùng Đã Xóa',
    
    // Form fields
    'formTitle'          => 'Thông Tin Loại Người Dùng',
    'tenLoai'            => 'Tên Loại',
    'tenLoaiPlaceholder' => 'Nhập tên loại người dùng',
    'tenLoaiHelp'        => 'Tên của loại người dùng (VD: Quản trị viên, Quản lý, Khách hàng)',
    'moTa'               => 'Mô Tả',
    'moTaPlaceholder'    => 'Nhập mô tả',
    'moTaHelp'           => 'Mô tả ngắn gọn về loại người dùng này và quyền hạn của họ',
    
    // Buttons
    'save'               => 'Lưu',
    'cancel'             => 'Hủy',
    'delete'             => 'Xóa',
    'restore'            => 'Khôi Phục',
    'deletePermanent'    => 'Xóa Vĩnh Viễn',
    
    // Messages
    'createSuccess'      => 'Tạo loại người dùng thành công',
    'updateSuccess'      => 'Cập nhật loại người dùng thành công',
    'deleteSuccess'      => 'Xóa loại người dùng thành công',
    'restoreSuccess'     => 'Khôi phục loại người dùng thành công',
    'permanentDeleteSuccess' => 'Xóa vĩnh viễn loại người dùng thành công',
    'deleteConfirm'      => 'Bạn có chắc chắn muốn xóa loại người dùng này?',
    'permanentDeleteConfirm' => 'Bạn có chắc chắn muốn xóa vĩnh viễn loại người dùng này? Hành động này không thể hoàn tác!',
    'deleteMultipleConfirm' => 'Bạn có chắc chắn muốn xóa các loại người dùng đã chọn?',
    'restoreMultipleConfirm' => 'Bạn có chắc chắn muốn khôi phục các loại người dùng đã chọn?',
    'permanentDeleteMultipleConfirm' => 'Bạn có chắc chắn muốn xóa vĩnh viễn các loại người dùng đã chọn? Hành động này không thể hoàn tác!',
    'emptyList'          => 'Không tìm thấy loại người dùng nào',
    'emptyTrash'         => 'Thùng rác trống',
    
    // Validation errors
    'tenLoaiRequired'    => 'Tên loại người dùng là bắt buộc',
    'tenLoaiMin'         => 'Tên loại người dùng phải có ít nhất 3 ký tự',
    'tenLoaiMax'         => 'Tên loại người dùng không được vượt quá 100 ký tự',
    'tenLoaiUnique'      => 'Loại người dùng với tên này đã tồn tại',
    'moTaMax'            => 'Mô tả không được vượt quá 255 ký tự',
]; 