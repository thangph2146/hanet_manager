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
        ['title' => 'Quản lý Người Dùng', 'url' => site_url('/nguoidung'), 'active' => false],
        ['title' => 'Danh sách Người Dùng đã xóa', 'active' => true]
    ],
    'actions' => [
		['url' => site_url('/nguoidung/new'), 'title' => 'Tạo Người Dùng'],
		['url' => site_url('/nguoidung'), 'title' => 'Danh sách Người Dùng']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="table-responsive">
    <?= form_open("nguoidung/restoreusers", ['class' => 'row g3']) ?>
    <div class="col-12 mb-3">
        <button type="submit" class="btn btn-success">Khôi phục người dùng đã chọn</button>
        <button type="submit" class="btn btn-danger" formaction="<?= site_url('nguoidung/force-delete') ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn những người dùng này?')">Xóa vĩnh viễn</button>
    </div>
    <?= view('components/_table', [
        'caption' => 'Danh Sách Người Dùng đã xóa',
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
                'name' => 'ids[]'
            ],
            [
                'field' => 'AccountId'
            ],
            [
                'field' => 'FullName'
            ],
            [
                'field' => 'deleted_at',
                'format' => 'date'
            ],
            [
                'type' => 'actions',
                'buttons' => [
                    [
                        'url_prefix' => '/nguoidung/restoreusers/',
                        'id_field' => 'id',
                        'title_field' => 'FullName',
                        'title' => 'Khôi phục %s',
                        'icon' => 'fadeIn animated bx bx-revision',
                        'js' => 'onclick="return confirm(\'Bạn có chắc chắn muốn khôi phục người dùng này?\')"'
                    ]
                ]
            ]
        ],
        'options' => [
            'table_id' => setting('App.table_id')
        ]
    ]) 
    ?>
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