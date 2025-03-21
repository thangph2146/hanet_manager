<?= $this->extend('layouts/default') ?>

<?= $this->section('linkHref') ?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/buttons.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/responsive.bootstrap5.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('title') ?><?= lang('LoaiNguoiDung.trashedTitle') ?><?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => lang('LoaiNguoiDung.trashedTitle'),
    'dashboard_url' => site_url('users/dashboard'),
    'breadcrumbs' => [
        ['title' => lang('LoaiNguoiDung.manageTitle'), 'url' => site_url('loainguoidung')],
        ['title' => lang('LoaiNguoiDung.trashedTitle'), 'active' => true]
    ],
    'actions' => [
        ['url' => site_url('/loainguoidung'), 'title' => lang('LoaiNguoiDung.backToList')],
        ['url' => site_url('/loainguoidung/dashboard'), 'title' => lang('LoaiNguoiDung.dashboard')]
    ]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="table-responsive">
    <?php if (session()->has('message') || session()->has('success')) : ?>
        <div class="alert alert-success border-0 bg-success alert-dismissible fade show">
            <div class="text-white"><?= session('message') ?? session('success') ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif ?>

    <?php if (session()->has('error')) : ?>
        <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
            <div class="text-white"><?= session('error') ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif ?>

    <?php
    // Tải helper tableRender
    helper('tableRender');
    
    // Form xử lý khôi phục nhiều mục
    echo form_open(site_url('loainguoidung/restoreMultiple'), ['id' => 'restore-form', 'class' => 'mb-3']);
    echo csrf_field();
    ?>
    <input type="hidden" name="selected_ids" id="selected_ids" value="">
    
    <div class="mb-3 d-flex gap-2">
        <button type="submit" class="btn btn-success restore-selected" disabled>
            <i class="bx bx-revision"></i> <?= lang('LoaiNguoiDung.restoreSelected') ?>
        </button>
        
        <button type="button" class="btn btn-danger permanent-delete-selected" disabled>
            <i class="bx bx-trash"></i> <?= lang('LoaiNguoiDung.permanentDeleteSelected') ?>
        </button>
    </div>
    
    <?php
    // Thiết lập các tùy chọn cho bảng
    $options = [
        'headings' => [
            'id' => lang('LoaiNguoiDung.id'),
            'ten_loai_nguoi_dung' => lang('LoaiNguoiDung.name'),
            'mo_ta' => lang('LoaiNguoiDung.description'),
            'deleted_at' => lang('LoaiNguoiDung.deleted_at')
        ],
        'checkbox' => true,
        'checkbox_name' => 'loai_nguoi_dung_id[]',
        'actions' => [
            lang('LoaiNguoiDung.restore') => 'javascript:restoreItem({id});',
            lang('LoaiNguoiDung.permanentDelete') => 'javascript:permanentDeleteItem({id});'
        ],
        'id_field' => 'id',
        'table_id' => 'deletedLoaiNguoiDungTable',
        'class' => 'table table-striped table-bordered',
        'caption' => lang('LoaiNguoiDung.trashedTitle')
    ];
    
    // Hiển thị bảng
    echo render_table($loai_nguoi_dung, $options);
    
    echo form_close();
    ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<!-- DataTables JS -->
<script src="<?= base_url('assets/plugins/datatable/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/dataTables.buttons.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/buttons.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/jszip.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/pdfmake.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/vfs_fonts.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/buttons.html5.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/buttons.print.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/buttons.colVis.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatable/js/responsive.bootstrap5.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>

