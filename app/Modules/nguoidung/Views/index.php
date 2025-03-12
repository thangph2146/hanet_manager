<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/buttons.bootstrap5.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatable/css/responsive.bootstrap5.min.css') ?>">
<?= $this->endSection() ?>
<?= $this->section('title') ?>QUẢN LÝ NGƯỜI DÙNG<?= $this->endSection() ?>



<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Quản lý Người Dùng',
	'dashboard_url' => site_url('users/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Người Dùng', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/nguoidung/new'), 'title' => 'Tạo Người Dùng'],
		['url' => site_url('/nguoidung/listdeleted'), 'title' => 'Danh sách Người Dùng đã xóa']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="table-responsive">
	<?= form_open("nguoidung/resetpassword", ['class' => 'row g3']) ?>
	<div class="col-12 mb-3">
		<button type="submit" class="btn btn-primary">ResetPassWord</button>
	</div>
<?= view('components/_table', [
    'caption' => 'Danh Sách Người Dùng',
    'headers' => [
        '<input type="checkbox" id="select-all" />', 
        'AccountId', 
        'FullName', 
        'Status',
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
            'type' => 'status',
            'field' => 'status',
            'active_label' => 'Hoạt động',
            'inactive_label' => 'Đã khóa!!'
        ],
        [
            'type' => 'actions',
            'buttons' => [
                [
                    'url_prefix' => '/nguoidung/edit/',
                    'id_field' => 'id',
                    'title_field' => 'FullName',
                    'title' => 'Edit %s',
                    'icon' => 'fadeIn animated bx bx-edit'
                ],
                [
                    'url_prefix' => '/nguoidung/delete/',
                    'id_field' => 'id',
                    'title_field' => 'FullName',
                    'title' => 'Delete %s',
                    'icon' => 'lni lni-trash',
                    'js' => 'onclick="return confirm(\'Bạn thật sự muốn xóa Người Dùng này?\')"'
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