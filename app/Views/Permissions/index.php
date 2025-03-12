<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>QUẢN LÝ PERMISSIONS<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="breadcrumb-title pe-3">Quản lý Permissons</div>
	<div class="ps-3">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a href="<?= site_url('users/dashboard') ?>"><i class="bx bx-home-alt"></i></a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Quản lý Permissons</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
		<div class="btn-group">
			<button type="button" class="btn btn-primary">Chức năng</button>
			<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
			</button>
			<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
				<a class="dropdown-item" href="<?= site_url('permissions/new') ?>">Tạo Permission</a>
				<a class="dropdown-item" href="<?= site_url('permissions/listdeleted') ?>">List Deleted Permission</a>
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
		'table_open' => '<table id="example2" class="table table-striped table-bordered">',
		'heading_cell_start' => '<th class="all">',
	];

	$table->setCaption('Danh Sách Permissions')->setTemplate($template);
	$table->setHeading(['ID', 'Tên Code', 'Tên Hiển thị', 'Mô tả ngắn', 'Trạng thái', 'Actions']);
	if (count($data) > 0) {
		foreach ($data as $show) {
			$table->addRow([
				$show->p_id,
				$show->p_name,
				$show->p_display_name,
				$show->p_description,
				($show->p_status == 1) ? view_cell('\App\Libraries\MyButton::iconChecked', ['label' => 'checked']) : '',
				view_cell('\App\Libraries\MyButton::buttonEditDelete', [
					'url' => site_url('/permissions/edit/'.$show->p_id),
					'class' => 'btn btn-default',
					'style' => '',
					'js' => '',
					'title' => "Edit {$show->p_display_name}",
					'icon' => 'fadeIn animated bx bx-edit',
					'label' => ''
				]) .
				view_cell('\App\Libraries\MyButton::buttonEditDelete', [
					'url' => site_url('/permissions/delete/'.$show->p_id),
					'class' => 'btn btn-default',
					'style' => '',
					'js' => 'onclick=' . '\'' . 'return confirm("Bạn thật sự muốn xóa Permission ' .$show->p_id . ' ? \nIt may cause errors where it is currently being used !!")' . '\'',
					'title' => "Delete {$show->p_display_name}",
					'icon' => 'lni lni-trash',
					'label' => ''
				])
			]);
		}
	}

	$table->setEmpty('&nbsp;');
	echo $table->generate();
?>
</div>
<?= $this->endSection() ?>