<script>
$(document).ready(function() {
    // Khởi tạo DataTable
    var table = $('#deletedLoaiNguoiDungTable').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        language: {
            url: '<?= base_url('assets/plugins/datatable/lang/vi.json') ?>'
        }
    });
    
    // Xử lý khi click vào checkbox select-all
    $('#select-all').on('click', function() {
        $('input[name="loai_nguoi_dung_id[]"]').prop('checked', this.checked);
        updateButtonState();
    });
    
    // Cập nhật trạng thái nút khi click vào checkbox
    $(document).on('change', 'input[name="loai_nguoi_dung_id[]"]', function() {
        updateButtonState();
    });
    
    // Cập nhật danh sách ID đã chọn và trạng thái các nút
    function updateButtonState() {
        var selectedIds = [];
        $('input[name="loai_nguoi_dung_id[]"]:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        $('#selected_ids').val(selectedIds.join(','));
        
        // Enable/disable các nút dựa vào có mục nào được chọn hay không
        if (selectedIds.length > 0) {
            $('.restore-selected').prop('disabled', false);
            $('.permanent-delete-selected').prop('disabled', false);
        } else {
            $('.restore-selected').prop('disabled', true);
            $('.permanent-delete-selected').prop('disabled', true);
        }
    }
    
    // Xác nhận trước khi khôi phục nhiều mục
    $('#restore-form').on('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: '<?= lang('App.confirmRestore') ?>',
            text: '<?= lang('App.confirmRestoreMultiple') ?>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<?= lang('App.restore') ?>',
            cancelButtonText: '<?= lang('App.cancel') ?>'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
    
    // Xác nhận trước khi xóa vĩnh viễn nhiều mục
    $('.permanent-delete-selected').on('click', function() {
        var selectedIds = $('#selected_ids').val();
        
        if (!selectedIds) {
            return;
        }
        
        Swal.fire({
            title: '<?= lang('App.confirmPermanentDelete') ?>',
            text: '<?= lang('App.confirmPermanentDeleteMultiple') ?>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<?= lang('App.permanentDelete') ?>',
            cancelButtonText: '<?= lang('App.cancel') ?>'
        }).then((result) => {
            if (result.isConfirmed) {
                // Tạo form mới cho việc xóa vĩnh viễn
                var form = $('<form>', {
                    'method': 'post',
                    'action': '<?= site_url('loainguoidung/permanentDeleteMultiple') ?>'
                });
                
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '<?= csrf_token() ?>',
                    'value': '<?= csrf_hash() ?>'
                }));
                
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'selected_ids',
                    'value': selectedIds
                }));
                
                $('body').append(form);
                form.submit();
            }
        });
    });
});

// Xác nhận trước khi khôi phục một mục
function restoreItem(id) {
    Swal.fire({
        title: '<?= lang('App.confirmRestore') ?>',
        text: '<?= lang('App.confirmRestoreSingle') ?>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<?= lang('App.restore') ?>',
        cancelButtonText: '<?= lang('App.cancel') ?>'
    }).then((result) => {
        if (result.isConfirmed) {
            // Tạo form cho việc khôi phục
            var form = $('<form>', {
                'method': 'post',
                'action': '<?= site_url('loainguoidung/restore/') ?>' + id
            });
            
            form.append($('<input>', {
                'type': 'hidden',
                'name': '<?= csrf_token() ?>',
                'value': '<?= csrf_hash() ?>'
            }));
            
            $('body').append(form);
            form.submit();
        }
    });
}

// Xác nhận trước khi xóa vĩnh viễn một mục
function permanentDeleteItem(id) {
    Swal.fire({
        title: '<?= lang('App.confirmPermanentDelete') ?>',
        text: '<?= lang('App.confirmPermanentDeleteSingle') ?>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '<?= lang('App.permanentDelete') ?>',
        cancelButtonText: '<?= lang('App.cancel') ?>'
    }).then((result) => {
        if (result.isConfirmed) {
            // Tạo form cho việc xóa vĩnh viễn
            var form = $('<form>', {
                'method': 'post',
                'action': '<?= site_url('loainguoidung/permanentDelete/') ?>' + id
            });
            
            form.append($('<input>', {
                'type': 'hidden',
                'name': '<?= csrf_token() ?>',
                'value': '<?= csrf_hash() ?>'
            }));
            
            $('body').append(form);
            form.submit();
        }
    });
}
</script>
<?= $this->endSection() ?>