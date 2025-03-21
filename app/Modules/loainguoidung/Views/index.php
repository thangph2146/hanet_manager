<?= $this->extend('layouts/default') ?>

<?= $this->section('linkHref') ?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/buttons.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/responsive.bootstrap5.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('title') ?><?= lang('LoaiNguoiDung.moduleTitle') ?><?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => lang('LoaiNguoiDung.manageTitle'),
    'dashboard_url' => site_url('users/dashboard'),
    'breadcrumbs' => [
        ['title' => lang('LoaiNguoiDung.manageTitle'), 'active' => true]
    ],
    'actions' => [
        ['url' => site_url('/loainguoidung/new'), 'title' => lang('LoaiNguoiDung.createNew')],
        ['url' => site_url('/loainguoidung/dashboard'), 'title' => lang('LoaiNguoiDung.dashboard')],
        ['url' => site_url('/loainguoidung/listdeleted'), 'title' => lang('LoaiNguoiDung.viewTrash')]
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
    
    // Form xử lý xóa nhiều mục
    echo form_open(site_url('loainguoidung/deleteMultiple'), ['id' => 'delete-form', 'class' => 'mb-3']);
    echo csrf_field();
    ?>
    <input type="hidden" name="selected_ids" id="selected_ids" value="">
    
    <div class="mb-3">
        <button type="submit" class="btn btn-danger delete-selected" disabled>
            <i class="bx bx-trash"></i> <?= lang('LoaiNguoiDung.deleteSelected') ?>
        </button>
    </div>
    
    <?php
    // Thiết lập các tùy chọn cho bảng
    $options = [
        'headings' => [
            'id' => lang('LoaiNguoiDung.id'),
            'ten_loai_nguoi_dung' => lang('LoaiNguoiDung.name'),
            'mo_ta' => lang('LoaiNguoiDung.description'),
            'status' => lang('LoaiNguoiDung.status'),
            'created_at' => lang('LoaiNguoiDung.created_at')
        ],
        'checkbox' => true,
        'checkbox_name' => 'loai_nguoi_dung_id[]',
        'actions' => [
            lang('LoaiNguoiDung.edit') => site_url('loainguoidung/edit/{id}'),
            lang('LoaiNguoiDung.delete') => 'javascript:deleteItem({id});'
        ],
        'id_field' => 'id',
        'table_id' => 'loaiNguoiDungTable',
        'class' => 'table table-striped table-bordered',
        'caption' => lang('LoaiNguoiDung.listTitle')
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
    var table = $('#loaiNguoiDungTable').DataTable({
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
        updateDeleteButtonState();
    });
    
    // Cập nhật trạng thái nút Delete khi click vào checkbox
    $(document).on('change', 'input[name="loai_nguoi_dung_id[]"]', function() {
        updateDeleteButtonState();
    });
    
    // Cập nhật danh sách ID đã chọn và trạng thái nút Delete
    function updateDeleteButtonState() {
        var selectedIds = [];
        $('input[name="loai_nguoi_dung_id[]"]:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        $('#selected_ids').val(selectedIds.join(','));
        
        // Enable/disable nút Delete dựa vào có mục nào được chọn hay không
        if (selectedIds.length > 0) {
            $('.delete-selected').prop('disabled', false);
        } else {
            $('.delete-selected').prop('disabled', true);
        }
    }
    
    // Xác nhận trước khi xóa nhiều mục
    $('#delete-form').on('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: '<?= lang('App.confirmDelete') ?>',
            text: '<?= lang('App.confirmDeleteMultiple') ?>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<?= lang('App.delete') ?>',
            cancelButtonText: '<?= lang('App.cancel') ?>'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
});

// Xác nhận trước khi xóa một mục
function deleteItem(id) {
    Swal.fire({
        title: '<?= lang('App.confirmDelete') ?>',
        text: '<?= lang('App.confirmDeleteSingle') ?>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '<?= lang('App.delete') ?>',
        cancelButtonText: '<?= lang('App.cancel') ?>'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= site_url('loainguoidung/delete/') ?>' + id;
        }
    });
}
</script>
<?= $this->endSection() ?>