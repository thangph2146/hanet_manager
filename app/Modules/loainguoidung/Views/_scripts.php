<?php
/**
 * Scripts cho module loại người dùng
 * Chứa các xử lý JavaScript cho việc xóa, đổi trạng thái, xóa hàng loạt
 */
?>

<script>
$(function() {
    // Xử lý DataTable
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "language": {
            "url": "<?= base_url('assets/plugins/datatables/Vietnamese.json') ?>"
        }
    });

    // Xử lý sự kiện xóa
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        
        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: "Dữ liệu sẽ được chuyển vào thùng rác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Đồng ý xóa',
            cancelButtonText: 'Hủy bỏ'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('loainguoidung/delete') ?>/' + id,
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

    // Xử lý sự kiện thay đổi trạng thái
    $(document).on('click', '.btn-status', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const currentStatus = $(this).closest('tr').find('td:nth-child(5) .badge').hasClass('badge-success') ? 1 : 0;
        const newStatus = currentStatus === 1 ? 0 : 1;
        const statusText = newStatus === 1 ? 'kích hoạt' : 'vô hiệu hóa';
        
        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: `Loại người dùng sẽ được ${statusText}!`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy bỏ'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('loainguoidung/status') ?>/' + id,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    },
                    data: {
                        status: newStatus
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Thành công!',
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

    // Xử lý sự kiện xóa nhiều
    $('#btn-delete-multiple').on('click', function(e) {
        e.preventDefault();
        
        const selectedIds = [];
        $('input[name="loai_nguoi_dung_id[]"]:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        if (selectedIds.length === 0) {
            Swal.fire(
                'Thông báo!',
                'Vui lòng chọn ít nhất một mục để xóa.',
                'info'
            );
            return;
        }
        
        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: "Các mục đã chọn sẽ được chuyển vào thùng rác!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Đồng ý xóa',
            cancelButtonText: 'Hủy bỏ'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('loainguoidung/delete-multiple') ?>',
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