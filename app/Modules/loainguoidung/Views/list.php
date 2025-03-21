<?= $this->extend('layouts/default'); ?>

<?= $this->section('linkHref') ?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/datatables.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/responsive/css/responsive.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/buttons/css/buttons.bootstrap4.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => $title,
    'dashboard_url' => site_url('users/dashboard'),
    'breadcrumbs' => [
        ['title' => lang('LoaiNguoiDung.manageTitle'), 'active' => true],
    ],
    'actions' => [
        ['url' => site_url('loainguoidung/new'), 'title' => lang('LoaiNguoiDung.createNew')],
        ['url' => site_url('loainguoidung/trash'), 'title' => lang('LoaiNguoiDung.trash')]
    ]
]) ?>
<?= $this->endSection() ?>

<?= $this->section('content'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="card radius-10">
            <div class="card-body">
                <!-- Hiển thị thông báo lỗi nếu có -->
                <?php if (session()->has('error')) : ?>
                    <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                        <div class="text-white"><?= session('error') ?></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('success')) : ?>
                    <div class="alert alert-success border-0 bg-success alert-dismissible fade show">
                        <div class="text-white"><?= session('success') ?></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Form xử lý hàng loạt -->
                <form action="<?= site_url('loainguoidung/bulkAction') ?>" method="post" id="form-loainguoidung">
                    <?= csrf_field() ?>
                    <input type="hidden" name="selected_ids" id="selected-ids" value="">

                    <div class="table-responsive">
                        <!-- Render Table sử dụng helper tableRender -->
                        <?php 
                        // Load tableRender helper
                        helper('tableRender');
                        
                        // Thiết lập các tùy chọn cho bảng
                        $tableOptions = [
                            'id' => 'loainguoidung-table',
                            'headers' => [
                                ['title' => '<input type="checkbox" id="select-all">', 'data' => 'checkbox', 'width' => '50px'],
                                ['title' => 'ID', 'data' => 'loai_nguoi_dung_id', 'width' => '80px'],
                                ['title' => lang('LoaiNguoiDung.name'), 'data' => 'ten_loai'],
                                ['title' => lang('LoaiNguoiDung.description'), 'data' => 'mo_ta'],
                                ['title' => lang('LoaiNguoiDung.status'), 'data' => 'status', 'width' => '100px'],
                                ['title' => lang('LoaiNguoiDung.actions'), 'data' => 'actions', 'width' => '200px']
                            ],
                            'data' => [],
                        ];
                        
                        // Duyệt qua dữ liệu và thiết lập mỗi hàng
                        foreach ($loaiNguoiDung as $item) {
                            $row = [
                                'checkbox' => '<input type="checkbox" class="select-item" name="ids[]" value="' . $item->loai_nguoi_dung_id . '">',
                                'loai_nguoi_dung_id' => $item->loai_nguoi_dung_id,
                                'ten_loai' => $item->ten_loai,
                                'mo_ta' => $item->mo_ta ? strip_tags(substr($item->mo_ta, 0, 100)) . '...' : '',
                                'status' => $item->status ? '<span class="badge bg-success">' . lang('LoaiNguoiDung.statusActive') . '</span>' : '<span class="badge bg-danger">' . lang('LoaiNguoiDung.statusInactive') . '</span>',
                                'actions' => '
                                    <a href="' . site_url('loainguoidung/edit/' . $item->loai_nguoi_dung_id) . '" class="btn btn-sm btn-primary">
                                        <i class="bx bx-edit"></i> ' . lang('LoaiNguoiDung.edit') . '
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="' . $item->loai_nguoi_dung_id . '">
                                        <i class="bx bx-trash"></i> ' . lang('LoaiNguoiDung.delete') . '
                                    </button>'
                            ];
                            $tableOptions['data'][] = $row;
                        }
                        
                        // Render bảng
                        echo render_table($tableOptions);
                        ?>
                    </div>

                    <div class="mt-3">
                        <button type="button" id="trash-selected" class="btn btn-danger" disabled>
                            <i class="bx bx-trash me-1"></i> <?= lang('LoaiNguoiDung.trashSelected') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/datatables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/buttons/js/dataTables.buttons.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/buttons/js/buttons.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/jszip/jszip.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/pdfmake/pdfmake.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/pdfmake/vfs_fonts.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/buttons/js/buttons.html5.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/buttons/js/buttons.print.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables/buttons/js/buttons.colVis.min.js') ?>"></script>

<script>
$(document).ready(function() {
    // Khởi tạo DataTable với các tùy chọn
    $('#loainguoidung-table').DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
        ],
        language: {
            url: '<?= base_url('assets/plugins/datatables/i18n/vi.json') ?>'
        },
        pageLength: 10
    });

    // Xử lý select all
    $('#select-all').on('click', function() {
        $('.select-item').prop('checked', this.checked);
        updateSelectedIds();
        updateBulkActionButtons();
    });

    // Xử lý khi checkbox thay đổi
    $('.select-item').on('change', function() {
        updateSelectedIds();
        updateBulkActionButtons();
    });

    // Cập nhật giá trị các IDs đã chọn
    function updateSelectedIds() {
        var selectedIds = $('.select-item:checked').map(function() {
            return $(this).val();
        }).get();
        
        $('#selected-ids').val(selectedIds.join(','));
    }

    // Cập nhật trạng thái nút bulk action
    function updateBulkActionButtons() {
        var selectedCount = $('.select-item:checked').length;
        $('#trash-selected').prop('disabled', selectedCount === 0);
    }

    // Xử lý khi nhấn nút xóa
    $('.btn-delete').on('click', function() {
        var id = $(this).data('id');
        confirmDelete(id);
    });

    // Xác nhận xóa
    function confirmDelete(id) {
        Swal.fire({
            title: '<?= lang('LoaiNguoiDung.confirmDelete') ?>',
            text: '<?= lang('LoaiNguoiDung.confirmDeleteDesc') ?>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<?= lang('LoaiNguoiDung.confirmYes') ?>',
            cancelButtonText: '<?= lang('LoaiNguoiDung.confirmNo') ?>'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= site_url('loainguoidung/delete/') ?>' + id;
            }
        });
    }

    // Xử lý khi nhấn nút xóa hàng loạt
    $('#trash-selected').on('click', function() {
        var selectedIds = $('#selected-ids').val();
        if (selectedIds) {
            Swal.fire({
                title: '<?= lang('LoaiNguoiDung.confirmTrashSelected') ?>',
                text: '<?= lang('LoaiNguoiDung.confirmTrashSelectedDesc') ?>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<?= lang('LoaiNguoiDung.confirmYes') ?>',
                cancelButtonText: '<?= lang('LoaiNguoiDung.confirmNo') ?>'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form-loainguoidung').submit();
                }
            });
        }
    });
});
</script>
<?= $this->endSection() ?> 