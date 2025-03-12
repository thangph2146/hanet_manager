<?= $this->extend('layouts/default') ?>

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