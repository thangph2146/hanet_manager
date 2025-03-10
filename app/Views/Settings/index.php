<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>QUẢN LÝ SETTINGS<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="breadcrumb-title pe-3">Quản lý Settings</div>
	<div class="ps-3">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a href="<?= site_url('Users/dashboard') ?>"><i class="bx bx-home-alt"></i></a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Quản lý Settings</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
		<div class="btn-group">
			<button type="button" class="btn btn-primary">Chức năng</button>
			<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
			</button>
			<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
				<a class="dropdown-item" href="<?= site_url('Settings/new') ?>">Tạo Setting</a>
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

	$table->setCaption('Danh Sách Settings')->setTemplate($template);
	$table->setHeading(['ID', 'Class', 'Key', 'Value', 'type', 'Context', 'Action']);
	if (count($data) > 0) {
		foreach ($data as $show) {
			$table->addRow([
				$show->id,
				$show->class,
				$show->key,
				$show->value,
				$show->type,
				$show->context,
				view_cell('\App\Libraries\MyButton::buttonEditDelete', [
					'url' => site_url('/Settings/edit/'.$show->id),
					'class' => 'btn btn-default',
					'style' => '',
					'js' => '',
					'title' => "Edit {$show->context}",
					'icon' => 'fadeIn animated bx bx-edit',
					'label' => ''
				]) .
				view_cell('\App\Libraries\MyButton::buttonEditDelete', [
					'url' => site_url('/Settings/delete/'.$show->id),
					'class' => 'btn btn-default',
					'style' => '',
					'js' => 'onclick=' . '\'' . 'return confirm("Bạn thật sự muốn xóa Setting ' .$show->id . ' ? \nIt may cause errors where it is currently being used !!")' . '\'',
					'title' => "Delete {$show->context}",
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
