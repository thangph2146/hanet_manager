<?php
/**
 * Component hiển thị danh sách loại người dùng đã xóa
 * 
 * @param array $loai_nguoi_dungs Mảng các loại người dùng đã xóa
 * @param string $title Tiêu đề, mặc định là "Danh sách loại người dùng đã xóa"
 */

// Giá trị mặc định nếu không được cung cấp
$loai_nguoi_dungs = $loai_nguoi_dungs ?? [];
$title = $title ?? 'Danh sách loại người dùng đã xóa';
?>

<!-- Danh sách loại người dùng đã xóa -->
<?= view('components/_table', [
    'caption' => $title,
    'headers' => [
        '<input type="checkbox" id="select-all" />',
        'STT',
        'Tên loại',
        'Mô tả',
        'Trạng thái',
        'Thao tác'
    ],
    'data' => $loai_nguoi_dungs,
    'columns' => [
        [
            'type' => 'checkbox',
            'id_field' => 'loai_nguoi_dung_id',
            'name' => 'loai_nguoi_dung_id[]'
        ],
        [
            'type' => 'custom',
            'field' => 'stt',
            'render' => function($item, $i) {
                return $i + 1;
            }
        ],
        [
            'field' => 'ten_loai'
        ],
        [
            'field' => 'mo_ta'
        ],
        [
            'type' => 'status',
            'field' => 'status'
        ],
        [
            'type' => 'actions',
            'buttons' => [
                [
                    'url_prefix' => '#',
                    'id_field' => 'loai_nguoi_dung_id',
                    'title_field' => 'ten_loai',
                    'title' => 'Khôi phục %s',
                    'icon' => 'fas fa-undo',
                    'class' => 'btn btn-sm btn-success btn-restore',
                    'js' => 'data-id="' . '{{$item->loai_nguoi_dung_id}}' . '"'
                ],
                [
                    'url_prefix' => '#',
                    'id_field' => 'loai_nguoi_dung_id',
                    'title_field' => 'ten_loai',
                    'title' => 'Xóa vĩnh viễn %s',
                    'icon' => 'fas fa-trash',
                    'class' => 'btn btn-sm btn-danger btn-delete',
                    'js' => 'data-id="' . '{{$item->loai_nguoi_dung_id}}' . '"'
                ]
            ]
        ]
    ],
    'options' => [
        'table_id' => 'example2',
        'template' => [
            'table_open' => '<table id="example2" class="table table-hover table-striped table-bordered mb-0 w-100">'
        ]
    ],
    'card_title' => $title,
    'card_tools' => [
        [
            'url' => site_url('loainguoidung'),
            'title' => 'Quay lại danh sách',
            'icon' => 'fas fa-arrow-left',
            'class' => 'btn btn-primary btn-sm'
        ]
    ],
    'bulk_actions' => [
        [
            'title' => 'Khôi phục mục đã chọn',
            'icon' => 'fas fa-undo',
            'class' => 'btn btn-success btn-sm btn-restore-multiple',
            'id' => 'btn-restore-multiple'
        ],
        [
            'title' => 'Xóa vĩnh viễn mục đã chọn',
            'icon' => 'fas fa-trash',
            'class' => 'btn btn-danger btn-sm btn-delete-multiple',
            'id' => 'btn-delete-multiple'
        ]
    ],
    'pagination' => [
        'per_page' => 10
    ]
]) ?>

<!-- Scripts cho trang danh sách đã xóa -->
<script>
$(function() {
    // Xử lý DataTable
    $("#example2").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "language": {
            "url": "<?= base_url('assets/plugins/datatables/Vietnamese.json') ?>"
        }
    });

    // Xử lý sự kiện khôi phục
    $(document).on('click', '.btn-restore', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        
        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: "Loại người dùng sẽ được khôi phục!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy bỏ'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('loainguoidung/restore') ?>/' + id,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Đã khôi phục!',
                                response.message,
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Lỗi!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Lỗi!',
                            'Đã xảy ra lỗi khi xử lý yêu cầu.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    // Xử lý sự kiện xóa vĩnh viễn
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        
        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: "Dữ liệu sẽ bị xóa vĩnh viễn và không thể khôi phục!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Đồng ý xóa',
            cancelButtonText: 'Hủy bỏ'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('loainguoidung/purge') ?>/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Đã xóa!',
                                response.message,
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Lỗi!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Lỗi!',
                            'Đã xảy ra lỗi khi xử lý yêu cầu.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    // Xử lý checkbox chọn tất cả
    $('#select-all').on('click', function() {
        $('input[name="loai_nguoi_dung_id[]"]').prop('checked', this.checked);
    });

    // Xử lý sự kiện khôi phục nhiều
    $('#btn-restore-multiple').on('click', function(e) {
        e.preventDefault();
        
        const selectedIds = [];
        $('input[name="loai_nguoi_dung_id[]"]:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        if (selectedIds.length === 0) {
            Swal.fire(
                'Thông báo!',
                'Vui lòng chọn ít nhất một mục để khôi phục.',
                'info'
            );
            return;
        }
        
        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: "Các mục đã chọn sẽ được khôi phục!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy bỏ'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('loainguoidung/restore-multiple') ?>',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    },
                    data: {
                        ids: selectedIds
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Đã khôi phục!',
                                response.message,
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Lỗi!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Lỗi!',
                            'Đã xảy ra lỗi khi xử lý yêu cầu.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    // Xử lý sự kiện xóa vĩnh viễn nhiều
    $('#btn-delete-multiple').on('click', function(e) {
        e.preventDefault();
        
        const selectedIds = [];
        $('input[name="loai_nguoi_dung_id[]"]:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        if (selectedIds.length === 0) {
            Swal.fire(
                'Thông báo!',
                'Vui lòng chọn ít nhất một mục để xóa vĩnh viễn.',
                'info'
            );
            return;
        }
        
        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: "Dữ liệu sẽ bị xóa vĩnh viễn và không thể khôi phục!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Đồng ý xóa',
            cancelButtonText: 'Hủy bỏ'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('loainguoidung/purge-multiple') ?>',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    },
                    data: {
                        ids: selectedIds
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Đã xóa!',
                                response.message,
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Lỗi!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Lỗi!',
                            'Đã xảy ra lỗi khi xử lý yêu cầu.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script> 