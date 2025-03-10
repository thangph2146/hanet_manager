<?php
$table = new \CodeIgniter\View\Table();

$template = [
	'table_open' => '<table id="example2" class="table table-bordered table-striped">',
	'heading_cell_start' => '<th class="all text-center">',
];
$table->setCaption('Danh Sách Permissions')->setTemplate($template);
$table->setHeading(['<input type="checkbox" id="select-all" />', 'Tên Code', 'Tên Hiển thị', 'Mô tả ngắn', 'Đã Chọn']);
if (count($allPermissions) > 0) {
	foreach ($allPermissions as $show) {
		$table->addRow([
			view_cell('\App\Libraries\MyButton::inputCheck', [
				'class' => 'check-select-p',
				'name' => 'permission_id[]',
				'id' => $show->p_id,
				'array' => $permissionsOfRole,
				'label' => ''
			]),
			$show->p_name,
			$show->p_display_name,
			$show->p_description,
			in_array($show->p_id, $permissionsOfRole) ? view_cell('\App\Libraries\MyButton::iconChecked', ['label' => 'checked']) : ''
		]);
	}
}
$table->setEmpty('&nbsp;');
echo $table->generate();
?>

