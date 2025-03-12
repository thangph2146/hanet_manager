<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>QUẢN LÝ NGƯỜI DÙNG<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="breadcrumb-title pe-3">Quản lý Người Dùng</div>
	<div class="ps-3">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a href="<?= site_url('nguoidung/dashboard') ?>"><i class="bx bx-home-alt"></i></a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Quản lý Người Dùng</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
		<div class="btn-group">
			<button type="button" class="btn btn-primary">Chức năng</button>
			<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
			</button>
			<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
				<a class="dropdown-item" href="<?= site_url('/nguoidung/new') ?>">Tạo Người Dùng</a>
				<a class="dropdown-item" href="<?= site_url('/nguoidung/listdeleted') ?>">Danh sách Người Dùng đã xóa</a>
			</div>
		</div>
	</div>
</div>
<!--end breadcrumb-->
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="table-responsive">
	<?= form_open("nguoidung/resetpassword", ['class' => 'row g3']) ?>
	<div class="col-12 mb-3">
		<button type="submit" class="btn btn-primary">ResetPassWord</button>
	</div>
<?php
	$table = new \CodeIgniter\View\Table();

	$template = [
		'table_open' => '<table id="example2" class="table table-striped table-bordered">',
		'heading_cell_start' => '<th class="all text-center">',
	];

	$table->setCaption('Danh Sách Người Dùng')->setTemplate($template);
	$table->setHeading(['<input type="checkbox" id="select-all" />', 'AccountId', 'FullName', 'Status','Action']);
	if (count($data) > 0) {
		foreach ($data as $show) {
			$table->addRow([
				view_cell('\App\Libraries\MyButton::inputCheck', [
					'class' => 'check-select-p',
					'name' => 'id[]',
					'id' => $show->id,
					'array' => [],
					'label' => ''
				]),
				$show->AccountId,
				$show->FullName,
				($show->status == 1) ? view_cell('\App\Libraries\MyButton::iconChecked', ['label' => 'Hoạt động']) : 'Đã khóa!!',
				view_cell('\App\Libraries\MyButton::buttonEditDelete', [
					'url' => site_url('/nguoidung/edit/'.$show->u_id),
					'class' => 'btn btn-default',
					'style' => '',
					'js' => '',
					'title' => "Edit {$show->u_username}",
					'icon' => 'fadeIn animated bx bx-edit',
					'label' => ''
				]) .
				view_cell('\App\Libraries\MyButton::buttonEditDelete', [
					'url' => site_url('/nguoidung/delete/'.$show->u_id),
					'class' => 'btn btn-default',
					'style' => '',
					'js' => 'onclick=' . '\'' . 'return confirm("Bạn thật sự muốn xóa Người Dùng ' .$show->u_id . ' ? \nIt may cause errors where it is currently being used !!")' . '\'',
					'title' => "Delete {$show->u_username}",
					'icon' => 'lni lni-trash',
					'label' => ''
				])
			]);
		}
	}

	$table->setEmpty('&nbsp;');
	echo $table->generate();
?>
	</form>
</div>
<?= $this->endSection() ?> 