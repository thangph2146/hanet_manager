<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/buttons.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/responsive.bootstrap5.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('title') ?>DANH SÁCH NGƯỜI DÙNG ĐÃ XÓA<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Danh sách Người Dùng đã xóa',
    'dashboard_url' => site_url('users/dashboard'),
    'breadcrumbs' => [
        ['title' => 'Quản lý Người Dùng', 'url' => site_url('nguoidung')],
        ['title' => 'Danh sách đã xóa', 'active' => true]
    ]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="table-responsive">
    <?= form_open("nguoidung/restore", ['class' => 'row g3']) ?>
    <div class="col-12 mb-3">
        <button type="submit" class="btn btn-success">Khôi phục</button>
    </div>

    <?= view('components/_table', [
        'card_title' => 'Danh Sách Người Dùng đã xóa',
        'headers' => [
            '<input type="checkbox" id="select-all" />', 
            'AccountId', 
            'FullName', 
            'Ngày xóa',
            'Action'
        ],  
        'data' => $data,
        'columns' => [
            [
                'type' => 'checkbox',
                'id_field' => 'id',
                'name' => 'id[]'
            ],
            [
                'field' => 'AccountId'
            ],
            [
                'field' => 'FullName'
            ],
            [
                'type' => 'date',
                'field' => 'deleted_at',
                'format' => 'd/m/Y H:i:s'
            ],
            [
                'type' => 'actions',
                'buttons' => [
                    [
                        'url_prefix' => '/nguoidung/restore/',
                        'id_field' => 'u_id',
                        'title_field' => 'u_username',
                        'title' => 'Khôi phục %s',
                        'icon' => 'fadeIn animated bx bx-revision',
                        'class' => 'btn btn-sm btn-outline-success'
                    ],
                    [
                        'url_prefix' => '/nguoidung/forcedelete/',
                        'id_field' => 'u_id',
                        'title_field' => 'u_username',
                        'title' => 'Xóa vĩnh viễn %s',
                        'icon' => 'lni lni-trash',
                        'class' => 'btn btn-sm btn-outline-danger',
                        'js' => 'onclick="return confirm(\'Bạn thật sự muốn xóa vĩnh viễn người dùng này?\')"'
                    ]
                ]
            ]
        ],
        'options' => [
            'table_id' => setting('App.table_id')
        ]
    ]) ?>
    </form>
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
<?= $this->endSection() ?> 