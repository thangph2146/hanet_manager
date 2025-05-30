<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>QUẢN LÝ USERS<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
	<div class="breadcrumb-title pe-3">Quản lý Users</div>
	<div class="ps-3">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0 p-0">
				<li class="breadcrumb-item"><a href="<?= site_url('users/dashboard') ?>"><i class="bx bx-home-alt"></i></a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Quản lý Users</li>
			</ol>
		</nav>
	</div>
	<div class="ms-auto">
		<div class="btn-group">
			<button type="button" class="btn btn-primary">Chức năng</button>
			<button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
			</button>
			<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
				<a class="dropdown-item" href="<?= site_url('/users/new') ?>">Tạo User</a>
				<a class="dropdown-item" href="<?= site_url('/users/listdeleted') ?>">List Deleted User</a>
			</div>
		</div>
	</div>
</div>
<!--end breadcrumb-->
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="table-responsive">
	<?= form_open("users/resetpassword", ['class' => 'row g3']) ?>
	<div class="col-12 mb-3">
		<button type="submit" class="btn btn-primary">ResetPassWord</button>
	</div>
<?php
	$table = new \CodeIgniter\View\Table();

	$template = [
		'table_open' => '<table id="example2" class="table table-striped table-bordered">',
		'heading_cell_start' => '<th class="all text-center">',
	];

	$table->setCaption('Danh Sách User')->setTemplate($template);
	$table->setHeading(['<input type="checkbox" id="select-all" />', 'Tên đăng nhập', 'Họ và Tên', 'Trạng thái','Action']);
	if (count($data) > 0) {
		foreach ($data as $show) {
			$table->addRow([
				view_cell('\App\Libraries\MyButton::inputCheck', [
					'class' => 'check-select-p',
					'name' => 'u_id[]',
					'id' => $show->u_id,
					'array' => [],
					'label' => ''
				]),
				$show->u_username,
				$show->u_FullName,
				($show->u_status == 1) ? view_cell('\App\Libraries\MyButton::iconChecked', ['label' => 'Hoạt động']) : 'Đã khóa!!',
				view_cell('\App\Libraries\MyButton::buttonEditDelete', [
					'url' => site_url('/users/edit/'.$show->u_id),
					'class' => 'btn btn-default',
					'style' => '',
					'js' => '',
					'title' => "Edit {$show->u_username}",
					'icon' => 'fadeIn animated bx bx-edit',
					'label' => ''
				]) .
				view_cell('\App\Libraries\MyButton::buttonEditDelete', [
					'url' => site_url('/users/delete/'.$show->u_id),
					'class' => 'btn btn-default',
					'style' => '',
                    'js' => 'onclick=' . '\'' . 'return confirm("Bạn thật sự muốn xóa User ' .$show->u_id . ' ? \nIt may cause errors where it is currently being used !!")' . '\'',
					'title' => "Delete {$show->u_username}",
					'icon' => 'lni lni-trash',
					'label' => ''
				]) .
				view_cell('\App\Libraries\MyButton::buttonEditDelete', [
					'url' => site_url('/users/assignroles/'.$show->u_id),
					'class' => 'btn btn-outline-primary px-5 radius-30',
					'style' => '',
					'js' => '',
					'title' => "Gán quyền cho {$show->u_username}",
					'icon' => '',
					'label' => 'Assign Roles'
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
