<?php
/**
 * 9/17/2022
 * AUTHOR:PDV-PC
 */
?>
<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>LIST DELETED USERS<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="breadcrumb-title pe-3">List Deleted Users</div>
	<div class="ps-3">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a href="<?= site_url('Users/dashboard') ?>"><i class="bx bx-home-alt"></i></a>
				</li>
				<li class="breadcrumb-item active" aria-current="page"><a href="<?= site_url('/Users') ?>">Quản lý Users</a></li>
				<li class="breadcrumb-item active" aria-current="page">List Deleted Users</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
		<div class="btn-group">
			<button type="button" class="btn btn-primary">Chức năng</button>
			<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
			</button>
			<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
				<a class="dropdown-item" href="<?= site_url('/Users/new') ?>">Tạo User</a>
				<a class="dropdown-item" href="<?= site_url('/Users/listDeleted') ?>">List Deleted Users</a>
			</div>
		</div>
	</div>
</div>
<!--end breadcrumb-->
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="table-responsive">
	<?php
	$table = new \CodeIgniter\View\Table();

	$template = [
		'table_open' => '<table id="example2" class="table table-striped table-bordered display responsive nowrap">',
		'heading_cell_start' => '<th class="all">',
	];

	$table->setCaption('Danh Sách Permissions')->setTemplate($template);
	$table->setHeading(['ID', 'Tên Code', 'Tên Hiển thị', 'Mô tả ngắn', 'Trạng thái', 'Actions']);
	if (count($data) > 0) {
		foreach ($data as $show) {
			$table->addRow([
				$show->u_id,
				'',
				'',
				'',
				view_cell('\App\Libraries\MyButton::iconChecked', [
					'label' => ''
				]),
				view_cell('\App\Libraries\MyButton::iconRestored', [
					'url' => site_url('/Users/restoreUser/'.$show->u_id)
				])
			]);
		}
	}

	$table->setEmpty('&nbsp;');
	echo $table->generate();
	?>
</div>
<?= $this->endSection() ?>

